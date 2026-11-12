
<?php

require_once __DIR__ . '/../models/EventoNotificacion.php';
require_once __DIR__ . '/../models/Notificacion.php';
require_once __DIR__ . '/../models/Servicios.php';
require_once __DIR__ . '/../services/CorreoService.php';

class ProcesadorNotificaciones
{
    public function procesar()
    {
        $eventoModelo = new EventoNotificacion();
        $notificacionModelo = new Notificacion();
        $servicioModelo = new Servicio();

        $resultado = [
            'servicios_revisados' => 0,
            'eventos_encontrados' => 0,
            'notificaciones_creadas' => 0,
            'duplicados' => 0,
            'correos_encontrados' => 0,
            'correos_enviados' => 0,
            'correos_fallidos' => 0,
            'errores' => 0
        ];

        // =========================================
        // 1. GENERAR EVENTOS PARA LOS SERVICIOS
        // =========================================

        $servicios = $servicioModelo
            ->obtenerServiciosParaNotificaciones();

        foreach ($servicios as $servicio) {
            try {
                $generado = $eventoModelo->generarParaServicio(
                    (int) $servicio['id']
                );

                if ($generado) {
                    $resultado['servicios_revisados']++;
                }
            } catch (Throwable $e) {
                $resultado['errores']++;

                error_log(
                    'Error generando eventos del servicio ' .
                    $servicio['id'] . ': ' . $e->getMessage()
                );
            }
        }

        // =========================================
        // 2. PROCESAR NOTIFICACIONES INTERNAS
        // =========================================

        $eventos = [];

        try {
            $eventos = $eventoModelo->obtenerPendientes();
        } catch (Throwable $e) {
            $resultado['errores']++;

            error_log(
                'Error obteniendo eventos pendientes: ' .
                $e->getMessage()
            );
        }

        $resultado['eventos_encontrados'] = count($eventos);

        foreach ($eventos as $evento) {
            try {
                $cantidad = (int) $evento['cantidad'];
                $unidad = $evento['unidad'];
                $momento = $evento['momento'];

                // Validar configuración del recordatorio.
                if (
                    $cantidad < 0 ||
                    !in_array($unidad, ['dias', 'meses'], true) ||
                    !in_array($momento, ['antes', 'despues'], true)
                ) {
                    $resultado['errores']++;

                    error_log(
                        'Configuración no válida. Evento ID: ' .
                        $evento['id']
                    );

                    continue;
                }

                $unidadTexto = ($unidad === 'meses')
                    ? ($cantidad === 1 ? 'mes' : 'meses')
                    : ($cantidad === 1 ? 'día' : 'días');

                $referenciaServicio =
                    "El servicio {$evento['req']} - {$evento['folio']}";

                $eventoNombreAnterior = null;

                // Aviso el día del vencimiento.
                if ($momento === 'antes' && $cantidad === 0) {
                    $titulo = 'Servicio vence hoy';
                    $mensaje = "{$referenciaServicio} vence hoy.";
                    $tipo = 'danger';
                    $eventoNombre = 'vencimiento_hoy';

                // Aviso previo al vencimiento.
                } elseif ($momento === 'antes') {
                    $titulo =
                        "Servicio vence en {$cantidad} {$unidadTexto}";

                    $mensaje =
                        "{$referenciaServicio} vence en " .
                        "{$cantidad} {$unidadTexto}.";

                    $tipo = 'warning';

                    $eventoNombre =
                        "vencimiento_antes_{$cantidad}_{$unidad}";

                    // Compatibilidad con nombres anteriores.
                    if ($unidad === 'dias') {
                        $eventoNombreAnterior =
                            "vencimiento_{$cantidad}";

                    } elseif (
                        $cantidad === 3 &&
                        $unidad === 'meses'
                    ) {
                        $eventoNombreAnterior = 'vencimiento_90';
                    }

                // Aviso posterior al vencimiento.
                } else {
                    $titulo =
                        "Servicio vencido hace {$cantidad} {$unidadTexto}";

                    $mensaje =
                        "{$referenciaServicio} venció hace " .
                        "{$cantidad} {$unidadTexto}.";

                    $tipo = 'danger';

                    $eventoNombre =
                        "vencimiento_despues_{$cantidad}_{$unidad}";
                }

                // Comprobar si ya existe la notificación.
                $existe = $notificacionModelo->existeEvento(
                    $evento['usuario_id'],
                    'servicios',
                    $evento['servicio_id'],
                    $eventoNombre,
                    $evento['id']
                );

                // Comprobar también el nombre anterior.
                if (
                    !$existe &&
                    $eventoNombreAnterior !== null
                ) {
                    $existe = $notificacionModelo->existeEvento(
                        $evento['usuario_id'],
                        'servicios',
                        $evento['servicio_id'],
                        $eventoNombreAnterior,
                        $evento['id']
                    );
                }

                if ($existe) {
                    if (!$eventoModelo->marcarEnviado($evento['id'])) {
                        $resultado['errores']++;

                        error_log(
                            'No se pudo marcar como enviado el evento ' .
                            $evento['id']
                        );
                    } else {
                        $resultado['duplicados']++;
                    }

                    continue;
                }

                // Crear la notificación interna.
                $creada = $notificacionModelo->crear([
                    'usuario_id' => $evento['usuario_id'],
                    'titulo' => $titulo,
                    'mensaje' => $mensaje,
                    'url' => 'servicios/' . $evento['anio'],
                    'tipo' => $tipo,
                    'modulo' => 'servicios',
                    'registro_id' => $evento['servicio_id'],
                    'evento' => $eventoNombre,
                    'evento_notificacion_id' => $evento['id']
                ]);

                if (!$creada) {
                    $resultado['errores']++;

                    error_log(
                        'No se pudo crear la notificación del evento ' .
                        $evento['id']
                    );

                    continue;
                }

                // Marcar después de crear la notificación.
                if (!$eventoModelo->marcarEnviado($evento['id'])) {
                    $resultado['errores']++;

                    error_log(
                        'La notificación del evento ' . $evento['id'] .
                        ' se creó, pero no se pudo marcar como enviado.'
                    );

                    continue;
                }

                $resultado['notificaciones_creadas']++;

            } catch (Throwable $e) {
                $resultado['errores']++;

                error_log(
                    'Error procesando evento ' . $evento['id'] .
                    ': ' . $e->getMessage()
                );
            }
        }

        // =========================================
        // 3. PROCESAR CORREOS
        // =========================================

            try {
                $correos = $eventoModelo->obtenerCorreosPendientes();

                $resultado['correos_encontrados'] = count($correos);

                foreach ($correos as $correo) {
                    try {
                        // Reclamar el evento antes de enviar.
                        // Evita que dos solicitudes lo procesen a la vez.
                        if (
                            !$eventoModelo->iniciarEnvioCorreo($correo['id'])
                        ) {
                            continue;
                        }
                        // Obtener el correo del usuario que registró el servicio.
                        $destinatario = trim(
                            (string) ($correo['correo_destinatario'] ?? '')
                        );

                        // Validar la dirección del destinatario.
                        if (
                            $destinatario === '' ||
                            !filter_var($destinatario, FILTER_VALIDATE_EMAIL)
                        ) {
                            $eventoModelo->marcarCorreoFallido($correo['id']);

                            $resultado['correos_fallidos']++;

                            error_log(
                                'Destinatario inválido para el evento ' . $correo['id']
                            );

                            continue;
                        }

                        $cantidad = (int) $correo['cantidad'];
                        $unidad = $correo['unidad'];
                        $momento = $correo['momento'];

                        $unidadTexto = ($unidad === 'meses')
                            ? ($cantidad === 1 ? 'mes' : 'meses')
                            : ($cantidad === 1 ? 'día' : 'días');

                        $referenciaServicio =
                            "Servicio {$correo['req']} - {$correo['folio']}";

                        if ($momento === 'antes' && $cantidad === 0) {
                            $descripcion = 'El servicio vence hoy.';

                        } elseif ($momento === 'antes') {
                            $descripcion =
                                "El servicio vence en {$cantidad} " .
                                "{$unidadTexto}.";

                        } else {
                            $descripcion =
                                "El servicio venció hace {$cantidad} " .
                                "{$unidadTexto}.";
                        }

                        $fechaFinalizacion = date(
                            'd/m/Y',
                            strtotime($correo['finalizacion'])
                        );

                        
                    
                        // Escapar los valores antes de incluirlos en HTML.
                        $folioHtml = htmlspecialchars(
                            (string) $correo['folio'],
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        $partidaHtml = htmlspecialchars(
                            (string) $correo['partida'],
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        $descripcionHtml = htmlspecialchars(
                            $descripcion,
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        // Asunto del correo.
                        $asunto = "Aviso de servicio - Folio {$correo['folio']}";

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
                            "Folio: {$correo['folio']}\n" .
                            "Partida: {$correo['partida']}\n\n" .
                            "Este es un aviso automático del sistema administrativo.";



                        $envio = CorreoService::enviar(
                            $destinatario,
                            $asunto,
                            $mensajeHtml,
                            $mensajeTexto
                        );

                        if (!empty($envio['ok'])) {
                            if (
                                $eventoModelo->marcarCorreoEnviado(
                                    $correo['id']
                                )
                            ) {
                                $resultado['correos_enviados']++;
                            } else {
                                $resultado['errores']++;

                                error_log(
                                    'El correo del evento ' .
                                    $correo['id'] .
                                    ' se envió, pero no se pudo registrar.'
                                );
                            }

                        } else {
                            $eventoModelo->marcarCorreoFallido(
                                $correo['id']
                            );

                            $resultado['correos_fallidos']++;

                            error_log(
                                'Falló el envío del correo del evento ' .
                                $correo['id']
                            );
                        }

                    } catch (Throwable $e) {
                        $resultado['errores']++;

                        error_log(
                            'Error procesando correo del evento ' .
                            $correo['id'] . ': ' . $e->getMessage()
                        );

                        try {
                            $eventoModelo->marcarCorreoFallido(
                                $correo['id']
                            );
                        } catch (Throwable $errorCorreo) {
                            error_log(
                                'No se pudo registrar el fallo del correo ' .
                                $correo['id'] . ': ' .
                                $errorCorreo->getMessage()
                            );
                        }
                    }
                }

            } catch (Throwable $e) {
                $resultado['errores']++;

                error_log(
                    'Error obteniendo o procesando correos pendientes: ' .
                    $e->getMessage()
                );
            }
        

        // =========================================
        // 4. LIMPIAR EVENTOS VENCIDOS
        // =========================================

        try {
            $eventoModelo->eliminarEventosVencidos();
        } catch (Throwable $e) {
            $resultado['errores']++;

            error_log(
                'Error eliminando eventos vencidos: ' .
                $e->getMessage()
            );
        }

        return $resultado;
    }
}
