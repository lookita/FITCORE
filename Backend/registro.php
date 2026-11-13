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
        "exito"   => false,
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
    echo json_encode(["exito" => false, "mensaje" => "Método no permitido"]);
    exit;
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/roles.php';

$input = json_decode(file_get_contents('php://input'), true);

// ── Validar campos ──
if (empty($input['nombre']) || empty($input['email']) || empty($input['password'])) {
    http_response_code(400);
    echo json_encode([
        "exito"   => false,
        "mensaje" => "Nombre, email y contraseña son requeridos"
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

try {
    $pdo = ConexionBaseDeDatos();

    // ── Verificar duplicado ──
    $stmt = $pdo->prepare("SELECT id
                             FROM usuarios 
                             WHERE email = :email 
                             LIMIT 1");
                             
    $stmt->execute(['email' => $input['email']]);

    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode([
            "exito"   => false,
            "mensaje" => "El email ya está registrado"
        ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
        exit;
    }

    // ── Insertar usuario ──
    $hash = password_hash($input['password'], PASSWORD_BCRYPT);
    $stmt = $pdo->prepare(
        "INSERT INTO usuarios (nombre, email, password_hash, rol,nivel_actual, xp_total)
         VALUES (:nombre, :email, :password_hash, :rol, :nivel_actual, :xp_total)
         RETURNING id"
    );
    $stmt->execute([
        'nombre'        => $input['nombre'],
        'email'         => $input['email'],
        'password_hash' => $hash,
        'rol'           => ROL_POR_DEFECTO,
        'nivel_actual'  => 1,
        'xp_total'     => 0
    ]);

    $nuevoId = $stmt->fetchColumn();

    http_response_code(200);
    echo json_encode([
        "exito"   => true,
        "mensaje" => "Usuario registrado correctamente",
        "datos"   => [
            "id"     => (int) $nuevoId,
            "nombre" => $input['nombre'],
            "email"  => $input['email']
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "exito"   => false,
        "mensaje" => "Error al registrar en la base de datos: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
}
