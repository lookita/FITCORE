<?php
declare(strict_types=1);

// ── 1. Configurar CORS y JSON ──
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=utf-8");

// Preflight de CORS
if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(200);
    exit;
}

// Solo permitir peticiones GET
if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    http_response_code(405);
    echo json_encode(["exito" => false, "mensaje" => "Método no permitido"]);
    exit;
}

require_once __DIR__ . "/db.php";

// ── 2. Obtener el usuario por ID (enviado por la URL: ?id=...) ──
$id = $_GET['id'] ?? null;

if (!$id) {
    http_response_code(400);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Debes enviar el id del usuario (?id=...)"
    ]);
    exit;
}

try {
    $pdo = ConexionBaseDeDatos();

    // ── 3. Consultar los datos del usuario directamente ──
    $stmt = $pdo->prepare("
        SELECT id, nombre, email, rol, xp_total, nivel_actual, ultima_sesion_at
        FROM usuarios
        WHERE id = :id
        LIMIT 1
    ");
    $stmt->execute(['id' => $id]);
    $usuario = $stmt->fetch();

    if (!$usuario) {
        http_response_code(404);
        echo json_encode([
            "exito" => false,
            "mensaje" => "Usuario no encontrado"
        ]);
        exit;
    }

    // ── 4. Devolver los datos del perfil ──
    http_response_code(200);
    echo json_encode([
        "exito" => true,
        "mensaje" => "Perfil obtenido con éxito",
        "datos" => $usuario
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "exito" => false,
        "mensaje" => "Error del servidor: " . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}