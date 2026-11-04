
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

        $destinatario = getenv('MAIL_TO');

        if (!$destinatario) {
            error_log(
                'No se procesaron los correos: falta configurar MAIL_TO.'
            );
        } else {
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
                        $referenciaHtml = htmlspecialchars(
                            $referenciaServicio,
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        $descripcionHtml = htmlspecialchars(
                            $descripcion,
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        $fechaHtml = htmlspecialchars(
                            $fechaFinalizacion,
                            ENT_QUOTES,
                            'UTF-8'
                        );

                        $asunto = "Recordatorio: {$referenciaServicio}";

                        $mensajeHtml = "
                            <div style='font-family: Arial, sans-serif;
                                        max-width: 600px;
                                        margin: 0 auto;
                                        color: #333;'>

                                <h2 style='color: #343a40;'>
                                    Recordatorio de servicio
                                </h2>

                                <p>{$descripcionHtml}</p>

                                <table style='width: 100%;
                                              border-collapse: collapse;'>

                                    <tr>
                                        <td style='padding: 8px;
                                                   border-bottom: 1px solid #ddd;'>
                                            <strong>Servicio:</strong>
                                        </td>
                                        <td style='padding: 8px;
                                                   border-bottom: 1px solid #ddd;'>
                                            {$referenciaHtml}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td style='padding: 8px;
                                                   border-bottom: 1px solid #ddd;'>
                                            <strong>Fecha de finalización:</strong>
                                        </td>
                                        <td style='padding: 8px;
                                                   border-bottom: 1px solid #ddd;'>
                                            {$fechaHtml}
                                        </td>
                                    </tr>

                                </table>

                                <p style='margin-top: 20px;'>
                                    Este es un aviso automático del sistema.
                                </p>

                            </div>
                        ";

                        $mensajeTexto =
                            "Recordatorio de servicio\n\n" .
                            "{$descripcion}\n" .
                            "Servicio: {$referenciaServicio}\n" .
                            "Fecha de finalización: {$fechaFinalizacion}\n";

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
