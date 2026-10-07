<?php

require_once __DIR__ . '/../models/EventoNotificacion.php';
require_once __DIR__ . '/../models/Notificacion.php';

$eventoModelo = new EventoNotificacion();
$notificacionModelo = new Notificacion();

$eventos = $eventoModelo->obtenerPendientes();

if (!$eventos) {
    die("No hay eventos pendientes para procesar.\n");
}

$totalProcesados = 0;
$totalErrores = 0;

foreach ($eventos as $evento) {


    $diasAntes = (int) $evento['dias_antes'];

    if ($diasAntes === 0) {
        $titulo = 'Servicio vence hoy';
        $mensaje = "El servicio {$evento['req']} - {$evento['folio']} vence hoy.";
        $tipo = 'danger';
        $eventoNombre = 'vencimiento_hoy';

    } else {
        $titulo = "Servicio vence en {$diasAntes} días";
        $mensaje = "El servicio {$evento['req']} - {$evento['folio']} vence en {$diasAntes} días.";
        $tipo = 'warning';
        $eventoNombre = "vencimiento_{$diasAntes}";
    }

    $existe = $notificacionModelo->existeEvento(
        $evento['usuario_id'],
        'servicios',
        $evento['servicio_id'],
        $eventoNombre,
        $evento['id']
    );

    if ($existe) {
        echo "  La notificación ya existe. Se marca el evento como enviado.\n";

        $eventoModelo->marcarEnviado($evento['id']);

        continue;
    }

    $resultado = $notificacionModelo->crear([
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

    if ($resultado) {

        $eventoModelo->marcarEnviado($evento['id']);

        $totalProcesados++;

        echo "  Notificación creada correctamente.\n";
        echo "  Evento marcado como enviado.\n";

    } else {

        $totalErrores++;

        echo "  ERROR: no se pudo crear la notificación.\n";
    }
}