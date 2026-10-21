<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

/*
|--------------------------------------------------------------------------
| CONTROL DE SESIÓN POR INACTIVIDAD
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['logueado'])) {

    $tiempoInactividad = 600; // 10 minutos

    $ultimaActividad =
        $_SESSION['ultima_actividad'] ?? time();

    if ((time() - $ultimaActividad) >= $tiempoInactividad) {

        // Guardar mensaje antes de destruir la sesión
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        // Crear una nueva sesión para informar que expiró
        session_start();

        $_SESSION['sesion_expirada'] = true;

    } else {

        // Actualizar la última actividad registrada
        $_SESSION['ultima_actividad'] = time();

    }
}

/*
|--------------------------------------------------------------------------
| Configuraciones
|--------------------------------------------------------------------------
*/

require_once dirname(__DIR__) . '/app/config/app.php';

/*
|--------------------------------------------------------------------------
| Core
|--------------------------------------------------------------------------
*/

require_once CORE_PATH . '/router.php';

/*
|--------------------------------------------------------------------------
| Constantes
|--------------------------------------------------------------------------
*/

require_once CONFIG_PATH . '/constantes.php';

/*
|--------------------------------------------------------------------------
| Rutas
|--------------------------------------------------------------------------
*/

require_once ROOT_PATH . '/routes.php';