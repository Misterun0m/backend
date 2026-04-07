<?php
header("Content-Type: application/json");

set_error_handler(function($severity, $message) {
    echo json_encode(["status" => "error", "mensaje" => "PHP error: $message"]);
    exit;
});
set_exception_handler(function($e) {
    echo json_encode(["status" => "error", "mensaje" => $e->getMessage()]);
    exit;
});

$data = json_decode(file_get_contents("php://input"), true);
if (!$data || !isset($data["correo"]) || !isset($data["codigo"])) {
    echo json_encode(["status" => "error", "mensaje" => "Datos incompletos"]);
    exit;
}

$correoDestino = $data["correo"];
$codigo        = htmlspecialchars($data["codigo"]);
$nombre        = isset($data["nombre"]) ? htmlspecialchars($data["nombre"]) : "Usuario";

$apiKey = getenv('BREVO_API_KEY') ?: ($_ENV['BREVO_API_KEY'] ?? '');
if (!$apiKey) {
    throw new Exception("BREVO_API_KEY no configurada");
}

$logoB64 = '/9j/4AAQSkZJRgABAQAAAQABAAD/...'; // tu base64 completo aquí

$mensaje  = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Verificacion</title></head>';
$mensaje .= '<body style="margin:0;padding:0;background-color:#8B2FC9;font-family:Arial,Helvetica,sans-serif;">';
$mensaje .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#8B2FC9;padding:30px 0;">';
$mensaje .= '<tr><td align="center">';
$mensaje .= '<table width="480" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff;border-radius:18px;overflow:hidden;">';
$mensaje .= '<tr><td align="center" style="padding:36px 40px 10px 40px;">';
$mensaje .= '<img src="data:image/jpeg;base64,' . $logoB64 . '" width="70" height="70" alt="Inicio Ciudadano" style="display:block;border-radius:4px;">';
$mensaje .= '</td></tr>';
$mensaje .= '<tr><td align="center" style="padding:8px 40px 0 40px;">';
$mensaje .= '<p style="margin:0;font-size:18px;font-weight:bold;color:#1a1a1a;">Inicio Ciudadano</p>';
$mensaje .= '</td></tr>';
$mensaje .= '<tr><td style="padding:16px 40px 0 40px;"><hr style="border:none;border-top:1px solid #e0e0e0;margin:0;"></td></tr>';
$mensaje .= '<tr><td align="center" style="padding:22px 40px 0 40px;">';
$mensaje .= '<p style="margin:0;font-size:21px;font-weight:bold;color:#8B2FC9;">&#161;Hola, ' . $nombre . '!</p>';
$mensaje .= '</td></tr>';
$mensaje .= '<tr><td align="center" style="padding:14px 50px 0 50px;">';
$mensaje .= '<p style="margin:0;font-size:15px;color:#444444;line-height:1.7;text-align:center;">';
$mensaje .= 'Has solicitado restablecer tu contrase&ntilde;a. Utiliza el siguiente c&oacute;digo de verificaci&oacute;n:';
$mensaje .= '</p></td></tr>';
$mensaje .= '<tr><td align="center" style="padding:26px 50px 0 50px;">';
$mensaje .= '<div style="background:#F3E8FF;border:2.5px dashed #9B3FD4;border-radius:14px;padding:20px 30px;display:inline-block;">';
$mensaje .= '<span style="font-size:46px;font-weight:bold;color:#8B2FC9;letter-spacing:14px;">' . $codigo . '</span>';
$mensaje .= '</div></td></tr>';
$mensaje .= '<tr><td align="center" style="padding:18px 50px 0 50px;">';
$mensaje .= '<p style="margin:0;font-size:13px;color:#888888;text-align:center;line-height:1.6;">';
$mensaje .= 'Este c&oacute;digo es v&aacute;lido por 15 minutos. Por tu seguridad, no compartas este PIN con nadie.';
$mensaje .= '</p></td></tr>';
$mensaje .= '<tr><td style="padding:22px 40px 0 40px;"><hr style="border:none;border-top:1px solid #e0e0e0;margin:0;"></td></tr>';
$mensaje .= '<tr><td align="center" style="padding:18px 50px 30px 50px;">';
$mensaje .= '<p style="margin:0;font-size:12px;color:#aaaaaa;text-align:center;line-height:1.7;">';
$mensaje .= 'Si no solicitaste este cambio, puedes ignorar este correo de forma segura.<br>';
$mensaje .= '<strong style="color:#555555;">Equipo de Inicio Ciudadano</strong>';
$mensaje .= '</p></td></tr>';
$mensaje .= '</table></td></tr></table></body></html>';

$payload = json_encode([
    "sender"      => ["name" => "Inicio Ciudadano", "email" => "iniciociudadano@gmail.com"],
    "to"          => [["email" => $correoDestino]],
    "subject"     => "Restablecer contraseña - Inicio Ciudadano",
    "htmlContent" => $mensaje
]);

$ctx = stream_context_create([
    'http' => [
        'method'        => 'POST',
        'header'        => "api-key: $apiKey\r\nContent-Type: application/json\r\nAccept: application/json\r\n",
        'content'       => $payload,
        'ignore_errors' => true,
    ]
]);

$result = file_get_contents('https://api.brevo.com/v3/smtp/email', false, $ctx);
if ($result === false) {
    throw new Exception("Error al conectar con Brevo");
}

$response = json_decode($result, true);
if (isset($response['code'])) {
    throw new Exception("Brevo error: " . ($response['message'] ?? 'desconocido'));
}

error_log("Correo de recuperación enviado via Brevo a $correoDestino");
echo json_encode(["status" => "ok", "mensaje" => "Correo enviado correctamente"]);
