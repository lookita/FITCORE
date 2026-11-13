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
    echo json_encode([
        "exito" => false,
        "mensaje" => "Método no permitido"
    ]);
    exit;
}

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/roles.php';

$input = json_decode(file_get_contents('php://input'), true);

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
        "exito"   => false,
        "mensaje" => "Email y contraseña son requeridos"
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

// Buscar en la base de datos

try {
    $pdo = ConexionBaseDeDatos();

    // ── Buscar usuario en la base de datos ──
    $stmt = $pdo->prepare(
        "SELECT id, nombre, email, password_hash, rol, created_at, updated_at, xp_total, nivel_actual, ultima_sesion_at
         FROM usuarios
         WHERE email = :email
         LIMIT 1"
    );

    $stmt->execute(['email' => $input['email']]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "exito"   => false,
        "mensaje" => "Error al consultar la base de datos: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

// Verificar credenciales

if (!$usuario || !password_verify($input['password'], $usuario['password_hash'])) {
    http_response_code(401);
    echo json_encode([
        "exito"   => false,
        "mensaje" => "Credenciales inválidas"
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

// Verificar rol

if (!esRolPermitido((string) $usuario['rol'])) {
    http_response_code(403);
    echo json_encode([
        "exito"   => false,
        "mensaje" => "El usuario tiene un rol no permitido"
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

// Creo el token

// Configurando los token_hash
$caracteristicas_token = 32;

// Genera bytes criptográficamente seguros.
$byte_generados = random_bytes($caracteristicas_token);

// Convierte esos bytes en caracteres hexadecimales.
$bytes_hex = bin2hex($byte_generados);

// Contiene el token que después enviaremos al frontend.
$Token_final = $bytes_hex;

// Generar copia del token con hash().
$token_hash = hash('sha256', $Token_final);

// Registro la sesión

try {
    $stmt = $pdo->prepare(
        "INSERT INTO sesiones_auth (
            id,
            usuario_id,
            token_hash,
            expira_en,
            created_at,
            revocado_at
        )
        VALUES (
            gen_random_uuid(),
            :usuario_id,
            :token_hash,
            NOW() + INTERVAL '7 days',
            NOW(),
            NULL
        )
        RETURNING id"
    );

    $stmt->execute([
        'usuario_id' => $usuario['id'],
        'token_hash' => $token_hash
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Error al registrar la sesión"
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

// Eliminar password_hash
unset($usuario['password_hash']);

// Verificar si el usuario ya tiene una rutina guardada en la base de datos
$tieneRutina = false;
try {
    $stmtRutina = $pdo->prepare("SELECT id FROM rutinas WHERE usuario_id = :uid LIMIT 1");
    $stmtRutina->execute(['uid' => $usuario['id']]);
    $tieneRutina = (bool) $stmtRutina->fetch();
} catch (Throwable $e) {
    // Si la tabla aún no tiene datos o error leve, queda en false
    $tieneRutina = false;
}

// Enviando respuesta al frontend

http_response_code(200);

echo json_encode([
    "exito"   => true,
    "mensaje" => "Login exitoso",
    "datos"   => [
        "usuario" => [
            "id" => $usuario['id'],
            "nombre" => $usuario['nombre'],
            "email" => $usuario['email'],
            "xp_total" => (int) $usuario['xp_total'],
            "nivel_actual" => (int) $usuario['nivel_actual'],
            "tiene_rutina" => $tieneRutina
        ],
        "token" => $Token_final
    ]
], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
