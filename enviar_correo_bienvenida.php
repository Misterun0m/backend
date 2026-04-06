<?php
declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

function enviarCorreoBienvenida(string $correoDestino, string $nombre): void
{
    $credentialsPath = file_exists('/etc/secrets/credentials.json')
        ? '/etc/secrets/credentials.json'
        : __DIR__ . '/credentials.json';

    $tokenPath = file_exists('/etc/secrets/token.json')
        ? '/etc/secrets/token.json'
        : __DIR__ . '/token.json';

    $client = new Google_Client();
    $client->setAuthConfig($credentialsPath);
    $client->addScope(Google_Service_Gmail::GMAIL_SEND);

    $accessToken = json_decode(file_get_contents($tokenPath), true);
    $client->setAccessToken($accessToken);

    if ($client->isAccessTokenExpired()) {
        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }

    $service = new Google_Service_Gmail($client);

    $nombre         = htmlspecialchars($nombre);
    $subject        = 'Bienvenido a Inicio Ciudadano';
    $subjectEncoded = '=?UTF-8?B?' . base64_encode($subject) . '?=';

    $mensaje  = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Bienvenida</title></head>';
    $mensaje .= '<body style="margin:0;padding:0;background-color:#8B2FC9;font-family:Arial,Helvetica,sans-serif;">';
    $mensaje .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#8B2FC9;padding:30px 0;">';
    $mensaje .= '<tr><td align="center">';
    $mensaje .= '<table width="480" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff;border-radius:18px;overflow:hidden;">';
    $mensaje .= '<tr><td align="center" style="padding:8px 40px 0 40px;">';
    $mensaje .= '<p style="margin:0;font-size:18px;font-weight:bold;color:#1a1a1a;">Inicio Ciudadano</p>';
    $mensaje .= '</td></tr>';
    $mensaje .= '<tr><td style="padding:16px 40px 0 40px;"><hr style="border:none;border-top:1px solid #e0e0e0;margin:0;"></td></tr>';
    $mensaje .= '<tr><td align="center" style="padding:22px 40px 0 40px;">';
    $mensaje .= '<p style="margin:0;font-size:21px;font-weight:bold;color:#8B2FC9;">&#161;Bienvenido, ' . $nombre . '!</p>';
    $mensaje .= '</td></tr>';
    $mensaje .= '<tr><td align="center" style="padding:20px 40px 0 40px;">';
    $mensaje .= '<div style="width:70px;height:70px;background:#F3E8FF;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:36px;">&#127881;</div>';
    $mensaje .= '</td></tr>';
    $mensaje .= '<tr><td align="center" style="padding:18px 50px 0 50px;">';
    $mensaje .= '<p style="margin:0;font-size:15px;color:#444444;line-height:1.8;text-align:center;">';
    $mensaje .= 'Tu cuenta ha sido creada exitosamente. Ya puedes acceder a todos los tr&aacute;mites disponibles para j&oacute;venes de 18 a&ntilde;os.';
    $mensaje .= '</p></td></tr>';
    $mensaje .= '<tr><td style="padding:24px 40px 0 40px;">';
    $mensaje .= '<div style="background:#F9F5FF;border-radius:12px;padding:20px 24px;">';
    $mensaje .= '<p style="margin:0 0 12px 0;font-size:14px;font-weight:bold;color:#6B21A8;">&#10003;&nbsp; Lo que puedes hacer:</p>';

    $items = [
        '&#128196; Gestionar tus tr&aacute;mites paso a paso',
        '&#128205; Ubicar m&oacute;dulos de atenci&oacute;n cercanos',
        '&#128203; Revisar requisitos de cada tr&aacute;mite',
        '&#128100; Actualizar tu perfil en cualquier momento',
    ];
    foreach ($items as $item) {
        $mensaje .= '<p style="margin:8px 0 0 0;font-size:13px;color:#555555;">' . $item . '</p>';
    }

    $mensaje .= '</div></td></tr>';
    $mensaje .= '<tr><td style="padding:28px 40px 0 40px;"><hr style="border:none;border-top:1px solid #e0e0e0;margin:0;"></td></tr>';
    $mensaje .= '<tr><td align="center" style="padding:18px 50px 30px 50px;">';
    $mensaje .= '<p style="margin:0;font-size:12px;color:#aaaaaa;text-align:center;line-height:1.7;">';
    $mensaje .= 'Si no creaste esta cuenta, puedes ignorar este correo de forma segura.<br>';
    $mensaje .= '<strong style="color:#555555;">Equipo de Inicio Ciudadano</strong>';
    $mensaje .= '</p></td></tr>';
    $mensaje .= '</table>';
    $mensaje .= '</td></tr></table>';
    $mensaje .= '</body></html>';

    $rawMessage = "To: $correoDestino\r\n" .
                  "Subject: $subjectEncoded\r\n" .
                  "MIME-Version: 1.0\r\n" .
                  "Content-Type: text/html; charset=utf-8\r\n\r\n" .
                  $mensaje;

    $rawMessage = base64_encode($rawMessage);
    $rawMessage = str_replace(['+', '/', '='], ['-', '_', ''], $rawMessage);

    $msg = new Google_Service_Gmail_Message();
    $msg->setRaw($rawMessage);
    $service->users_messages->send("me", $msg);
}