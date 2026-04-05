<?php
error_reporting(0);
ini_set('display_errors', 0);

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = getenv('MYSQLHOST')     ?: 'localhost';
$user = getenv('MYSQLUSER')     ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$db   = getenv('MYSQLDATABASE') ?: 'jovenestramites';
$port = (int)(getenv('MYSQLPORT') ?: 3306);

$conn = new mysqli($host, $user, $pass, $db, $port);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $conn->connect_error]);
    exit();
}

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id <= 0) {
    http_response_code(400);
    echo json_encode(["error" => "user_id inválido o no proporcionado."]);
    exit();
}

$sql = "
    SELECT
        ut.user_idtramite,
        ut.estado_tramite,
        ut.fecha_inicio,
        ut.fecha_finalizacion,
        t.tram_id,
        t.tram_tip   AS nombre,
        t.tram_imp   AS descripcion,
        t.tram_saber AS info_util
    FROM user_tramite ut
    INNER JOIN tramite t ON ut.tram_id = t.tram_id
    WHERE ut.user_id = ?
    ORDER BY t.tram_id ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$tramites = [];
while ($row = $result->fetch_assoc()) {
    $tramites[] = [
        "user_idtramite"     => (int) $row['user_idtramite'],
        "tram_id"            => (int) $row['tram_id'],
        "nombre"             => $row['nombre'],
        "descripcion"        => $row['descripcion'],
        "info_util"          => $row['info_util'],
        "estado_tramite"     => $row['estado_tramite'],
        "fecha_inicio"       => $row['fecha_inicio'],
        "fecha_finalizacion" => $row['fecha_finalizacion'] ?? null,
    ];
}

$stmt->close();
$conn->close();

echo json_encode([
    "success"  => true,
    "user_id"  => $user_id,
    "tramites" => $tramites
], JSON_UNESCAPED_UNICODE);