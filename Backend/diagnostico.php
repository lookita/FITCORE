<?php
declare(strict_types=1);

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=utf-8");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") { exit; }

require_once __DIR__ . "/db.php";

// Leer token
$cabeceras = function_exists("getallheaders") ? getallheaders() : [];
$autorizacion = $_SERVER["HTTP_AUTHORIZATION"]
    ?? $cabeceras["Authorization"]
    ?? $cabeceras["authorization"]
    ?? "";

if (!preg_match('/^Bearer\s+(.+)$/i', $autorizacion, $m)) {
    http_response_code(401);
    echo json_encode(["error" => "Sin token"]);
    exit;
}

try {
    $pdo = ConexionBaseDeDatos();
    $tokenHash = hash("sha256", trim($m[1]));

    // ── 0. CHECK CONSTRAINTS ──────────────────────────────────────────────
    $stmt = $pdo->query("
        SELECT conname, pg_get_constraintdef(oid) AS definicion
        FROM pg_constraint
        WHERE conrelid IN (
            'planificaciones_semanales'::regclass,
            'ejercicios_planificados'::regclass,
            'sesiones_entrenamiento'::regclass,
            'usuario_configuracion'::regclass,
            'xp_historial'::regclass
        )
    ");
    $constraints = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt = $pdo->prepare("
        SELECT u.id, u.nombre, u.email, u.xp_total, u.nivel_actual,
               s.expira_en, s.created_at AS sesion_creada
        FROM sesiones_auth s
        JOIN usuarios u ON u.id = s.usuario_id
        WHERE s.token_hash = :tk
          AND s.expira_en > NOW()
          AND s.revocado_at IS NULL
        LIMIT 1
    ");
    $stmt->execute(["tk" => $tokenHash]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        http_response_code(401);
        echo json_encode(["error" => "Token inválido o expirado"]);
        exit;
    }

    $uid = $usuario["id"];

    // ── 2. RUTINAS DEL USUARIO ─────────────────────────────────────────────
    $stmt = $pdo->prepare("
        SELECT id, nombre, descripcion, nivel_requerido, created_at
        FROM rutinas
        WHERE usuario_id = :uid
        ORDER BY created_at DESC
    ");
    $stmt->execute(["uid" => $uid]);
    $rutinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ── 3. DÍAS CONFIGURADOS ───────────────────────────────────────────────
    $stmt = $pdo->prepare("
        SELECT ds.id, ds.dia_semana, ds.rutina_id, r.nombre AS rutina_nombre
        FROM dia_semanal ds
        JOIN rutinas r ON r.id = ds.rutina_id
        WHERE ds.usuario_id = :uid
        ORDER BY ds.dia_semana
    ");
    $stmt->execute(["uid" => $uid]);
    $dias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ── 4. EJERCICIOS ASIGNADOS A CADA RUTINA ─────────────────────────────
    $ejerciciosPorRutina = [];
    foreach ($rutinas as $r) {
        $stmt = $pdo->prepare("
            SELECT re.id, re.orden, re.series_objetivo, re.reps_objetivo, re.descanso_segundos,
                   e.nombre AS ejercicio_nombre, e.categoria, e.dificultad
            FROM rutina_ejercicios re
            JOIN ejercicios e ON e.id = re.ejercicio_id
            WHERE re.rutina_id = :rid
            ORDER BY re.orden
        ");
        $stmt->execute(["rid" => $r["id"]]);
        $ejerciciosPorRutina[$r["id"]] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── 5. SESIONES DE ENTRENAMIENTO COMPLETADAS ───────────────────────────
    // Columnas reales: id, usuario_id, fecha, duracion_segundos, estado,
    //                  xp_otorgado, notas, planificacion_id, numero_dia
    $stmt = $pdo->prepare("
        SELECT se.id, se.fecha, se.duracion_segundos, se.estado,
               se.xp_otorgado, se.numero_dia, se.notas
        FROM sesiones_entrenamiento se
        WHERE se.usuario_id = :uid
        ORDER BY se.fecha DESC
        LIMIT 20
    ");
    $stmt->execute(["uid" => $uid]);
    $sesionesCompletadas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ── 6. REGISTRO DE SERIES (últimas 50) ────────────────────────────────
    // Columnas reales: id, sesion_id, numero_serie, peso_kg, repeticiones, completada
    $stmt = $pdo->prepare("
        SELECT sr.id, sr.sesion_id, sr.numero_serie,
               sr.peso_kg, sr.repeticiones, sr.completada
        FROM series_registro sr
        WHERE sr.sesion_id IN (
            SELECT id FROM sesiones_entrenamiento WHERE usuario_id = :uid
        )
        ORDER BY sr.created_at DESC
        LIMIT 50
    ");
    $stmt->execute(["uid" => $uid]);
    $seriesRegistro = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ── 7. HISTORIAL XP ───────────────────────────────────────────────────
    $xpHistorial = [];
    try {
        $stmt = $pdo->prepare("
            SELECT xh.id, xh.xp_ganado, xh.motivo, xh.created_at
            FROM xp_historial xh
            WHERE xh.usuario_id = :uid
            ORDER BY xh.created_at DESC
            LIMIT 30
        ");
        $stmt->execute(["uid" => $uid]);
        $xpHistorial = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        $xpHistorial = ["nota" => "Tabla xp_historial no encontrada o vacía: " . $e->getMessage()];
    }

    // ── 8. TODOS LOS EJERCICIOS DISPONIBLES EN LA DB ──────────────────────
    try {
        $stmt = $pdo->prepare("
            SELECT id, nombre, categoria, dificultad, activo
            FROM ejercicios
            ORDER BY dificultad, categoria, nombre
        ");
        $stmt->execute();
        $todosEjercicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        $todosEjercicios = ["error" => $e->getMessage()];
    }

    // ── 9. NIVELES CONFIGURADOS ────────────────────────────────────────────
    $stmt = $pdo->prepare("SELECT * FROM niveles ORDER BY numero_nivel");
    $stmt->execute();
    $niveles = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ── 10. CÁLCULO XP SEGÚN REGLAS DE NEGOCIO ────────────────────────────
    $cantidadDias     = count($dias);
    $nivelUsuario     = (int) $usuario["nivel_actual"];
    // ejercicios por día según nivel (Nivel 1=4, Nivel 2=5, etc.)
    $ejerciciosPorDia = 3 + $nivelUsuario; // Nivel1→4, Nivel2→5, Nivel3→6…
    $xpPorDia         = $cantidadDias > 0 ? round(250 / $cantidadDias, 4) : 0;
    $xpPorEjercicio   = $ejerciciosPorDia > 0 ? round($xpPorDia / $ejerciciosPorDia, 4) : 0;
    $xpPorSeries      = round($xpPorEjercicio / 2, 4);
    $xpPorReps        = round($xpPorEjercicio / 2, 4);

    // ── DIAGNÓSTICO COMPLETO ───────────────────────────────────────────────
    echo json_encode([
        "diagnostico" => "FITCORE - Estado completo del sistema",
        "timestamp"   => date("Y-m-d H:i:s"),

        "usuario" => $usuario,

        "resumen_xp" => [
            "nivel_actual"       => $nivelUsuario,
            "dias_semana"        => $cantidadDias,
            "ejercicios_por_dia" => $ejerciciosPorDia,
            "xp_por_dia"         => $xpPorDia,
            "xp_por_ejercicio"   => $xpPorEjercicio,
            "xp_por_series_50"   => $xpPorSeries,
            "xp_por_reps_50"     => $xpPorReps,
            "total_semana"       => 250,
            "total_nivel"        => 1000,
        ],

        "rutinas"             => $rutinas,
        "dias_configurados"   => $dias,
        "ejercicios_rutinas"  => $ejerciciosPorRutina,

        "sesiones_completadas" => $sesionesCompletadas,
        "series_registro"      => $seriesRegistro,
        "xp_historial"         => $xpHistorial,
        "check_constraints"    => $constraints,

        "todos_ejercicios_db" => $todosEjercicios,
        "niveles_db"          => $niveles,
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_INVALID_UTF8_SUBSTITUTE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}

