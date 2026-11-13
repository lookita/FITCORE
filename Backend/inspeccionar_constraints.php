<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

try {
    $pdo = ConexionBaseDeDatos();
    $stmt = $pdo->query("
        SELECT 
            tc.table_name,
            tc.constraint_name,
            cc.check_clause
        FROM information_schema.table_constraints tc
        JOIN information_schema.check_constraints cc 
            ON tc.constraint_name = cc.constraint_name
        WHERE tc.table_name IN ('planificaciones_semanales', 'ejercicios_planificados', 'sesiones_entrenamiento', 'series_registro', 'xp_historial')
    ");
    $checkConstraints = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt2 = $pdo->query("
        SELECT conname, conrelid::regclass::text as table_name, pg_get_constraintdef(oid) as definicion
        FROM pg_constraint
        WHERE conrelid IN (
            'planificaciones_semanales'::regclass,
            'ejercicios_planificados'::regclass,
            'sesiones_entrenamiento'::regclass,
            'series_registro'::regclass,
            'xp_historial'::regclass
        )
    ");
    $pgConstraints = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    $resultado = [
        "check_constraints" => $checkConstraints,
        "pg_constraints" => $pgConstraints
    ];

    file_put_contents(__DIR__ . '/constraints_dump.json', json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    header("Content-Type: application/json");
    echo json_encode($resultado, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    file_put_contents(__DIR__ . '/constraints_dump.json', json_encode(["error" => $e->getMessage()]));
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}

