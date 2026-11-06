<?php

require_once __DIR__ . '/../app/services/CorreoService.php';

$destinatario = 'rogelio_hernandez@pandasoft.com.mx';

// Datos de prueba.
$folio = '3010';
$partida = 'Servicio de mantenimiento y soporte técnico';
$descripcion = 'El servicio vence en 7 días.';

// Escapar valores para HTML.
$folioHtml = htmlspecialchars($folio, ENT_QUOTES, 'UTF-8');
$partidaHtml = htmlspecialchars($partida, ENT_QUOTES, 'UTF-8');
$descripcionHtml = htmlspecialchars($descripcion, ENT_QUOTES, 'UTF-8');

// Asunto.
$asunto = "Aviso de servicio - Folio {$folio}";

// Mensaje HTML.
$mensajeHtml = "
    <div style='
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        font-family: Arial, Helvetica, sans-serif;
        color: #343a40;
        background-color: #ffffff;
    '>

        <div style='
            padding: 22px;
            background-color: #243B55;
            border-radius: 8px 8px 0 0;
            color: #ffffff;
        '>
            <div style='
                font-size: 11px;
                letter-spacing: 1px;
                margin-bottom: 8px;
                color: #dce7f2;
            '>
                PANDASOFT - SISTEMA ADMINISTRATIVO
            </div>

            <h2 style='
                margin: 0;
                font-size: 23px;
                color: #ffffff;
            '>
                AVISO DE SERVICIO
            </h2>
        </div>

        <div style='
            padding: 25px;
            border: 1px solid #dee2e6;
            border-top: none;
        '>

            <p style='
                margin-top: 0;
                margin-bottom: 22px;
                font-size: 15px;
                line-height: 1.6;
            '>
                {$descripcionHtml}
            </p>

            <div style='
                margin-bottom: 18px;
                padding: 16px;
                background-color: #f4f6f9;
                border-left: 4px solid #243B55;
            '>
                <div style='
                    margin-bottom: 7px;
                    color: #6c757d;
                    font-size: 11px;
                    font-weight: bold;
                    letter-spacing: 1px;
                '>
                    FOLIO
                </div>

                <div style='
                    color: #243B55;
                    font-size: 25px;
                    font-weight: bold;
                '>
                    {$folioHtml}
                </div>
            </div>

            <div style='
    margin-bottom: 18px;
    padding: 16px;
    background-color: #f4f6f9;
    border-left: 4px solid #243B55;
'>
    <div style='
        margin-bottom: 7px;
        color: #6c757d;
        font-size: 11px;
        font-weight: bold;
        letter-spacing: 1px;
    '>
        PARTIDA
    </div>

    <div style='
        color: #243B55;
        font-size: 18px;
        font-weight: bold;
        line-height: 1.5;
    '>
        {$partidaHtml}
    </div>
</div>

        </div>

        <div style='
            padding: 15px;
            text-align: center;
            font-size: 11px;
            color: #6c757d;
            background-color: #f4f6f9;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 8px 8px;
        '>
            Este es un aviso automático del sistema administrativo.
            <br>
            Por favor, no responda a este mensaje.
        </div>

    </div>
";

// Versión de texto plano.
$mensajeTexto =
    "AVISO DE SERVICIO\n\n" .
    "{$descripcion}\n\n" .
    "Folio: {$folio}\n" .
    "Partida: {$partida}\n\n" .
    "Este es un aviso automático del sistema administrativo.";

// Enviar correo.
$resultado = CorreoService::enviar(
    $destinatario,
    $asunto,
    $mensajeHtml,
    $mensajeTexto
);

header('Content-Type: text/plain; charset=UTF-8');

echo $resultado['mensaje'];
