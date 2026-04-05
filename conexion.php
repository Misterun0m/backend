<?php
$host    = getenv('MYSQLHOST')     ?: 'y2c6vd.h.filess.io';
$db      = getenv('MYSQLDATABASE') ?: 'jovenestramites_atomgrass';
$user    = getenv('MYSQLUSER')     ?: 'jovenestramites_atomgrass';
$pass    = getenv('MYSQLPASSWORD') ?: 'b0a4ff2d3a099211c340197186ba2f9472fa901d';
$port    = getenv('MYSQLPORT')     ?: '3306';
$charset = "utf8mb4";

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(["error" => "No se pudo conectar a la BD"]);
    exit;
}