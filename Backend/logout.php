<?php
declare(strict_types=1);

// ==========================================
// 1. CONFIGURACIÓN CORS
// ==========================================

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

// ==========================================
// 2. PRE-FLIGHT Y MÉTODO POST
// ==========================================

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

// ==========================================
// 3. CONEXIÓN A LA BASE DE DATOS
// ==========================================

require_once __DIR__ . '/db.php';

// ==========================================
// 4. OBTENER Y VALIDAR EL TOKEN
// ==========================================

$token = '';

$headers = function_exists('getallheaders') ? getallheaders() : [];
$autorizacion = $_SERVER['HTTP_AUTHORIZATION']
    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
    ?? $headers['Authorization']
    ?? $headers['authorization']
    ?? '';

if (preg_match('/^Bearer\s+(.+)$/i', $autorizacion, $coincidencias)) {
    $token = trim($coincidencias[1]);
}

if ($token === '') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (is_array($input) && !empty($input['token']) && is_string($input['token'])) {
        $token = trim($input['token']);
    }
}

if ($token !== '') {
    $token_hash = hash('sha256', $token);

    try {
        $pdo = ConexionBaseDeDatos();
        $stmt = $pdo->prepare("
            UPDATE sesiones_auth
            SET revocado_at = NOW()
            WHERE token_hash = :token_hash
              AND revocado_at IS NULL
        ");
        $stmt->execute([':token_hash' => $token_hash]);
    } catch (Throwable $e) {
        // Log interno del error si fuera necesario
        error_log("[logout_error] " . $e->getMessage());
    }
}

http_response_code(200);
echo json_encode([
    "exito"   => true,
    "mensaje" => "Sesión cerrada correctamente"
], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);

