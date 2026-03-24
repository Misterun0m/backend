<?php

header("Access-Control-Allow-Origin: https://jovenes-tramites.vercel.app");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] === "OPTIONS") {
    exit;
}

require_once "conexion.php";

$input = file_get_contents("php://input");

if (!$input) {
    echo json_encode(["error" => "No llegaron datos"]);
    exit;
}

$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode([
        "error" => "JSON inválido",
        "recibido" => $input
    ]);
    exit;
}

$correo = $data["correo"] ?? '';
$codigo = $data["codigo"] ?? '';

$stmt = $pdo->prepare("
SELECT user_id FROM usuario
WHERE user_correo=? AND codigo_recuperacion=?
LIMIT 1
");

$stmt->execute([$correo,$codigo]);

if($stmt->fetch()){
    echo json_encode(["success"=>true]);
}else{
    echo json_encode(["error"=>"Código inválido o expirado"]);
}