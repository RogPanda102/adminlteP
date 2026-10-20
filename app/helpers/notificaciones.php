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
    $modulo = null,
    $registroId = null,
    $evento = null,
    $eventoNotificacionId = null
    ) 
    {

        if (
            $usuarioId === null &&
            isset($_SESSION['usuario_id'])
        ) {

            $usuarioId = $_SESSION['usuario_id'];

        }

        if ($usuarioId === null) {

            return false;

        }

        $notificacion = new Notificacion();

        return $notificacion->crear([

            'usuario_id' => $usuarioId,

            'titulo' => $titulo,

            'mensaje' => $mensaje,

            'url' => $url,

            'tipo' => $tipo,

            'modulo' => $modulo ?? null,
            'registro_id' => $registroId ?? null,
            'evento' => $evento ?? null,
            'evento_notificacion_id' => $eventoNotificacionId ?? null

        ]);
}
function notificacionIcono($tipo)
{

    switch ($tipo) {

        case 'success':
            return 'bi bi-check-circle-fill';

        case 'warning':
            return 'bi bi-exclamation-triangle-fill';

        case 'danger':
            return 'bi bi-x-circle-fill';

        case 'info':
        default:
            return 'bi bi-info-circle-fill';

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
    $url = '',
    $modulo = null,
    $registroId = null,
    $evento = null,
    $eventoNotificacionId = null
) {

    return notificar(
        $usuarioId,
        $titulo,
        $mensaje,
        $url,
        'success',
        $modulo,
        $registroId,
        $evento,
        $eventoNotificacionId
    );

}
function notificarInfo(
    $usuarioId = null,
    $titulo = '',
    $mensaje = '',
    $url = '',
    $modulo = null,
    $registroId = null,
    $evento = null,
    $eventoNotificacionId = null
) {

    return notificar(
        $usuarioId,
        $titulo,
        $mensaje,
        $url,
        'info',
        $modulo,
        $registroId,
        $evento,
        $eventoNotificacionId
    );

}
function notificarAdvertencia(
    $usuarioId = null,
    $titulo = '',
    $mensaje = '',
    $url = '',
    $modulo = null,
    $registroId = null,
    $evento = null,
    $eventoNotificacionId = null
) {

    return notificar(
        $usuarioId,
        $titulo,
        $mensaje,
        $url,
        'warning',
        $modulo,
        $registroId,
        $evento,
        $eventoNotificacionId
    );

}
function notificarError(
    $usuarioId = null,
    $titulo = '',
    $mensaje = '',
    $url = '',
    $modulo = null,
    $registroId = null,
    $evento = null,
    $eventoNotificacionId = null
) {

    return notificar(
        $usuarioId,
        $titulo,
        $mensaje,
        $url,
        'danger',
        $modulo,
        $registroId,
        $evento,
        $eventoNotificacionId
    );

}
// =========================
// Notificaciones Navbar
// =========================
function obtenerNotificacionesNavbar(
    $limite = 5
) {

    if (
        empty($_SESSION['usuario_id'])
    ) {

        return [

            'total' => 0,

            'notificaciones' => []

        ];

    }

    $modelo = new Notificacion();

    $total = $modelo->contarNoLeidas(
        $_SESSION['usuario_id']
    );

    return [

        'total' => (int)$total['total'],

        'notificaciones' => $modelo->obtenerParaNavbar(
            $_SESSION['usuario_id'],
            $limite
        )

    ];

}
