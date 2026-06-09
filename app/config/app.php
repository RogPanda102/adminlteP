<?php

define('APP_NAME', 'Administracion de PandaSoft S.A de C.V');

/*
|--------------------------------------------------------------------------
| Rutas físicas
|--------------------------------------------------------------------------
*/

define('ROOT_PATH', dirname(__DIR__, 2));

define('APP_PATH', ROOT_PATH . '/app');

define('CONFIG_PATH', APP_PATH . '/config/');
define('CONTROLLERS_PATH', APP_PATH . '/controllers/');
define('MODELS_PATH', APP_PATH . '/models/');
define('VIEWS_PATH', APP_PATH . '/views/');
define('CORE_PATH', APP_PATH . '/core/');

define('PUBLIC_PATH', ROOT_PATH . '/public/');

/*
|--------------------------------------------------------------------------
| URL Base
|--------------------------------------------------------------------------
*/

$protocol =
(
    isset($_SERVER['HTTPS'])
    &&
    $_SERVER['HTTPS'] !== 'off'
)
? 'https://'
: 'http://';

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
define('BASE_URL', $protocol . $host . $base . '/');