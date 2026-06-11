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
|------------------------------------------------------------------
| OPERACIONES - ADJUDICADOS 2026 - 2025
|------------------------------------------------------------------
*/

$router->get(
    '/adjudicados/2026',
    'operaciones\AdjudicadosController@adjudicados2026'
);


/*
|------------------------------------------------------------------
| OPERACIONES - ADJUDICADOS / FORMULARIO / GUARDAR FORMULARIO
|------------------------------------------------------------------
*/

$router->get(
    '/adjudicados/nueva',
    'operaciones\AdjudicadosController@nueva'
);

$router->post(
    '/adjudicados/guardar',
    'operaciones\AdjudicadosController@adjudicados202'
);



/*
|------------------------------------------------------------------
| DATOS AJAX / DASHBOARD
|------------------------------------------------------------------
*/

$router->get(
    '/dashboard/estadisticas',
    'HomeController@estadisticas'
);

/*
|--------------------------------------------------------------------------
| EJECUTAR ROUTER
|--------------------------------------------------------------------------
*/

/*
|------------------------------------------------------------------
| CATALOGO - PROVEEDORES
|------------------------------------------------------------------
*/

$router->get(
    '/proveedores',
    'operaciones\ProveedoresController@index'
);

$router->get(
    '/proveedores/nueva',
    'operaciones\ProveedoresController@nueva'
);

$router->post(
    '/proveedores/guardar',
    'operaciones\ProveedoresController@guardar'
);


$router->dispatch();