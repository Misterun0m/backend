<?php

function enviarCorreoBienvenida(string $correoDestino, string $nombre): void {

    $apiKey = getenv('RESEND_API_KEY') ?: ($_ENV['RESEND_API_KEY'] ?? '');

    if (!$apiKey) {
        throw new Exception("RESEND_API_KEY no configurada");
    }

    $nombre = htmlspecialchars($nombre);

    $html  = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Bienvenida</title></head>';
    $html .= '<body style="margin:0;padding:0;background-color:#8B2FC9;font-family:Arial,Helvetica,sans-serif;">';
    $html .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#8B2FC9;padding:30px 0;">';
    $html .= '<tr><td align="center">';
    $html .= '<table width="480" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff;border-radius:18px;overflow:hidden;">';
    $html .= '<tr><td align="center" style="padding:36px 40px 10px 40px;">';
    $html .= '<p style="margin:0;font-size:32px;">🏛️</p>';
    $html .= '</td></tr>';
    $html .= '<tr><td align="center" style="padding:8px 40px 0 40px;">';
    $html .= '<p style="margin:0;font-size:18px;font-weight:bold;color:#1a1a1a;">Inicio Ciudadano</p>';
    $html .= '</td></tr>';
    $html .= '<tr><td style="padding:16px 40px 0 40px;"><hr style="border:none;border-top:1px solid #e0e0e0;margin:0;"></td></tr>';
    $html .= '<tr><td align="center" style="padding:22px 40px 0 40px;">';
    $html .= '<p style="margin:0;font-size:21px;font-weight:bold;color:#8B2FC9;">&#161;Bienvenido, ' . $nombre . '!</p>';
    $html .= '</td></tr>';
    $html .= '<tr><td align="center" style="padding:14px 50px 0 50px;">';
    $html .= '<p style="margin:0;font-size:15px;color:#444444;line-height:1.7;text-align:center;">';
    $html .= 'Tu cuenta ha sido creada exitosamente. Ahora puedes acceder a todos los tr&aacute;mites y servicios disponibles en <strong>Inicio Ciudadano</strong>.';
    $html .= '</p></td></tr>';
    $html .= '<tr><td align="center" style="padding:26px 50px 0 50px;">';
    $html .= '<div style="background:#F3E8FF;border-radius:14px;padding:20px 30px;">';
    $html .= '<p style="margin:0;font-size:15px;color:#8B2FC9;font-weight:bold;">&#10003; Cuenta activada correctamente</p>';
    $html .= '</div></td></tr>';
    $html .= '<tr><td align="center" style="padding:18px 50px 0 50px;">';
    $html .= '<p style="margin:0;font-size:13px;color:#888888;text-align:center;line-height:1.6;">';
    $html .= 'Si no creaste esta cuenta, por favor cont&aacute;ctanos de inmediato.';
    $html .= '</p></td></tr>';
    $html .= '<tr><td style="padding:22px 40px 0 40px;"><hr style="border:none;border-top:1px solid #e0e0e0;margin:0;"></td></tr>';
    $html .= '<tr><td align="center" style="padding:18px 50px 30px 50px;">';
    $html .= '<p style="margin:0;font-size:12px;color:#aaaaaa;text-align:center;line-height:1.7;">';
    $html .= 'Gracias por unirte a nuestra plataforma.<br>';
    $html .= '<strong style="color:#555555;">Equipo de Inicio Ciudadano</strong>';
    $html .= '</p></td></tr>';
    $html .= '</table></td></tr></table></body></html>';

    $payload = json_encode([
        "from"    => "Inicio Ciudadano <onboarding@resend.dev>",
        "to"      => [$correoDestino],
        "subject" => "Bienvenido a Inicio Ciudadano",
        "html"    => $html
    ]);

    $ctx = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => "Authorization: Bearer $apiKey\r\nContent-Type: application/json\r\n",
            'content' => $payload,
            'ignore_errors' => true,
        ]
    ]);

    $result = file_get_contents('https://api.resend.com/emails', false, $ctx);

    if ($result === false) {
        throw new Exception("Error al conectar con Resend");
    }

    $response = json_decode($result, true);
    if (isset($response['statusCode']) && $response['statusCode'] >= 400) {
        throw new Exception("Resend error: " . ($response['message'] ?? 'desconocido'));
    }

    error_log("Correo enviado via Resend a $correoDestino");
}