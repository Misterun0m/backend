<?php
header("Content-Type: application/json; charset=UTF-8");
header("Cross-Origin-Opener-Policy: same-origin");
header("Cross-Origin-Embedder-Policy: require-corp");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    require_once "db.php"; // tu conexión $conn

    $rawData = file_get_contents("php://input");
    if (!$rawData) throw new Exception("No se recibieron datos");

    $data = json_decode($rawData, true);
    if (json_last_error() !== JSON_ERROR_NONE) throw new Exception("JSON inválido");

    if (empty($data['correo']) || empty($data['password'])) {
        echo json_encode([
            "success" => false,
            "message" => "Todos los campos son obligatorios"
        ]);
        exit();
    }

    $correo = trim($data['correo']);
    $password = trim($data['password']);

    $stmt = $conn->prepare("
        SELECT user_id, user_nom, user_pass, user_sex, fecha_nacimiento, google_id
        FROM usuario
        WHERE user_correo = ?
    ");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            "success" => false,
            "message" => "Usuario no encontrado"
        ]);
        exit();
    }

    $user = $result->fetch_assoc();

    // Para pruebas: contraseña plano (en producción usar password_verify)
    if ($password !== $user['user_pass']) {
        echo json_encode([
            "success" => false,
            "message" => "Contraseña incorrecta"
        ]);
        exit();
    }

    echo json_encode([
        "success" => true,
        "message" => "Login exitoso",
        "user" => [
            "user_id"          => $user['user_id'],
            "user_nom"         => $user['user_nom'],
            "user_sex"         => $user['user_sex'] ?? null,
            "fecha_nacimiento" => $user['fecha_nacimiento'] ?? null,
            "user_correo"      => $correo,
            "google_id"        => $user['google_id'] ?? null
        ]
    ]);

    $stmt->close();
    $conn->close();
    exit();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error interno del servidor",
        "error" => $e->getMessage()
    ]);
}
?>
