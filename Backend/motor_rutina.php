<?php
declare(strict_types=1);


function obtenerProgresionNivel(int $nivel): array
{
    $progresiones = [
        1  => ["series" => 6, "reps" => 3],
        2  => ["series" => 6, "reps" => 4],
        3  => ["series" => 5, "reps" => 5],
        4  => ["series" => 5, "reps" => 6],
        5  => ["series" => 5, "reps" => 8],
        6  => ["series" => 4, "reps" => 8],
        7  => ["series" => 4, "reps" => 10],
        8  => ["series" => 4, "reps" => 10],
        9  => ["series" => 4, "reps" => 12],
        10 => ["series" => 4, "reps" => 12],
    ];

    return $progresiones[$nivel] ?? ["series" => 4, "reps" => 10];
}

function obtenerLimiteEjerciciosNivel(int $nivel): int
{
    // REGLA ESTRICTA FITCORE:
    // Nivel 1: 4 ejercicios
    // Nivel 2: 5 ejercicios
    // Nivel 3 en adelante: 6 ejercicios (TOPE MÁXIMO ABSOLUTO)
    $limites = [
        1  => 4,
        2  => 5,
        3  => 6,
        4  => 6,
        5  => 6,
        6  => 6,
        7  => 6,
        8  => 6,
        9  => 6,
        10 => 6,
    ];

    return min(6, $limites[$nivel] ?? 6);
}

function mapearSplitEntrenamiento(int $cantidadDias): array
{
    if ($cantidadDias <= 3) {
        return ["visual" => "Full Body", "db" => "FULL_BODY"];
    } elseif ($cantidadDias === 4) {
        return ["visual" => "Upper / Lower", "db" => "UPPER_LOWER"];
    } elseif ($cantidadDias === 5) {
        return ["visual" => "Push / Pull / Legs", "db" => "PPL"];
    } else {
        return ["visual" => "Push / Pull / Legs × 2", "db" => "PPL_X2"];
    }
}

function obtenerEjerciciosNivel(PDO $pdo, int $nivel, string $categoria): array
{
    $limite = obtenerLimiteEjerciciosNivel($nivel);

    // 1. Buscar ejercicios específicos del nivel
    $stmt = $pdo->prepare("
        SELECT id, nombre, categoria, imagen_url, descripcion, dificultad
        FROM ejercicios
        WHERE dificultad = :nivel AND activo IS TRUE
        ORDER BY nombre
    ");
    $stmt->execute(["nivel" => $nivel]);
    $ejerciciosNivel = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Si no hay suficientes ejercicios específicos del nivel, completar con los más cercanos
    if (count($ejerciciosNivel) < $limite) {
        $idsExistentes = array_column($ejerciciosNivel, 'id');
        $placeholders = count($idsExistentes) > 0 ? implode(',', array_fill(0, count($idsExistentes), '?')) : "''";

        $sqlFallback = "
            SELECT id, nombre, categoria, imagen_url, descripcion, dificultad
            FROM ejercicios
            WHERE activo IS TRUE
        ";
        if (count($idsExistentes) > 0) {
            $sqlFallback .= " AND id NOT IN ($placeholders)";
        }
        $sqlFallback .= " ORDER BY ABS(COALESCE(dificultad, 1) - ?), nombre LIMIT ?";

        $stmtFallback = $pdo->prepare($sqlFallback);
        $idx = 1;
        foreach ($idsExistentes as $id) {
            $stmtFallback->bindValue($idx++, $id);
        }
        $stmtFallback->bindValue($idx++, $nivel, PDO::PARAM_INT);
        $stmtFallback->bindValue($idx++, $limite - count($ejerciciosNivel), PDO::PARAM_INT);
        $stmtFallback->execute();
        $complemento = $stmtFallback->fetchAll(PDO::FETCH_ASSOC);

        $ejerciciosNivel = array_merge($ejerciciosNivel, $complemento);
    }

    // 3. Filtrar según la categoría / split si aplica
    $categoriaLower = strtolower($categoria);
    if (strpos($categoriaLower, 'upper') !== false || strpos($categoriaLower, 'lower') !== false) {
        $filtrados = array_filter($ejerciciosNivel, function ($e) {
            $c = strtolower($e['categoria'] ?? '');
            return strpos($c, 'push') !== false || strpos($c, 'pull') !== false
                || strpos($c, 'upper') !== false || strpos($c, 'lower') !== false
                || strpos($c, 'legs') !== false || strpos($c, 'core') !== false;
        });
        if (!empty($filtrados)) {
            $ejerciciosNivel = array_values($filtrados);
        }
    } elseif (strpos($categoriaLower, 'push') !== false || strpos($categoriaLower, 'ppl') !== false) {
        $filtrados = array_filter($ejerciciosNivel, function ($e) {
            $c = strtolower($e['categoria'] ?? '');
            return strpos($c, 'push') !== false || strpos($c, 'pull') !== false
                || strpos($c, 'legs') !== false || strpos($c, 'core') !== false;
        });
        if (!empty($filtrados)) {
            $ejerciciosNivel = array_values($filtrados);
        }
    }

    // Si después de filtrar quedaron menos del límite, volver a llenar
    if (count($ejerciciosNivel) < $limite) {
        $stmtTodos = $pdo->prepare("SELECT id, nombre, categoria, imagen_url, descripcion, dificultad FROM ejercicios WHERE activo IS TRUE ORDER BY nombre LIMIT :lim");
        $stmtTodos->bindValue(':lim', $limite, PDO::PARAM_INT);
        $stmtTodos->execute();
        $ejerciciosNivel = $stmtTodos->fetchAll(PDO::FETCH_ASSOC);
    }

    return array_slice($ejerciciosNivel, 0, $limite);
}

/**
 * Actualiza o regenera la rutina y los ejercicios planificados para el nivel actual del usuario.
 */
function sincronizarRutinaYNivel(
    PDO $pdo,
    string $usuarioId,
    int $nivel,
    ?string $categoriaForzada = null,
    ?array $diasForzados = null
): array {
    // 1. Obtener días configurados si no se pasaron
    if ($diasForzados === null || count($diasForzados) === 0) {
        $stmtDias = $pdo->prepare("SELECT dia_semana FROM dia_semanal WHERE usuario_id = :uid ORDER BY dia_semana");
        $stmtDias->execute(["uid" => $usuarioId]);
        $diasConfigurados = array_map("intval", $stmtDias->fetchAll(PDO::FETCH_COLUMN));
        if (count($diasConfigurados) === 0) {
            $diasConfigurados = [1, 3, 5]; // Lunes, Miércoles, Viernes por defecto
        }
    } else {
        $diasConfigurados = array_values(array_unique(array_map("intval", $diasForzados)));
        sort($diasConfigurados);
    }

    $cantidadDias = min(6, max(1, count($diasConfigurados)));
    $split = mapearSplitEntrenamiento($cantidadDias);
    $categoriaVisual = $categoriaForzada ?? $split["visual"];
    $categoriaDB = $split["db"];

    // 2. Progresión y límite del nivel
    $progresion = obtenerProgresionNivel($nivel);
    $seriesObj  = $progresion["series"];
    $repsObj    = $progresion["reps"];
    $ejercicios = obtenerEjerciciosNivel($pdo, $nivel, $categoriaVisual);

    // 3. Crear o actualizar en la tabla 'rutinas'
    $nombreRutina = "Rutina " . $categoriaVisual . " - Nivel " . $nivel;
    $descRutina   = "Rutina de " . $categoriaVisual . " para " . $cantidadDias . " días a la semana - Nivel " . $nivel;

    $stmtRutina = $pdo->prepare("SELECT id FROM rutinas WHERE usuario_id = :uid LIMIT 1");
    $stmtRutina->execute(["uid" => $usuarioId]);
    $rutinaId = $stmtRutina->fetchColumn();

    if ($rutinaId) {
        $stmtUpRutina = $pdo->prepare("
            UPDATE rutinas 
            SET nombre = :nom, descripcion = :descr, nivel_requerido = :niv
            WHERE id = :rid
        ");
        $stmtUpRutina->execute([
            "nom"   => $nombreRutina,
            "descr" => $descRutina,
            "niv"   => $nivel,
            "rid"   => $rutinaId
        ]);
    } else {
        $stmtInsRutina = $pdo->prepare("
            INSERT INTO rutinas (id, usuario_id, nombre, descripcion, nivel_requerido)
            VALUES (gen_random_uuid(), :uid, :nom, :descr, :niv)
            RETURNING id
        ");
        $stmtInsRutina->execute([
            "uid"   => $usuarioId,
            "nom"   => $nombreRutina,
            "descr" => $descRutina,
            "niv"   => $nivel
        ]);
        $rutinaId = $stmtInsRutina->fetchColumn();
    }

    // 4. Actualizar días en dia_semanal si vinieron forzados o si no existían
    if ($diasForzados !== null) {
        $pdo->prepare("DELETE FROM dia_semanal WHERE usuario_id = :uid")->execute(["uid" => $usuarioId]);
        $stmtInsDia = $pdo->prepare("INSERT INTO dia_semanal (id, usuario_id, dia_semana, rutina_id) VALUES (gen_random_uuid(), :uid, :dia, :rid)");
        foreach ($diasConfigurados as $diaNum) {
            $stmtInsDia->execute(["uid" => $usuarioId, "dia" => $diaNum, "rid" => $rutinaId]);
        }
    }

    // 5. Reemplazar los ejercicios en 'rutina_ejercicios' con los del nuevo nivel
    $pdo->prepare("DELETE FROM rutina_ejercicios WHERE rutina_id = :rid")->execute(["rid" => $rutinaId]);
    $stmtInsRe = $pdo->prepare("
        INSERT INTO rutina_ejercicios (id, rutina_id, ejercicio_id, orden, series_objetivo, reps_objetivo, descanso_segundos)
        VALUES (gen_random_uuid(), :rid, :eid, :ord, :ser, :rep, 60)
    ");
    foreach ($ejercicios as $idx => $ej) {
        $stmtInsRe->execute([
            "rid" => $rutinaId,
            "eid" => $ej["id"],
            "ord" => $idx + 1,
            "ser" => $seriesObj,
            "rep" => $repsObj
        ]);
    }

    // 6. Crear o actualizar la planificación activa en 'planificaciones_semanales'
    $stmtPlan = $pdo->prepare("
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
    $stmtPlan->execute([
        "uid"   => $usuarioId,
        "nivel" => $nivel,
        "dias"  => $cantidadDias,
        "tipo"  => $categoriaDB
    ]);
    $planId = $stmtPlan->fetchColumn();

    // 7. Reemplazar los ejercicios planificados pendientes en 'ejercicios_planificados'
    $pdo->prepare("DELETE FROM ejercicios_planificados WHERE planificacion_id = :pid AND estado = 'pendiente'")
        ->execute(["pid" => $planId]);

    $cantEjercicios = count($ejercicios);
    $xpPorDia = $cantidadDias > 0 ? 250 / $cantidadDias : 0;
    $xpTotalPorEj = $cantEjercicios > 0 ? $xpPorDia / $cantEjercicios : 0;
    $xpTotRedondeado = round($xpTotalPorEj, 4);
    $xpSerRedondeado = round($xpTotRedondeado / 2, 4);
    $xpRepRedondeado = round($xpTotRedondeado - $xpSerRedondeado, 4);

    $stmtEjPlan = $pdo->prepare("
        INSERT INTO ejercicios_planificados
            (id, planificacion_id, ejercicio_id, numero_dia, orden, series_objetivo, repeticiones_objetivo, xp_total, xp_series, xp_repeticiones, estado)
        VALUES
            (gen_random_uuid(), :plan_id, :ej_id, :num_dia, :orden, :series, :reps, :xp_tot, :xp_ser, :xp_rep, 'pendiente')
        ON CONFLICT (planificacion_id, numero_dia, orden)
        DO UPDATE SET
            ejercicio_id = EXCLUDED.ejercicio_id,
            series_objetivo = EXCLUDED.series_objetivo,
            repeticiones_objetivo = EXCLUDED.repeticiones_objetivo,
            xp_total = EXCLUDED.xp_total,
            xp_series = EXCLUDED.xp_series,
            xp_repeticiones = EXCLUDED.xp_repeticiones
    ");

    foreach ($diasConfigurados as $indiceDia => $_diaOriginal) {
        $diaValido = $indiceDia + 1;
        foreach ($ejercicios as $idxEj => $ejercicio) {
            $stmtEjPlan->execute([
                "plan_id"  => $planId,
                "ej_id"    => $ejercicio["id"],
                "num_dia"  => $diaValido,
                "orden"    => $idxEj + 1,
                "series"   => $seriesObj,
                "reps"     => $repsObj,
                "xp_tot"   => $xpTotRedondeado,
                "xp_ser"   => $xpSerRedondeado,
                "xp_rep"   => $xpRepRedondeado
            ]);
        }
    }

    // 8. Sincronizar en usuario_configuracion
    try {
        $stmtCfg = $pdo->prepare("
            INSERT INTO usuario_configuracion (usuario_id, dias_entrenamiento, tipo_entrenamiento, created_at, updated_at)
            VALUES (:usuario, :dias, :tipo, NOW(), NOW())
            ON CONFLICT (usuario_id)
            DO UPDATE SET
                dias_entrenamiento = EXCLUDED.dias_entrenamiento,
                tipo_entrenamiento = EXCLUDED.tipo_entrenamiento,
                updated_at = NOW()
        ");
        $stmtCfg->execute([
            "usuario" => $usuarioId,
            "dias"    => $cantidadDias,
            "tipo"    => $categoriaDB
        ]);
    } catch (Throwable $eCfg) {
        error_log("[motor_rutina:usuario_configuracion] " . $eCfg->getMessage());
    }

    // 9. Devolver los ejercicios construidos con sus objetivos reales
    $ejerciciosConfigurados = [];
    foreach ($ejercicios as $idx => $ej) {
        $ejerciciosConfigurados[] = [
            "id"              => $ej["id"],
            "nombre"          => $ej["nombre"],
            "imagen_url"      => $ej["imagen_url"] ?? null,
            "descripcion"     => $ej["descripcion"] ?? null,
            "series_objetivo" => $seriesObj,
            "reps_objetivo"   => $repsObj,
            "orden"           => $idx + 1
        ];
    }

    return [
        "rutina_id"        => $rutinaId,
        "planificacion_id" => $planId,
        "nivel"            => $nivel,
        "categoria"        => $categoriaVisual,
        "series_objetivo"  => $seriesObj,
        "reps_objetivo"    => $repsObj,
        "cantidad_ejercicios" => count($ejercicios),
        "dias_semana"      => $diasConfigurados,
        "ejercicios"       => $ejerciciosConfigurados
    ];
}
