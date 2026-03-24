<?php

$host = "localhost";
$user = "root";
$pass = "";
$db   = "jovenestramites";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error de conexión a la base de datos"
    ]);
    exit;
}

$conn->set_charset("utf8mb4");