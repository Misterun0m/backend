<?php
$host = getenv('MYSQLHOST')     ?: 'y2c6vd.h.filess.io';
$user = getenv('MYSQLUSER')     ?: 'jovenestramites_atomgrass';
$pass = getenv('MYSQLPASSWORD') ?: 'b0a4ff2d3a099211c340197186ba2f9472fa901d';
$db   = getenv('MYSQLDATABASE') ?: 'jovenestramites_atomgrass';
$port = (int)(getenv('MYSQLPORT') ?: 3306);

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error de conexión a la base de datos"
    ]);
    exit;
}

$conn->set_charset("utf8mb4");