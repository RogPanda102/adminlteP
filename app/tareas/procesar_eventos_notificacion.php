<?php

require_once __DIR__ . '/../models/EventoNotificacion.php';
require_once __DIR__ . '/../models/Notificacion.php';
require_once __DIR__ . '/../models/Servicios.php';

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
            'errores' => 0
        ];

        // 1. Generar eventos para los servicios.
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

        // 2. Limpiar eventos pendientes de días anteriores.
        try {
            $eventoModelo->eliminarEventosVencidos();
        } catch (Throwable $e) {
            $resultado['errores']++;

            error_log(
                'Error eliminando eventos vencidos: ' .
                $e->getMessage()
            );
        }

        // 3. Obtener eventos que ya deben procesarse.
        try {
            $eventos = $eventoModelo->obtenerPendientes();
        } catch (Throwable $e) {
            $resultado['errores']++;

            error_log(
                'Error obteniendo eventos pendientes: ' .
                $e->getMessage()
            );

            return $resultado;
        }

        $resultado['eventos_encontrados'] = count($eventos);

        foreach ($eventos as $evento) {
            try {
                $cantidad = (int) $evento['cantidad'];
                $unidad = $evento['unidad'];
                $momento = $evento['momento'];

                // Validar la configuración del recordatorio.
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

                // Crear la notificación.
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

                // Marcar únicamente después de crear la notificación.
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

        return $resultado;
    }
}