<?php
declare(strict_types=1);

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json; charset=utf-8");

function responder(int $codigo, array $respuesta): void
{
    http_response_code($codigo);
    echo json_encode($respuesta, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    exit;
}
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    responder(405, ["exito" => false, "mensaje" => "Método no permitido"]);
}

require_once __DIR__ . "/db.php";

// Leer el token de autorización
$cabeceras = function_exists("getallheaders") ? getallheaders() : [];
$autorizacion = $_SERVER["HTTP_AUTHORIZATION"]
    ?? $cabeceras["Authorization"]
    ?? $cabeceras["authorization"]
    ?? "";

if (!preg_match('/^Bearer\s+(.+)$/i', $autorizacion, $coincidencias)) {
    responder(401, ["exito" => false, "mensaje" => "Inicia sesión para continuar"]);
}

try {
    $pdo = ConexionBaseDeDatos();

    // 1. Buscar el usuario y su nivel actual
    $stmt = $pdo->prepare("
        SELECT u.id AS usuario_id, n.numero_nivel
        FROM sesiones_auth s
        JOIN usuarios u ON u.id = s.usuario_id
        JOIN niveles n ON n.numero_nivel = u.nivel_actual
        WHERE s.token_hash = :token
          AND s.expira_en > NOW()
          AND s.revocado_at IS NULL
        LIMIT 1
    ");
    $stmt->execute(["token" => hash("sha256", trim($coincidencias[1]))]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        responder(401, ["exito" => false, "mensaje" => "La sesión no es válida"]);
    }

    $usuarioId = $usuario["usuario_id"];
    $nivel = (int) $usuario["numero_nivel"];

    // 2. Comprobar si el usuario ya tiene una rutina creada (persistencia)
    $rutinaExistente = null;
    $diasConfigurados = [];
    $ejerciciosConfigurados = [];
    $tieneRutina = false;
    $rutinaActual = null;
    $progresoSemanal = null;

    try {
        $stmtRutina = $pdo->prepare("
            SELECT r.id, r.nombre, r.descripcion, r.nivel_requerido
            FROM rutinas r
            WHERE r.usuario_id = :usuario_id
            LIMIT 1
        ");
        $stmtRutina->execute(["usuario_id" => $usuarioId]);
        $rutinaExistente = $stmtRutina->fetch(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        $rutinaExistente = null;
    }

    try {
        $stmtDias = $pdo->prepare("
            SELECT dia_semana 
            FROM dia_semanal 
            WHERE usuario_id = :usuario_id
            ORDER BY dia_semana
        ");
        $stmtDias->execute(["usuario_id" => $usuarioId]);
        $diasConfigurados = array_map("intval", $stmtDias->fetchAll(PDO::FETCH_COLUMN));
    } catch (Throwable $e) {
        $diasConfigurados = [];
    }

    $tieneRutina = ($rutinaExistente !== false && !empty($rutinaExistente)) || (count($diasConfigurados) > 0);

    if ($tieneRutina && $rutinaExistente) {
        // Verificar si el nivel del usuario cambió con respecto al de la rutina existente
        $nivelRutina = (int) ($rutinaExistente["nivel_requerido"] ?? 1);
        if ($nivelRutina !== $nivel) {
            require_once __DIR__ . "/motor_rutina.php";
            sincronizarRutinaYNivel($pdo, $usuarioId, $nivel);
            // Re-consultar la rutina con su nuevo nivel y nombre
            $stmtRutina->execute(["usuario_id" => $usuarioId]);
            $rutinaExistente = $stmtRutina->fetch(PDO::FETCH_ASSOC);
        }

        try {
            $stmtPlan = $pdo->prepare("
                SELECT p.id, p.nivel, p.semana, p.estado,
                       COALESCE(SUM(se.xp_otorgado), 0) AS xp_semana
                FROM planificaciones_semanales p
                LEFT JOIN sesiones_entrenamiento se
                    ON se.planificacion_id = p.id
                WHERE p.usuario_id = :usuario_id
                GROUP BY p.id
                ORDER BY CASE WHEN p.estado = 'activa' THEN 0 ELSE 1 END,
                         p.nivel DESC, p.semana DESC, p.created_at DESC
                LIMIT 1
            ");
            $stmtPlan->execute(["usuario_id" => $usuarioId]);
            $plan = $stmtPlan->fetch(PDO::FETCH_ASSOC);
            if ($plan) {
                $progresoSemanal = [
                    "planificacion_id" => $plan["id"],
                    "nivel" => (int) $plan["nivel"],
                    "semana" => (int) $plan["semana"],
                    "xp" => min(250, (int) $plan["xp_semana"]),
                    "objetivo_xp" => 250,
                    "estado" => $plan["estado"]
                ];
            }
        } catch (Throwable $e) {
            $progresoSemanal = null;
        }

        try {
            $stmtEjercicios = $pdo->prepare("
                SELECT e.id, e.nombre, e.imagen_url, e.descripcion, re.series_objetivo, re.reps_objetivo
                FROM rutina_ejercicios re
                JOIN ejercicios e 
                ON e.id = re.ejercicio_id
                WHERE re.rutina_id = :rutina_id
                ORDER BY re.orden
            ");
            $stmtEjercicios->execute(["rutina_id" => $rutinaExistente["id"]]);
            $ejerciciosConfigurados = $stmtEjercicios->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $ejerciciosConfigurados = [];
        }

        $diasCompletados = [];
        try {
            if ($progresoSemanal) {
                $stmtSesCompletadas = $pdo->prepare("
                    SELECT DISTINCT numero_dia
                    FROM sesiones_entrenamiento
                    WHERE planificacion_id = :plan_id AND estado = 'completada'
                ");
                $stmtSesCompletadas->execute(["plan_id" => $progresoSemanal["planificacion_id"]]);
                $diasCompletados = array_map("intval", $stmtSesCompletadas->fetchAll(PDO::FETCH_COLUMN));
            }
        } catch (Throwable $e) {
            $diasCompletados = [];
        }

        $rutinaActual = [
            "id" => $rutinaExistente["id"],
            "nombre" => $rutinaExistente["nombre"],
            "categoria" => $rutinaExistente["nombre"],
            "nivel_actual" => (int) $rutinaExistente["nivel_requerido"],
            "dias_semana" => $diasConfigurados,
            "dias_completados" => $diasCompletados,
            "ejercicios" => $ejerciciosConfigurados,
            "planificacion_id" => $progresoSemanal["planificacion_id"] ?? null,
            "semana_actual" => $progresoSemanal["semana"] ?? 1,
            "xp_semana" => $progresoSemanal["xp"] ?? 0,
            "objetivo_xp_semanal" => 250,
            "estado_semana" => $progresoSemanal["estado"] ?? "activa"
        ];
    }

    // 3. Traer las categorías existentes en la base de datos
    $stmt = $pdo->prepare("
        SELECT DISTINCT categoria
        FROM ejercicios
        WHERE activo IS TRUE
          AND dificultad = :nivel
          AND categoria IS NOT NULL
          AND BTRIM(categoria) <> ''
        ORDER BY categoria
    ");
    $stmt->execute(["nivel" => $nivel]);
    $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Categorías del generador dinámico FITCORE según la frecuencia de entrenamiento
    $categorias = [
        "Full Body",               // 1 a 3 días
        "Upper / Lower",           // 4 días
        "Push / Pull / Legs",      // 5 días
        "Push / Pull / Legs × 2"   // 6 días
    ];

    responder(200, [
        "exito" => true,
        "mensaje" => "Catálogo cargado",
        "datos" => [
            "nivel_actual" => $nivel,
            "categorias" => $categorias,
            "tiene_rutina" => $tieneRutina,
            "rutina_actual" => $rutinaActual
        ]
    ]);
} catch (Throwable $e) {
    error_log("[catalogo_rutina] " . $e->getMessage());
    responder(500, ["exito" => false, "mensaje" => "No se pudo cargar el catálogo"]);
}
