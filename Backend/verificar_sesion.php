<?php
declare(strict_types=1);

// 1. CONFIGURACIÓN CORS

header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Método no permitido",
        "datos" => null
    ]);
    exit;
}



require_once __DIR__ . '/db.php';



$token = '';

// Intentar leer token del header Authorization
$headers = function_exists('getallheaders') ? getallheaders() : [];
$autorizacion = $_SERVER['HTTP_AUTHORIZATION']
    ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
    ?? $headers['Authorization']
    ?? $headers['authorization']
    ?? '';

if (preg_match('/^Bearer\s+(.+)$/i', $autorizacion, $coincidencias)) {
    $token = trim($coincidencias[1]);
}

// si no vino por header, intentar leerlo del cuerpo JSON
if ($token === '') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (is_array($input) && !empty($input['token']) && is_string($input['token'])) {
        $token = trim($input['token']);
    }
}

if ($token === '') {
    http_response_code(401);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Token no proporcionado",
        "datos" => null
    ]);
    exit;
}

// GENERAR HASH DEL TOKEN
$token_hash = hash('sha256', $token);



// BUSCAR Y VALIDAR LA SESIÓN
try {
    $pdo = ConexionBaseDeDatos();

    $sql = "
        SELECT
            u.id,
            u.nombre,
            u.email,
            u.rol,
            u.xp_total,
            u.nivel_actual
        FROM sesiones_auth s
        INNER JOIN usuarios u
            ON u.id = s.usuario_id
        WHERE s.token_hash = :token_hash
          AND s.expira_en > NOW()
          AND s.revocado_at IS NULL
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':token_hash' => $token_hash]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        http_response_code(401);
        echo json_encode([
            "exito" => false,
            "mensaje" => "La sesión no es válida o ha expirado",
            "datos" => null
        ]);
        exit;
    }


    

    // VERIFICAR SI EL USUARIO YA TIENE UNA RUTINA GUARDADA
    $tieneRutina = false;
    try {
        $stmtRutina = $pdo->prepare("
            SELECT id 
            FROM rutinas 
            WHERE usuario_id = :uid LIMIT 1"
        );
        $stmtRutina->execute([':uid' => $usuario['id']]);
        $tieneRutina = (bool) $stmtRutina->fetch();
    } catch (Throwable $e) {
        $tieneRutina = false;
    }

    // 7. DEVOLVER LOS DATOS DEL USUARIO
    http_response_code(200);
    echo json_encode([
        "exito" => true,
        "mensaje" => "Sesión validada correctamente",
        "datos" => [
            "usuario" => [
                "id"           => $usuario['id'],
                "nombre"       => $usuario['nombre'],
                "email"        => $usuario['email'],
                "rol"          => $usuario['rol'],
                "xp_total"     => (int) ($usuario['xp_total'] ?? 0),
                "nivel_actual" => (int) ($usuario['nivel_actual'] ?? 1),
                "tiene_rutina" => $tieneRutina
            ]
        ]
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Error al verificar la sesión: " . $e->getMessage(),
        "datos" => null
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
}
