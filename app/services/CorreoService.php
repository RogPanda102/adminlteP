<?php

use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../core/Env.php';

require_once __DIR__ . '/../libraries/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../libraries/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libraries/PHPMailer/src/SMTP.php';

// Cargar variables de entorno.
Env::cargar(__DIR__ . '/../../.env');

class CorreoService
{
    private static function crearMailer()
    {
        $host = getenv('MAIL_HOST');
        $usuario = getenv('MAIL_USER');
        $password = getenv('MAIL_PASS');
        $puerto = getenv('MAIL_PORT');
        $seguridad = strtolower(getenv('MAIL_ENCRYPTION') ?: 'ssl');
        $nombreRemitente = getenv('MAIL_FROM_NAME') ?: 'Mini ERP';

        if (!$host || !$usuario || !$password || !$puerto) {
            throw new RuntimeException(
                'La configuración SMTP está incompleta.'
            );
        }

        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host = $host;
        $mail->SMTPAuth = true;
        $mail->Username = $usuario;
        $mail->Password = $password;

        if ($seguridad === 'ssl') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($seguridad === 'tls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            throw new RuntimeException(
                'El tipo de cifrado SMTP no es válido.'
            );
        }

        $mail->Port = (int) $puerto;
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);

        $mail->setFrom($usuario, $nombreRemitente);

        return $mail;
    }

    public static function enviar(
        $destinatario,
        $asunto,
        $mensajeHtml,
        $mensajeTexto = ''
    ) {
        try {
            $mail = self::crearMailer();

            $mail->addAddress($destinatario);
            $mail->Subject = $asunto;
            $mail->Body = $mensajeHtml;

            $mail->AltBody = $mensajeTexto !== ''
                ? $mensajeTexto
                : strip_tags($mensajeHtml);

            $mail->send();

            return [
                'ok' => true,
                'mensaje' => 'Correo aceptado por el servidor SMTP.'
            ];

        } catch (\Throwable $e) {

            error_log(
                '[CorreoService] Error de envío: ' .
                get_class($e) . ' - ' .
                $e->getMessage()
            );

            return [
                'ok' => false,
                'mensaje' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}