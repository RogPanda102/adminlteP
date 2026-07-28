<?php

require_once APP_PATH . '/models/Notificacion.php';

/**
 * Crear una notificación
 *
 * @param int    $usuarioId
 * @param string $titulo
 * @param string $mensaje
 * @param string $url
 * @param string $tipo
 *
 * @return bool
 */
function notificar(
    $usuarioId = null,
    $titulo = '',
    $mensaje = '',
    $url = '',
    $tipo = 'info',
) {

    if ($usuarioId === null) {

        $usuarioId = $_SESSION['usuario_id'];
    }

    $notificacion = new Notificacion();

    return $notificacion->crear([

        'usuario_id' => $usuarioId,

        'titulo' => $titulo,

        'mensaje' => $mensaje,

        'url' => $url,

        'tipo' => $tipo

    ]);
}
function notificacionIcono($tipo)
{

    switch ($tipo) {

        case 'success':
            return 'fas fa-circle-check';

        case 'warning':
            return 'fas fa-triangle-exclamation';

        case 'danger':
            return 'fas fa-circle-xmark';

        default:
            return 'fas fa-circle-info';

    }

}
function notificacionColor($tipo)
{

    switch ($tipo) {

        case 'success':
            return 'success';

        case 'warning':
            return 'warning';

        case 'danger':
            return 'danger';

        default:
            return 'info';

    }

}
function notificacionFecha($fecha)
{

    $fecha = new DateTime($fecha);
    $ahora = new DateTime();

    $diferencia = $ahora->getTimestamp() - $fecha->getTimestamp();

    if ($diferencia < 60) {
        return 'Hace un momento';
    }

    if ($diferencia < 3600) {
        return 'Hace ' . floor($diferencia / 60) . ' minutos';
    }

    if ($diferencia < 86400) {
        return 'Hace ' . floor($diferencia / 3600) . ' horas';
    }

    if ($diferencia < 172800) {
        return 'Ayer';
    }

    return $fecha->format('d/m/Y H:i');

}

function notificarExito(
    $usuarioId = null,
    $titulo = '',
    $mensaje = '',
    $url = ''
) {

    return notificar(
        $usuarioId,
        $titulo,
        $mensaje,
        $url,
        'success'
    );

}
function notificarInfo(
    $usuarioId = null,
    $titulo = '',
    $mensaje = '',
    $url = ''
) {

    return notificar(
        $usuarioId,
        $titulo,
        $mensaje,
        $url,
        'info'
    );

}
function notificarAdvertencia(
    $usuarioId = null,
    $titulo = '',
    $mensaje = '',
    $url = ''
) {

    return notificar(
        $usuarioId,
        $titulo,
        $mensaje,
        $url,
        'warning'
    );

}
function notificarError(
    $usuarioId = null,
    $titulo = '',
    $mensaje = '',
    $url = ''
) {

    return notificar(
        $usuarioId,
        $titulo,
        $mensaje,
        $url,
        'danger'
    );

}

