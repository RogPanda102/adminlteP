<?php

require_once __DIR__ . '/../models/EventoNotificacion.php';
require_once __DIR__ . '/../models/Notificacion.php';
require_once __DIR__ . '/../models/Servicios.php';

$eventoModelo = new EventoNotificacion();
$notificacionModelo = new Notificacion();
$servicioModelo = new Servicio();



// =========================================
// GENERAR EVENTOS PARA TODOS LOS SERVICIOS
// =========================================

$servicios = $servicioModelo->obtenerServiciosParaNotificaciones();

$totalServiciosRevisados = 0;

foreach ($servicios as $servicio) {

    $resultado = $eventoModelo->generarParaServicio(
        (int) $servicio['id']
    );

    if ($resultado) {
        $totalServiciosRevisados++;
    }
}

echo "Servicios revisados: {$totalServiciosRevisados}\n";

// =========================================
// ELIMINAR EVENTOS CUYA HORA YA PASÓ
// =========================================

$eventoModelo->eliminarEventosVencidos();

// =========================================
// OBTENER EVENTOS LISTOS PARA PROCESAR
// =========================================

$eventos = $eventoModelo->obtenerPendientes();

if (!$eventos) {
    die("No hay eventos pendientes para procesar.\n");
}

$totalProcesados = 0;
$totalErrores = 0;

if (!$eventos) {
    die("No hay eventos pendientes para procesar.\n");
}

$totalProcesados = 0;
$totalErrores = 0;


foreach ($eventos as $evento) {

    $cantidad = (int)$evento['cantidad'];
    $unidad = $evento['unidad'];
    $momento = $evento['momento'];

    // Validar los datos del recordatorio
    if (
        $cantidad < 0 ||
        !in_array($unidad, ['dias', 'meses'], true) ||
        !in_array($momento, ['antes', 'despues'], true)
    ) {
        $totalErrores++;
        echo "  ERROR: configuración de recordatorio no válida. Evento ID: {$evento['id']}\n";
        continue;
    }

    $unidadTexto = ($unidad === 'meses')
        ? ($cantidad === 1 ? 'mes' : 'meses')
        : ($cantidad === 1 ? 'día' : 'días');

    $referenciaServicio =
        "El servicio {$evento['req']} - {$evento['folio']}";

    // Variables de la notificación
    $eventoNombreAnterior = null;

    // =========================================
    // AVISO EL DÍA DEL VENCIMIENTO
    // =========================================
    if ($momento === 'antes' && $cantidad === 0) {

        $titulo = 'Servicio vence hoy';
        $mensaje = "{$referenciaServicio} vence hoy.";
        $tipo = 'danger';
        $eventoNombre = 'vencimiento_hoy';

        // =========================================
        // AVISO ANTES DEL VENCIMIENTO
        // =========================================
    } elseif ($momento === 'antes') {

        $titulo = "Servicio vence en {$cantidad} {$unidadTexto}";
        $mensaje = "{$referenciaServicio} vence en {$cantidad} {$unidadTexto}.";
        $tipo = 'warning';

        $eventoNombre =
            "vencimiento_antes_{$cantidad}_{$unidad}";

        // Compatibilidad con nombres de eventos anteriores
        if ($unidad === 'dias') {
            $eventoNombreAnterior = "vencimiento_{$cantidad}";
        } elseif ($cantidad === 3 && $unidad === 'meses') {
            // Antes, este recordatorio se identificaba como 90 días
            $eventoNombreAnterior = 'vencimiento_90';
        }

        // =========================================
        // AVISO DESPUÉS DEL VENCIMIENTO
        // =========================================
    } else {

        $titulo = "Servicio vencido hace {$cantidad} {$unidadTexto}";
        $mensaje = "{$referenciaServicio} venció hace {$cantidad} {$unidadTexto}.";
        $tipo = 'danger';

        $eventoNombre =
            "vencimiento_despues_{$cantidad}_{$unidad}";
    }

    // =========================================
    // EVITAR NOTIFICACIONES DUPLICADAS
    // =========================================
    $existe = $notificacionModelo->existeEvento(
        $evento['usuario_id'],
        'servicios',
        $evento['servicio_id'],
        $eventoNombre,
        $evento['id']
    );

    // Comprobar también el nombre antiguo, si aplica
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

        echo "  La notificación ya existe. Se marca el evento como enviado.\n";

        $eventoModelo->marcarEnviado($evento['id']);

        continue;
    }

    // =========================================
    // CREAR NOTIFICACIÓN
    // =========================================
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
