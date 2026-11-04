<?php

require_once __DIR__ . '/../app/services/CorreoService.php';

// Sustituye este valor por la dirección que quieras utilizar
// como destinatario de la prueba.
$destinatario = 'rogelio_hernandez@pandasoft.com.mx';

$resultado = CorreoService::enviar(
    $destinatario,
    'Prueba SMTP - Mini ERP',
    '
        <div style="font-family: Arial, sans-serif; padding: 20px;">
            <h2>Prueba de correo</h2>

            <p>El sistema administrativo ha enviado este mensaje
            utilizando PHPMailer y SMTP.</p>

            <p>Si recibiste este correo, la prueba de envío fue exitosa.</p>

            <hr>

            <small>Mini ERP - PandaSoft</small>
        </div>
    ',
    'Prueba de correo del mini ERP. Si recibiste este mensaje, la prueba fue exitosa.'
);

header('Content-Type: text/plain; charset=UTF-8');

echo $resultado['mensaje'];