<?php

require_once __DIR__ . '/../models/Servicios.php';
require_once __DIR__ . '/../models/Notificacion.php';

$modeloServicio = new Servicio();
$modeloNotificacion = new Notificacion();

$servicios =
    $modeloServicio->obtenerServiciosParaNotificaciones();

$hoy = new DateTime();

$generadas = 0;

foreach ($servicios as $servicio) {

    if (empty($servicio['finalizacion'])) {
        continue;
    }

    try {

        $fechaFinalizacion =
            new DateTime($servicio['finalizacion']);

    } catch (Exception $e) {

        continue;

    }

    // ==========================================
    // Calcular días restantes
    // ==========================================

    $hoySinHora =
        new DateTime($hoy->format('Y-m-d'));

    $fechaFinalSinHora =
        new DateTime(
            $fechaFinalizacion->format('Y-m-d')
        );

    $diferencia =
        $hoySinHora->diff($fechaFinalSinHora);

    $diasRestantes =
        (int)$diferencia->format('%r%a');

    // ==========================================
    // Determinar evento
    // ==========================================

    switch ($diasRestantes) {

        case 90:

            $evento = 'vencimiento_90';

            $titulo =
                'Servicio próximo a vencer';

            $mensaje =
                'El servicio ' .
                ($servicio['req'] ?: $servicio['folio']) .
                ' vence en 90 días.';

            $tipo = 'info';

            break;


        case 30:

            $evento = 'vencimiento_30';

            $titulo =
                'Servicio próximo a vencer';

            $mensaje =
                'El servicio ' .
                ($servicio['req'] ?: $servicio['folio']) .
                ' vence en 30 días.';

            $tipo = 'info';

            break;


        case 7:

            $evento = 'vencimiento_7';

            $titulo =
                'Servicio próximo a vencer';

            $mensaje =
                'El servicio ' .
                ($servicio['req'] ?: $servicio['folio']) .
                ' vence en 7 días.';

            $tipo = 'warning';

            break;


        case 0:

            $evento = 'vencimiento_hoy';

            $titulo =
                'Servicio vence hoy';

            $mensaje =
                'El servicio ' .
                ($servicio['req'] ?: $servicio['folio']) .
                ' vence hoy.';

            $tipo = 'danger';

            break;


        default:

            continue 2;
    }

    // ==========================================
    // Verificar duplicado
    // ==========================================

    $yaExiste =
        $modeloNotificacion->existeEvento(

            $servicio['creado_por'],

            'servicios',

            $servicio['id'],

            $evento

        );

    if ($yaExiste) {

        continue;

    }

    // ==========================================
    // Crear notificación
    // ==========================================

    $resultado =
        $modeloNotificacion->crear([

            'usuario_id' =>
                $servicio['creado_por'],

            'titulo' =>
                $titulo,

            'mensaje' =>
                $mensaje,

            'url' =>
                '/servicios/2026',

            'tipo' =>
                $tipo,

            'modulo' =>
                'servicios',

            'registro_id' =>
                $servicio['id'],

            'evento' =>
                $evento

        ]);

    if ($resultado) {

        $generadas++;

    }

}

// ==========================================
// Resultado
// ==========================================

echo "Detector ejecutado.\n";
echo "Notificaciones generadas: {$generadas}\n";