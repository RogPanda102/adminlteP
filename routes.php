<?php

$router = new Router();

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

$router->get('/', 'auth\AuthController@login');

$router->get('/login', 'auth\AuthController@login');

$router->post('/login', 'auth\AuthController@autenticar');

$router->get('/logout', 'auth\AuthController@logout');

/*
|--------------------------------------------------------------------------
| PANEL
|--------------------------------------------------------------------------
*/

$router->get('/dashboard', 'HomeController@index');

/*
|------------------------------------------------------------------
| OPERACIONES - COTIZACIONES
|------------------------------------------------------------------
*/

$router->get(
    '/cotizaciones/2025',
    'operaciones\CotizacionesController@cotizaciones2025'
);

$router->get(
    '/cotizaciones/2026',
    'operaciones\CotizacionesController@cotizaciones2026'
);

/*
|------------------------------------------------------------------
| OPERACIONES - COTIZACIONES / FORMULARIO / GUARDAR FORMULARIO
|------------------------------------------------------------------
*/

$router->get(
    '/cotizaciones/nueva',
    'operaciones\CotizacionesController@nueva'
);

$router->post(
    '/cotizaciones/guardar',
    'operaciones\CotizacionesController@guardar'
);

/*
|--------------------------------------------------------------------------
| EJECUTAR ROUTER
|--------------------------------------------------------------------------
*/


$router->dispatch();