<?php

$router = new Router();

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

$router->get('/', 'Auth\AuthController@login');

$router->get('/login', 'Auth\AuthController@login');

$router->post('/login', 'Auth\AuthController@autenticar');

$router->get('/logout', 'Auth\AuthController@logout');

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