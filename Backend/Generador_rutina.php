<?php
declare(strict_types=1);

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
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
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    responder(405, ["exito" => false, "mensaje" => "Método no permitido"]);
}

// 1. Leer los datos enviados desde el frontend
$entrada = json_decode(file_get_contents("php://input"), true);
$categoria = is_array($entrada) && is_string($entrada["categoria"] ?? null)
    ? trim($entrada["categoria"])
    : "";
$dias = is_array($entrada) ? ($entrada["dias_semana"] ?? null) : null;

// Validar que se hayan enviado datos básicos (1 a 6 días por semana)
if ($categoria === "" || mb_strlen($categoria) > 60 || !is_array($dias) || count($dias) === 0 || count($dias) > 6) {
    responder(400, ["exito" => false, "mensaje" => "Debes seleccionar entre 1 y 6 días para entrenar."]);
}

// Validar que cada día sea un número entre 1 (Lunes) y 7 (Domingo)
foreach ($dias as $dia) {
    if (!is_int($dia) || $dia < 1 || $dia > 7) {
        responder(400, ["exito" => false, "mensaje" => "Los días deben estar entre 1 y 7."]);
    }
}

// Limpiar días duplicados y ordenarlos
$dias = array_values(array_unique($dias));
sort($dias);
$cantidadDias = count($dias);

if ($cantidadDias > 6) {
    responder(400, ["exito" => false, "mensaje" => "El usuario puede elegir como máximo 6 días por semana."]);
}

// 2. Verificar la sesión del usuario con el token
require_once __DIR__ . "/db.php";
require_once __DIR__ . "/motor_rutina.php";

$cabeceras = function_exists("getallheaders") ? getallheaders() : [];
$autorizacion = $_SERVER["HTTP_AUTHORIZATION"]
    ?? $cabeceras["Authorization"]
    ?? $cabeceras["authorization"]
    ?? "";

if (!preg_match('/^Bearer\s+(.+)$/i', $autorizacion, $coincidencias)) {
    responder(401, ["exito" => false, "mensaje" => "Inicia sesión para continuar."]);
}

try {
    $pdo = ConexionBaseDeDatos();

    // Obtener el ID y nivel actual del usuario
    $stmt = $pdo->prepare("
        SELECT u.id, n.numero_nivel AS nivel_actual
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
        responder(401, ["exito" => false, "mensaje" => "La sesión no es válida o ha expirado."]);
    }

    $nivel = (int) $usuario["nivel_actual"];

    // Sincronizar rutina y nivel con motor_rutina
    $resultado = sincronizarRutinaYNivel($pdo, $usuario["id"], $nivel, $categoria, $dias);

    // Consultar los ejercicios completos con sus datos enriquecidos para la respuesta
    $stmt = $pdo->prepare("
        SELECT e.id, e.nombre, e.imagen_url, e.descripcion, re.series_objetivo, re.reps_objetivo
        FROM rutina_ejercicios re
        JOIN ejercicios e ON e.id = re.ejercicio_id
        WHERE re.rutina_id = :rutina
        ORDER BY re.orden
    ");
    $stmt->execute(["rutina" => $resultado["rutina_id"]]);
    $ejerciciosRespuesta = $stmt->fetchAll(PDO::FETCH_ASSOC);

    responder(201, [
        "exito" => true,
        "mensaje" => "¡Rutina creada con éxito!",
        "datos" => [
            "id" => $resultado["rutina_id"],
            "nombre" => "Rutina " . $resultado["categoria"] . " - Nivel " . $nivel,
            "categoria" => $resultado["categoria"],
            "nivel_actual" => $nivel,
            "dias_semana" => $resultado["dias_semana"],
            "ejercicios" => $ejerciciosRespuesta,
            "planificacion_id" => $resultado["planificacion_id"],
            "semana_actual" => 1,
            "xp_semana" => 0,
            "objetivo_xp_semanal" => 250,
            "estado_semana" => "activa"
        ]
    ]);

} catch (Throwable $e) {
    error_log("[generador_rutina] " . $e->getMessage());
    responder(500, ["exito" => false, "mensaje" => "Ocurrió un error al crear la rutina: " . $e->getMessage()]);
}
