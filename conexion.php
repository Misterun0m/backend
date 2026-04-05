<?php
$host    = getenv('MYSQLHOST')     ?: 'localhost';
$db      = getenv('MYSQLDATABASE') ?: 'jovenestramites';
$user    = getenv('MYSQLUSER')     ?: 'root';
$pass    = getenv('MYSQLPASSWORD') ?: '';
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