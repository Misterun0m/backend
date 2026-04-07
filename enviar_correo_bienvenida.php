<?php

require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function enviarCorreoBienvenida(string $correoDestino, string $nombre): void {

    $gmailUser = getenv('GMAIL_USER') ?: ($_ENV['GMAIL_USER'] ?? '');
    $gmailPass = getenv('GMAIL_PASS') ?: ($_ENV['GMAIL_PASS'] ?? '');

    if (!$gmailUser || !$gmailPass) {
        throw new Exception("Variables GMAIL_USER o GMAIL_PASS no configuradas");
    }

    $nombre = htmlspecialchars($nombre);

    $mensaje  = '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8"><title>Bienvenida</title></head>';
    $mensaje .= '<body style="margin:0;padding:0;background-color:#8B2FC9;font-family:Arial,Helvetica,sans-serif;">';
    $mensaje .= '<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#8B2FC9;padding:30px 0;">';
    $mensaje .= '<tr><td align="center">';
    $mensaje .= '<table width="480" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff;border-radius:18px;overflow:hidden;">';
    $mensaje .= '<tr><td align="center" style="padding:36px 40px 10px 40px;">';
    $mensaje .= '<p style="margin:0;font-size:32px;">🏛️</p>';
    $mensaje .= '</td></tr>';
    $mensaje .= '<tr><td align="center" style="padding:8px 40px 0 40px;">';
    $mensaje .= '<p style="margin:0;font-size:18px;font-weight:bold;color:#1a1a1a;">Inicio Ciudadano</p>';
    $mensaje .= '</td></tr>';
    $mensaje .= '<tr><td style="padding:16px 40px 0 40px;"><hr style="border:none;border-top:1px solid #e0e0e0;margin:0;"></td></tr>';
    $mensaje .= '<tr><td align="center" style="padding:22px 40px 0 40px;">';
    $mensaje .= '<p style="margin:0;font-size:21px;font-weight:bold;color:#8B2FC9;">&#161;Bienvenido, ' . $nombre . '!</p>';
    $mensaje .= '</td></tr>';
    $mensaje .= '<tr><td align="center" style="padding:14px 50px 0 50px;">';
    $mensaje .= '<p style="margin:0;font-size:15px;color:#444444;line-height:1.7;text-align:center;">';
    $mensaje .= 'Tu cuenta ha sido creada exitosamente. Ahora puedes acceder a todos los tr&aacute;mites y servicios disponibles en <strong>Inicio Ciudadano</strong>.';
    $mensaje .= '</p></td></tr>';
    $mensaje .= '<tr><td align="center" style="padding:26px 50px 0 50px;">';
    $mensaje .= '<div style="background:#F3E8FF;border-radius:14px;padding:20px 30px;">';
    $mensaje .= '<p style="margin:0;font-size:15px;color:#8B2FC9;font-weight:bold;">&#10003; Cuenta activada correctamente</p>';
    $mensaje .= '</div></td></tr>';
    $mensaje .= '<tr><td align="center" style="padding:18px 50px 0 50px;">';
    $mensaje .= '<p style="margin:0;font-size:13px;color:#888888;text-align:center;line-height:1.6;">';
    $mensaje .= 'Si no creaste esta cuenta, por favor cont&aacute;ctanos de inmediato.';
    $mensaje .= '</p></td></tr>';
    $mensaje .= '<tr><td style="padding:22px 40px 0 40px;"><hr style="border:none;border-top:1px solid #e0e0e0;margin:0;"></td></tr>';
    $mensaje .= '<tr><td align="center" style="padding:18px 50px 30px 50px;">';
    $mensaje .= '<p style="margin:0;font-size:12px;color:#aaaaaa;text-align:center;line-height:1.7;">';
    $mensaje .= 'Gracias por unirte a nuestra plataforma.<br>';
    $mensaje .= '<strong style="color:#555555;">Equipo de Inicio Ciudadano</strong>';
    $mensaje .= '</p></td></tr>';
    $mensaje .= '</table></td></tr></table></body></html>';

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = $gmailUser;
    $mail->Password   = $gmailPass;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';
    $mail->setFrom($gmailUser, 'Inicio Ciudadano');
    $mail->addAddress($correoDestino);
    $mail->isHTML(true);
    $mail->Subject = 'Bienvenido a Inicio Ciudadano';
    $mail->Body    = $mensaje;
    $mail->send();
}