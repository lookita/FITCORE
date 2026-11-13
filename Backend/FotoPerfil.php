<?php
declare(strict_types=1);

// ── CORS ──
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=utf-8");

ob_start();

register_shutdown_function(static function (): void {
    $error = error_get_last();

    if ($error === null || !in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        return;
    }

    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    http_response_code(500);

    echo json_encode([
        "exito" => false,
        "mensaje" => "Error fatal de PHP: " . $error['message']
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
});

// Preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Método no permitido"
    ]);
    exit;
}

require_once __DIR__ . '/db.php';

$imagen = json_decode(file_get_contents('php://input'), true);

// ── Validar los datos del input ──

if (
    !is_array($input) ||
    empty($input['email']) ||
    empty($input['password']) ||
    !is_string($input['email']) ||
    !is_string($input['password'])
) {
    http_response_code(400);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Email y contraseña son requeridos"
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}


try {
    $pdo = ConexionBaseDeDatos();
}