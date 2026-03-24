<?php
// ------------------------------
// Archivo: generar_codigo.php
// ------------------------------

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") exit;

// Manejar errores
set_error_handler(function($severity, $message) {
    echo json_encode(["status"=>"error","mensaje"=>"PHP error: $message"]);
    exit;
});
set_exception_handler(function($e){
    echo json_encode(["status"=>"error","mensaje"=>$e->getMessage()]);
    exit;
});

require_once __DIR__ . "/conexion.php";

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data["correo"])) {
    echo json_encode(["status"=>"error","mensaje"=>"Correo requerido"]);
    exit;
}

$correo = $data["correo"];

// Verificar que el usuario exista
$stmt = $pdo->prepare("SELECT user_id FROM usuario WHERE user_correo=?");
$stmt->execute([$correo]);
if (!$stmt->fetch()) {
    echo json_encode(["status"=>"error","mensaje"=>"Correo no registrado"]);
    exit;
}

// Generar código
$codigo = rand(100000,999999);

// Guardar código en DB
$stmt = $pdo->prepare("UPDATE usuario SET codigo_recuperacion=? WHERE user_correo=?");
$stmt->execute([$codigo,$correo]);

// Llamar a enviar_correo.php
$datosCorreo = ["correo"=>$correo,"codigo"=>$codigo];
$ch = curl_init("http://localhost/backend/enviar_correo.php");
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($datosCorreo)
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode != 200 || !$response) {
    echo json_encode(["status"=>"error","mensaje"=>"Error al enviar correo"]);
    exit;
}

$resJson = json_decode($response, true);
if(!$resJson) {
    echo json_encode(["status"=>"error","mensaje"=>"Respuesta del servidor no es JSON"]);
    exit;
}

echo json_encode(["status"=>"ok","mensaje"=>"Código enviado al correo"]);