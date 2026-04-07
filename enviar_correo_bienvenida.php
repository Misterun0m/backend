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
    $mensaje .= '<body style="margin:0;padding:0;background-color:#8B2FC9;">';
    $mensaje .= '<p>Bienvenido ' . $nombre . ', tu cuenta ha sido creada.</p>';
    $mensaje .= '</body></html>';

    $mail = new PHPMailer(true);
    $mail->SMTPDebug  = 2;
    $mail->Debugoutput = function($str, $level) {
        error_log("SMTP: $str");
    };
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