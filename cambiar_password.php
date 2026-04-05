<?php

header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD']=="OPTIONS"){ exit; }

require_once "conexion.php";

$data = json_decode(file_get_contents("php://input"), true);

$correo = $data["correo"];
$password = $data["password"];

$stmt = $pdo->prepare("
UPDATE usuario
SET user_pass=?
WHERE user_correo=?
");

$stmt->execute([$password,$correo]);

echo json_encode([
 "success"=>true,
 "mensaje"=>"Contraseña actualizada"
]);
?>