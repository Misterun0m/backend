<?php
// ------------------------------
// Archivo: bd.php
// Descripción: Conexión a la base de datos MySQL usando mysqli.
// ------------------------------

$host = "centerbeam.proxy.rlwy.net";
$port = 16749;
$user = "root";
$pass = "ycrtevFnWGNbedBPSMIRQbEHWkqVXPNl";
$db   = "railway";

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
