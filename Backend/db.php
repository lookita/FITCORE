<?php
declare(strict_types=1);


function ConexionBaseDeDatos(): PDO {
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $env = parse_ini_file(__DIR__ . '/.env');

    $host = $env['DB_HOST'];
    $db   = $env['DB_NAME'];
    $user = $env['DB_USER'];
    $pass = $env['DB_PASS'];
    $port = $env['DB_PORT'];

    try {
        $pdo = new PDO(
            "pgsql:host={$host};port={$port};dbname={$db}",
            $user,
            $pass,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
        return $pdo;



            // activacion de alarma de algun error PDOException
        }catch (PDOException $e){
            //esconder el mensaje del error 
            error_log(
                "[db_connection_error]".
                $e->getMessage()
            );

        // Cortar el sistema con mensaje generico
        throw new RuntimeException("No se pudo establecer la conexion con la base de datos");
    }
}
