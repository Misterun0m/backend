<?php
declare(strict_types=1);

header("Access-Control-Allow-Origin: https://jovenes-tramites.vercel.app");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json; charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once("conexion.php");
require_once("enviar_correo_bienvenida.php"); // ← archivo de correo

function jsonBody(): array {
    $raw  = file_get_contents("php://input");
    $data = json_decode($raw ?: "{}", true);
    return is_array($data) ? $data : [];
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
    exit;
}

$data = jsonBody();

$user_nom    = trim((string)($data["user_nom"]         ?? ""));
$user_sex    = trim((string)($data["user_sex"]         ?? ""));
$user_pass   = trim((string)($data["user_pass"]        ?? ""));
$user_correo = trim((string)($data["user_correo"]      ?? ""));
$fecha_nac   = trim((string)($data["fecha_nacimiento"] ?? ""));

/* VALIDACIONES */
if ($user_nom === "" || $user_correo === "" || $user_pass === "") {
    http_response_code(400);
    echo json_encode(["error" => "Todos los campos son obligatorios"]);
    exit;
}

if (!in_array($user_sex, ["Femenino", "Masculino"])) {
    http_response_code(400);
    echo json_encode(["error" => "Sexo inválido"]);
    exit;
}

if (!filter_var($user_correo, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["error" => "Correo inválido"]);
    exit;
}

/* Verificar si el correo ya existe */
$check = $pdo->prepare("SELECT user_id FROM usuario WHERE user_correo = ?");
$check->execute([$user_correo]);
if ($check->fetch()) {
    http_response_code(409);
    echo json_encode(["error" => "El correo ya está registrado"]);
    exit;
}

/* TRANSACCIÓN */
try {

    $pdo->beginTransaction();

    // 1. Insertar usuario
    $stmt = $pdo->prepare("
        INSERT INTO usuario (user_nom, user_sex, user_pass, user_correo, fecha_nacimiento, codigo_recuperacion)
        VALUES (?, ?, ?, ?, ?, 0)
    ");
    $stmt->execute([
        $user_nom,
        $user_sex,
        $user_pass,
        $user_correo,
        $fecha_nac !== "" ? $fecha_nac : null
    ]);

    $nuevo_user_id = (int) $pdo->lastInsertId();

    // 2. Obtener todos los trámites disponibles
    $tramites      = $pdo->query("SELECT tram_id FROM tramite ORDER BY tram_id ASC");
    $listaTramites = $tramites->fetchAll(PDO::FETCH_ASSOC);

    // 3. Insertar un registro por cada trámite
    $stmtTram = $pdo->prepare("
        INSERT INTO user_tramite (user_id, tram_id, estado_tramite, fecha_inicio, fecha_finalizacion)
        VALUES (?, ?, 'Pendiente', CURDATE(), NULL)
    ");

    foreach ($listaTramites as $tram) {
        $stmtTram->execute([$nuevo_user_id, $tram['tram_id']]);
    }

    $pdo->commit();

    // 4. Enviar correo de bienvenida (fuera de la transacción para no afectar el registro)
    try {
        enviarCorreoBienvenida($user_correo, $user_nom);
    } catch (Exception $mailEx) {
        // Si falla el correo, el registro ya se guardó — solo logueamos el error
        error_log("Error enviando correo de bienvenida: " . $mailEx->getMessage());
    }

    http_response_code(201);
    echo json_encode([
        "success"            => true,
        "mensaje"            => "Usuario registrado correctamente",
        "tramites_asignados" => count($listaTramites)
    ]);

} catch (PDOException $e) {

    $pdo->rollBack();

    http_response_code(500);
    echo json_encode([
        "error"   => "Error en la base de datos",
        "detalle" => $e->getMessage()
    ]);
}
?>