<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    require_once "db.php";
    require_once __DIR__ . '/enviar_correo_bienvenida.php'; // ← agrega esto

    $rawData = file_get_contents("php://input");
    $data    = json_decode($rawData, true);

    if (!$data || empty($data['token'])) {
        echo json_encode(["success" => false, "message" => "Token no recibido"]);
        exit();
    }

    $token = $data['token'];

    $googleUrl = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $token;
    $response  = @file_get_contents($googleUrl);
    if ($response === false) throw new Exception("No se pudo validar el token con Google");

    $info = json_decode($response, true);
    if (!$info || isset($info['error'])) throw new Exception("Token inválido");

    $email     = $info['email'] ?? null;
    $name      = $info['name']  ?? null;
    $google_id = $info['sub']   ?? null;

    if (!$email || !$google_id) {
        echo json_encode(["success" => false, "message" => "Datos incompletos desde Google"]);
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM usuario WHERE user_correo = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $user = $result->fetch_assoc();
        echo json_encode([
            "success" => true,
            "message" => "Bienvenido",
            "user"    => [
                "user_id"          => $user['user_id'],
                "user_nom"         => $user['user_nom'],
                "user_sex"         => $user['user_sex']         ?? null,
                "fecha_nacimiento" => $user['fecha_nacimiento'] ?? null,
                "user_correo"      => $email,
                "google_id"        => $google_id
            ]
        ]);

    } else {

        $conn->begin_transaction();

        try {

            $default_sex   = null;
            $default_fecha = null;

            $insert = $conn->prepare("
                INSERT INTO usuario (user_nom, user_correo, google_id, user_sex, fecha_nacimiento, codigo_recuperacion)
                VALUES (?, ?, ?, ?, ?, 0)
            ");
            $insert->bind_param("sssss", $name, $email, $google_id, $default_sex, $default_fecha);
            $insert->execute();
            $newId = $insert->insert_id;
            $insert->close();

            $tramitesResult = $conn->query("SELECT tram_id FROM tramite ORDER BY tram_id ASC");

            $stmtTram = $conn->prepare("
                INSERT INTO user_tramite (user_id, tram_id, estado_tramite, fecha_inicio, fecha_finalizacion)
                VALUES (?, ?, 'Pendiente', CURDATE(), NULL)
            ");

            $tramitesAsignados = 0;
            while ($tram = $tramitesResult->fetch_assoc()) {
                $stmtTram->bind_param("ii", $newId, $tram['tram_id']);
                $stmtTram->execute();
                $tramitesAsignados++;
            }
            $stmtTram->close();

            $conn->commit();

            // ── Correo de bienvenida (fuera del commit) ────
            try {
                enviarCorreoBienvenida($email, $name);
            } catch (Exception $mailEx) {
                error_log("[Bienvenida Google] " . $mailEx->getMessage());
            }

            echo json_encode([
                "success"            => true,
                "message"            => "Usuario registrado",
                "tramites_asignados" => $tramitesAsignados,
                "user"               => [
                    "user_id"          => $newId,
                    "user_nom"         => $name,
                    "user_sex"         => null,
                    "fecha_nacimiento" => null,
                    "user_correo"      => $email,
                    "google_id"        => $google_id
                ]
            ]);

        } catch (Exception $innerEx) {
            $conn->rollback();
            http_response_code(500);
            echo json_encode([
                "success" => false,
                "message" => "Error al registrar usuario de Google",
                "error"   => $innerEx->getMessage()
            ]);
        }
    }

    $stmt->close();
    $conn->close();
    exit();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Error interno del servidor",
        "error"   => $e->getMessage()
    ]);
}
?>