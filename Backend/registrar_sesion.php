<?php
declare(strict_types=1);

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=utf-8");

function responder(int $codigo, array $datos): void {
    http_response_code($codigo);
    echo json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") { 
    exit; 
    }
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    responder(405, ["exito" => false, "mensaje" => "Método no permitido"]);
}

require_once __DIR__ . "/db.php";

// ── Leer token ─────────────────────────────────────────────────────────────
$cabeceras    = function_exists("getallheaders") ? getallheaders() : [];
$autorizacion = $_SERVER["HTTP_AUTHORIZATION"]
    ?? $cabeceras["Authorization"]
    ?? $cabeceras["authorization"]
    ?? "";

if (!preg_match('/^Bearer\s+(.+)$/i', $autorizacion, $m)) {
    responder(401, 
    [

        "exito" => false, 
        "mensaje" => "Inicia sesión para continuar."

    ]);
}

// ── Leer body ──────────────────────────────────────────────────────────────
$entrada        = json_decode(file_get_contents("php://input"), true);

$numeroDia      = isset($entrada["numero_dia"])      ? (int)   $entrada["numero_dia"]      : 1;
// numero_dia identifica la sesión ordinal dentro de la semana (1 a 6).
$numeroDia      = min(6, max(1, $numeroDia));

$xpTotalSesion  = isset($entrada["xp_total_sesion"]) ? (float) $entrada["xp_total_sesion"] : 0;
$ejerciciosLog  = is_array($entrada["ejercicios"] ?? null) ? $entrada["ejercicios"] : [];

if (count($ejerciciosLog) === 0) {
    responder(400, ["exito" => false, "mensaje" => "Debes completar al menos un ejercicio."]);
}

try {
    $pdo       = ConexionBaseDeDatos();
    $tokenHash = hash("sha256", trim($m[1]));

    // 1. Verificar sesión del usuario
    $stmt = $pdo->prepare("
        SELECT u.id AS usuario_id
        FROM sesiones_auth s
        JOIN usuarios u 
        ON u.id = s.usuario_id
        WHERE s.token_hash = :tk
          AND s.expira_en > NOW()
          AND s.revocado_at IS NULL
        LIMIT 1
    ");
    $stmt->execute(["tk" => $tokenHash]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        responder(401, ["exito" => false, "mensaje" => "Sesión no válida o expirada."]);
    }

    $usuarioId   = $usuario["usuario_id"];
    $pdo->beginTransaction();

    // Serializar el avance del usuario para evitar acreditar dos sesiones a la vez.
    $stmtUsuario = $pdo->prepare("
        SELECT nivel_actual, xp_total
        FROM usuarios
        WHERE id = :uid
        FOR UPDATE
    ");
    $stmtUsuario->execute(["uid" => $usuarioId]);
    $progresoUsuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);
    if (!$progresoUsuario) {
        $pdo->rollBack();
        responder(401, ["exito" => false, "mensaje" => "El usuario de la sesión ya no existe."]);
    }

    $nivelActual = (int) $progresoUsuario["nivel_actual"];
    $xpActual = (int) $progresoUsuario["xp_total"];

    // 2. Obtener o crear la planificación activa del usuario
    // Restricciones de PostgreSQL:
    // - tipo_entrenamiento IN ('FULL_BODY', 'UPPER_LOWER', 'PPL', 'PPL_X2')
    // - dias_entrenamiento BETWEEN 1 AND 6
    // - semana BETWEEN 1 AND 4
    // - estado IN ('activa', 'completada')
    // - UNIQUE (usuario_id, nivel, semana)
    $stmtPlan = $pdo->prepare("
        SELECT id, nivel, semana, dias_entrenamiento, tipo_entrenamiento
        FROM planificaciones_semanales
        WHERE usuario_id = :uid AND estado = 'activa'
        ORDER BY created_at DESC
        LIMIT 1
    ");
    $stmtPlan->execute(["uid" => $usuarioId]);
    $planActual = $stmtPlan->fetch(PDO::FETCH_ASSOC);

    if (!$planActual) {
        // Consultar cantidad de días configurados en dia_semanal
        $stmtDias = $pdo->prepare("SELECT COUNT(*) FROM dia_semanal WHERE usuario_id = :uid");
        $stmtDias->execute(["uid" => $usuarioId]);
        $cantDias = (int) $stmtDias->fetchColumn();
        if ($cantDias === 0) $cantDias = 3;
        if ($cantDias > 6)   $cantDias = 6;

        $tipoDB = 'FULL_BODY';
        if ($cantDias === 4) {
            $tipoDB = 'UPPER_LOWER';
        } elseif ($cantDias === 5) {
            $tipoDB = 'PPL';
        } elseif ($cantDias === 6) {
            $tipoDB = 'PPL_X2';
        }

        // Crear la primera planificación activa si todavía no existe.
        $stmtCrearPlan = $pdo->prepare("
            INSERT INTO planificaciones_semanales
                (id, usuario_id, nivel, semana, dias_entrenamiento, tipo_entrenamiento, estado, created_at)
            VALUES
                (gen_random_uuid(), :uid, :nivel, 1, :dias, :tipo, 'activa', NOW())
            ON CONFLICT (usuario_id, nivel, semana)
            DO UPDATE SET 
                dias_entrenamiento = EXCLUDED.dias_entrenamiento,
                tipo_entrenamiento = EXCLUDED.tipo_entrenamiento,
                estado = 'activa'
            RETURNING id
        ");
        $stmtCrearPlan->execute([
            "uid"   => $usuarioId,
            "nivel" => $nivelActual,
            "dias"  => $cantDias,
            "tipo"  => $tipoDB
        ]);
        $planActual = [
            "id" => $stmtCrearPlan->fetchColumn(),
            "nivel" => $nivelActual,
            "semana" => 1,
            "dias_entrenamiento" => $cantDias,
            "tipo_entrenamiento" => $tipoDB
        ];
    }

    $planificacionId = $planActual["id"];
    $nivelPlan = (int) $planActual["nivel"];
    $semanaPlan = (int) $planActual["semana"];
    $diasPlan = max(1, (int) $planActual["dias_entrenamiento"]);

    $stmtXpSemana = $pdo->prepare("
        SELECT COALESCE(SUM(xp_otorgado), 0)
        FROM sesiones_entrenamiento
        WHERE planificacion_id = :plan_id
    ");
    $stmtXpSemana->execute(["plan_id" => $planificacionId]);
    $xpSemanaAnterior = (int) $stmtXpSemana->fetchColumn();

    $stmtSesionExistente = $pdo->prepare("
        SELECT xp_otorgado
        FROM sesiones_entrenamiento
        WHERE planificacion_id = :plan_id AND numero_dia = :dia
        FOR UPDATE
    ");
    $stmtSesionExistente->execute([
        "plan_id" => $planificacionId,
        "dia" => $numeroDia
    ]);
    $xpSesionAnteriorValor = $stmtSesionExistente->fetchColumn();
    $sesionExistente = $xpSesionAnteriorValor !== false;
    $xpSesionAnterior = (int) ($xpSesionAnteriorValor ?: 0);

    // Repartir los 250 XP enteros entre los días y ajustar la última fracción
    // de cada sesión para que completar todos los días sume exactamente 250.
    $stmtDiasPrevios = $pdo->prepare("
        SELECT COUNT(*)
        FROM sesiones_entrenamiento
        WHERE planificacion_id = :plan_id AND numero_dia <> :dia
    ");
    $stmtDiasPrevios->execute([
        "plan_id" => $planificacionId,
        "dia" => $numeroDia
    ]);
    $diasPrevios = (int) $stmtDiasPrevios->fetchColumn();
    $xpObjetivoDia = intdiv(250, $diasPlan)
        + ($diasPrevios < (250 % $diasPlan) ? 1 : 0);
    $xpMaximoSesion = 250 / $diasPlan;
    $xpSolicitado = is_finite($xpTotalSesion) ? max(0.0, $xpTotalSesion) : 0.0;
    $xpSesionPropuesto = $xpMaximoSesion > 0
        ? (int) round($xpObjetivoDia * min(1.0, $xpSolicitado / $xpMaximoSesion))
        : 0;

    $xpBaseSemana = max(0, $xpSemanaAnterior - $xpSesionAnterior);
    $xpDisponibleSemana = max(0, 250 - $xpBaseSemana);
    $diasRegistrados = $diasPrevios + ($sesionExistente ? 1 : 0);
    if (
        $diasRegistrados >= $diasPlan
        && $xpSolicitado >= $xpMaximoSesion - 0.01
        && $xpSemanaAnterior < 250
    ) {
        // Completar redondeos heredados (por ejemplo 3 sesiones de 83 XP = 249).
        $xpSesionPropuesto = max($xpSesionPropuesto, $xpDisponibleSemana);
    }
    $xpOtorgadoInt = min(
        $xpDisponibleSemana,
        max($xpSesionAnterior, $xpSesionPropuesto)
    );

    // 3. Crear o actualizar registro en sesiones_entrenamiento
    // Restricciones:
    // - estado IN ('en_progreso', 'completada', 'cancelada')
    // - numero_dia BETWEEN 1 AND 6
    // - UNIQUE (planificacion_id, numero_dia)
    $stmtSesion = $pdo->prepare("
        INSERT INTO sesiones_entrenamiento
            (id, usuario_id, fecha, duracion_segundos, estado, xp_otorgado, created_at, planificacion_id, numero_dia)
        VALUES
            (gen_random_uuid(), :uid, NOW(), 0, 'completada', :xp, NOW(), :plan_id, :dia)
        ON CONFLICT (planificacion_id, numero_dia)
        DO UPDATE SET
            fecha = NOW(),
            estado = 'completada',
            xp_otorgado = EXCLUDED.xp_otorgado
        RETURNING id
    ");
    $stmtSesion->execute([
        "uid"     => $usuarioId,
        "xp"      => $xpOtorgadoInt,
        "plan_id" => $planificacionId,
        "dia"     => $numeroDia
    ]);
    $sesionId = $stmtSesion->fetchColumn();
    $xpGanadoAhora = max(0, $xpOtorgadoInt - $xpSesionAnterior);

    // 4. Procesar cada ejercicio y sus series en ejercicios_planificados y series_registro
    $stmtBuscarEjPlan = $pdo->prepare("
        SELECT id FROM ejercicios_planificados
        WHERE planificacion_id = :plan_id 
          AND ejercicio_id = :ej_id 
          AND numero_dia = :dia
        LIMIT 1
    ");

    // Restricciones ejercicios_planificados:
    // - chk_xp_componentes: xp_total = xp_series + xp_repeticiones (debe coincidir exactamente)
    // - estado IN ('pendiente', 'completado')
    // - numero_dia BETWEEN 1 AND 6
    // - UNIQUE (planificacion_id, numero_dia, orden)
    $stmtCrearEjPlan = $pdo->prepare("
        INSERT INTO ejercicios_planificados
            (id, planificacion_id, ejercicio_id, numero_dia, orden, series_objetivo, repeticiones_objetivo, xp_total, xp_series, xp_repeticiones, estado)
        VALUES
            (gen_random_uuid(), :plan_id, :ej_id, :dia, :orden, :series_obj, :reps_obj, :xp_tot, :xp_ser, :xp_rep, 'completado')
        ON CONFLICT (planificacion_id, numero_dia, orden)
        DO UPDATE SET
            ejercicio_id = EXCLUDED.ejercicio_id,
            series_objetivo = EXCLUDED.series_objetivo,
            repeticiones_objetivo = EXCLUDED.repeticiones_objetivo,
            xp_total = EXCLUDED.xp_total,
            xp_series = EXCLUDED.xp_series,
            xp_repeticiones = EXCLUDED.xp_repeticiones,
            estado = 'completado'
        RETURNING id
    ");

    $stmtActualizarEjPlan = $pdo->prepare("
        UPDATE ejercicios_planificados 
        SET estado = 'completado', xp_total = :xp_tot, xp_series = :xp_ser, xp_repeticiones = :xp_rep 
        WHERE id = :id
    ");

    // Restricciones series_registro:
    // - numero_serie > 0
    // - UNIQUE (ejercicio_planificado_id, numero_serie)
    $stmtSerie = $pdo->prepare("
        INSERT INTO series_registro
            (id, sesion_id, numero_serie, peso_kg, repeticiones, completada, created_at, ejercicio_planificado_id)
        VALUES
            (gen_random_uuid(), :sid, :ns, :peso::numeric, :reps, :comp::boolean, NOW(), :ej_plan_id)
        ON CONFLICT (ejercicio_planificado_id, numero_serie)
        DO UPDATE SET
            sesion_id = EXCLUDED.sesion_id,
            peso_kg = EXCLUDED.peso_kg,
            repeticiones = EXCLUDED.repeticiones,
            completada = EXCLUDED.completada
    ");

    $orden = 1;
    foreach ($ejerciciosLog as $ejLog) {
        $ejercicioId = $ejLog["ejercicio_id"] ?? null;
        $series      = $ejLog["series"] ?? [];
        $xpEj        = (float) ($ejLog["xp_ganado"] ?? ($xpTotalSesion / max(count($ejerciciosLog), 1)));

        // Asegurar consistencia matemática estricta: xp_total = xp_series + xp_repeticiones
        $xpTotalRedondeado  = round($xpEj, 4);
        $xpSeriesRedondeado = round($xpTotalRedondeado / 2, 4);
        $xpRepsRedondeado   = round($xpTotalRedondeado - $xpSeriesRedondeado, 4);

        if (!$ejercicioId) {
            $stmtEjFallback = $pdo->prepare("SELECT id FROM ejercicios WHERE activo IS TRUE LIMIT 1 OFFSET :off");
            $stmtEjFallback->execute(["off" => $orden - 1]);
            $ejercicioId = $stmtEjFallback->fetchColumn();
        }

        // Buscar si ya existe el ejercicio planificado para este día y ejercicio
        $stmtBuscarEjPlan->execute([
            "plan_id" => $planificacionId,
            "ej_id"   => $ejercicioId,
            "dia"     => $numeroDia
        ]);
        $ejPlanificadoId = $stmtBuscarEjPlan->fetchColumn();

        if (!$ejPlanificadoId) {
            $stmtCrearEjPlan->execute([
                "plan_id"    => $planificacionId,
                "ej_id"      => $ejercicioId,
                "dia"        => $numeroDia,
                "orden"      => $orden,
                "series_obj" => count($series) > 0 ? count($series) : 3,
                "reps_obj"   => isset($series[0]["repeticiones"]) ? (int) $series[0]["repeticiones"] : 10,
                "xp_tot"     => $xpTotalRedondeado,
                "xp_ser"     => $xpSeriesRedondeado,
                "xp_rep"     => $xpRepsRedondeado
            ]);
            $ejPlanificadoId = $stmtCrearEjPlan->fetchColumn();
        } else {
            $stmtActualizarEjPlan->execute([
                "id"     => $ejPlanificadoId,
                "xp_tot" => $xpTotalRedondeado,
                "xp_ser" => $xpSeriesRedondeado,
                "xp_rep" => $xpRepsRedondeado
            ]);
        }

        // Registrar series de este ejercicio
        $numSerieIndex = 1;
        foreach ($series as $serie) {
            $numSerie = (int) ($serie["numero_serie"] ?? $numSerieIndex);
            if ($numSerie <= 0) $numSerie = $numSerieIndex;

            $stmtSerie->execute([
                "sid"        => $sesionId,
                "ns"         => $numSerie,
                "peso"       => (float) ($serie["peso_kg"]      ?? 0),
                "reps"       => (int)   ($serie["repeticiones"] ?? 0),
                "comp"       => !empty($serie["completada"]) ? 'true' : 'false',
                "ej_plan_id" => $ejPlanificadoId
            ]);
            $numSerieIndex++;
        }
        $orden++;
    }

    $stmtXpSemana->execute(["plan_id" => $planificacionId]);
    $xpSemanaNuevo = (int) $stmtXpSemana->fetchColumn();
    $semanaCompletada = $xpSemanaNuevo >= 250;

    // 5. Registrar en xp_historial solo el XP nuevo, no el total de la sesión
    // si se actualiza un entrenamiento que ya estaba guardado.
    // Restricciones:
    // - motivo IN ('ejercicio_progreso', 'ejercicio_completado', 'semana_completada', 'nivel_completado')
    if ($xpGanadoAhora > 0) {
        $stmtHistorial = $pdo->prepare("
            INSERT INTO xp_historial (id, usuario_id, xp_ganado, motivo, created_at)
            VALUES (gen_random_uuid(), :uid, :xp, 'ejercicio_completado', NOW())
        ");
        $stmtHistorial->execute([
            "uid" => $usuarioId,
            "xp"  => $xpGanadoAhora
        ]);
    }

    // 6. Actualizar XP y nivel en usuarios
    // Cada nivel requiere 1000 XP acumulados (4 semanas x 250 XP).
    $nuevoXp = $xpActual + $xpGanadoAhora;
    $nuevoNivel = min(10, (int) floor($nuevoXp / 1000) + 1);
    $nuevoNivel = max($nuevoNivel, $nivelActual);

    $stmtUsuario = $pdo->prepare("
        UPDATE usuarios
        SET xp_total = :xp, nivel_actual = :nivel, updated_at = NOW(), ultima_sesion_at = NOW()
        WHERE id = :uid
    ");
    $stmtUsuario->execute([
        "xp"    => $nuevoXp,
        "nivel" => $nuevoNivel,
        "uid"   => $usuarioId
    ]);

    // Al llegar a 250 XP o subir de nivel, actualizar rutina y planificación.
    $semanaRespuesta = $semanaPlan;
    $xpSemanaRespuesta = $xpSemanaNuevo;
    $estadoSemanaRespuesta = 'activa';
    $planificacionRespuesta = $planificacionId;
    $subioDeNivel = ($nuevoNivel > $nivelActual);

    require_once __DIR__ . "/motor_rutina.php";

    if ($subioDeNivel) {
        // El usuario subió de nivel:
        // Regenerar inmediatamente la rutina con los nuevos ejercicios y objetivos del nuevo nivel!
        $resSinc = sincronizarRutinaYNivel($pdo, $usuarioId, $nuevoNivel);
        $planificacionRespuesta = $resSinc["planificacion_id"];
        $semanaRespuesta = 1;
        $xpSemanaRespuesta = 0;
        $estadoSemanaRespuesta = 'activa';
    } elseif ($semanaCompletada) {
        $stmtCompletarPlan = $pdo->prepare("
            UPDATE planificaciones_semanales
            SET estado = 'completada'
            WHERE id = :id
        ");
        $stmtCompletarPlan->execute(["id" => $planificacionId]);
        $estadoSemanaRespuesta = 'completada';

        $siguienteNivel = $nivelPlan;
        $siguienteSemana = $semanaPlan + 1;
        if ($semanaPlan >= 4) {
            $siguienteNivel = min(10, $nivelPlan + 1);
            $siguienteSemana = 1;
        }

        if ($siguienteNivel > $nivelPlan) {
            // Completó las 4 semanas y pasa al siguiente nivel:
            $nuevoNivel = $siguienteNivel;
            $stmtUpUser = $pdo->prepare("UPDATE usuarios SET nivel_actual = :niv WHERE id = :uid");
            $stmtUpUser->execute(["niv" => $nuevoNivel, "uid" => $usuarioId]);

            $resSinc = sincronizarRutinaYNivel($pdo, $usuarioId, $nuevoNivel);
            $planificacionRespuesta = $resSinc["planificacion_id"];
            $semanaRespuesta = 1;
            $xpSemanaRespuesta = 0;
            $estadoSemanaRespuesta = 'activa';
            $subioDeNivel = true;
        } elseif ($semanaPlan < 4) {
            // Siguiente semana dentro del mismo nivel:
            $tipoDB = $planActual["tipo_entrenamiento"] ?? 'FULL_BODY';

            $stmtSiguientePlan = $pdo->prepare("
                INSERT INTO planificaciones_semanales
                    (id, usuario_id, nivel, semana, dias_entrenamiento, tipo_entrenamiento, estado, created_at)
                VALUES
                    (gen_random_uuid(), :uid, :nivel, :semana, :dias, :tipo, 'activa', NOW())
                ON CONFLICT (usuario_id, nivel, semana)
                DO UPDATE SET
                    dias_entrenamiento = EXCLUDED.dias_entrenamiento,
                    tipo_entrenamiento = EXCLUDED.tipo_entrenamiento,
                    estado = 'activa',
                    created_at = NOW()
                RETURNING id
            ");
            $stmtSiguientePlan->execute([
                "uid" => $usuarioId,
                "nivel" => $nivelPlan,
                "semana" => $siguienteSemana,
                "dias" => $diasPlan,
                "tipo" => $tipoDB
            ]);
            $planificacionRespuesta = $stmtSiguientePlan->fetchColumn();
            $semanaRespuesta = $siguienteSemana;
            $xpSemanaRespuesta = 0;
            $estadoSemanaRespuesta = 'activa';

            // Copiar los ejercicios planificados para la nueva semana con estado pendiente
            $stmtCopiarEjercicios = $pdo->prepare("
                INSERT INTO ejercicios_planificados
                    (id, planificacion_id, ejercicio_id, numero_dia, orden,
                     series_objetivo, repeticiones_objetivo, xp_total, xp_series,
                     xp_repeticiones, estado, nivel_id)
                SELECT gen_random_uuid(), :nuevo_plan, ejercicio_id, numero_dia, orden,
                       series_objetivo, repeticiones_objetivo, xp_total, xp_series,
                       xp_repeticiones, 'pendiente', nivel_id
                FROM ejercicios_planificados
                WHERE planificacion_id = :plan_anterior
                ON CONFLICT (planificacion_id, numero_dia, orden)
                DO UPDATE SET
                    ejercicio_id = EXCLUDED.ejercicio_id,
                    series_objetivo = EXCLUDED.series_objetivo,
                    repeticiones_objetivo = EXCLUDED.repeticiones_objetivo,
                    xp_total = EXCLUDED.xp_total,
                    xp_series = EXCLUDED.xp_series,
                    xp_repeticiones = EXCLUDED.xp_repeticiones,
                    estado = 'pendiente',
                    nivel_id = EXCLUDED.nivel_id
            ");
            $stmtCopiarEjercicios->execute([
                "nuevo_plan" => $planificacionRespuesta,
                "plan_anterior" => $planificacionId
            ]);
        }
    }

    // Si subió de nivel, registrar en xp_historial con 'nivel_completado'
    if ($nuevoNivel > $nivelActual) {
        $stmtHistorialNivel = $pdo->prepare("
            INSERT INTO xp_historial (id, usuario_id, xp_ganado, motivo, created_at)
            VALUES (gen_random_uuid(), :uid, 0, 'nivel_completado', NOW())
        ");
        $stmtHistorialNivel->execute(["uid" => $usuarioId]);
    }

    if ($semanaCompletada) {
        $stmtHistorialSemana = $pdo->prepare("
            INSERT INTO xp_historial (id, usuario_id, xp_ganado, motivo, created_at)
            VALUES (gen_random_uuid(), :uid, 0, 'semana_completada', NOW())
        ");
        $stmtHistorialSemana->execute(["uid" => $usuarioId]);
    }

    $pdo->commit();

    responder(200, [
        "exito"   => true,
        "mensaje" => "¡Sesión registrada con éxito!",
        "datos"   => [
            "sesion_id"      => $sesionId,
            "xp_ganado"      => $xpGanadoAhora,
            "xp_total_nuevo" => $nuevoXp,
            "nivel_anterior" => $nivelActual,
            "nivel_nuevo"    => $nuevoNivel,
            "subio_de_nivel" => $nuevoNivel > $nivelActual,
            "planificacion_id" => $planificacionRespuesta,
            "semana_actual" => $semanaRespuesta,
            "xp_semana" => $xpSemanaRespuesta,
            "objetivo_xp_semanal" => 250,
            "estado_semana" => $estadoSemanaRespuesta,
            "semana_completada" => $semanaCompletada
        ]
    ]);

} catch (Throwable $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("[registrar_sesion] " . $e->getMessage());
    responder(500, [
        "exito"   => false,
        "mensaje" => "Error al guardar en la base de datos: " . $e->getMessage()
    ]);
}
