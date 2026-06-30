<?php

$router = new Router();

/*
|--------------------------------------------------------------------------
| AUTH LOGIN REGISTRO
|--------------------------------------------------------------------------
*/

$router->get('/', 'auth\AuthController@login');

$router->get('/login', 'auth\AuthController@login');

$router->post('/login', 'auth\AuthController@autenticar');

$router->get('/logout', 'auth\AuthController@logout');

$router->get('/registro', 'auth\AuthController@registro');

$router->post('/registro', 'auth\AuthController@guardarRegistro');

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

$router->get(
    '/cotizaciones/buscar',
    'operaciones\CotizacionesController@buscarAjax'
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

$router->get(
    '/adjudicados/2025',
    'operaciones\AdjudicadosController@adjudicados2025'
);

$router->get(
    '/historial/adjudicados',
    'operaciones\HistorialController@adjudicados'
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
    'operaciones\AdjudicadosController@guardar'
);

$router->get(
    '/adjudicados/buscar-dependencia',
    'operaciones\AdjudicadosController@buscarDependenciaAjax'
);

$router->post(
    '/adjudicados/update',
    'operaciones\AdjudicadosController@update'
);
/*
|------------------------------------------------------------------
| OPERACIONES - SERVICIOS
|------------------------------------------------------------------
*/

$router->get(
    '/servicios/2025',
    'operaciones\ServiciosController@servicios2025'
);

$router->get(
    '/servicios/2026',
    'operaciones\ServiciosController@servicios2026'
);

/*
|------------------------------------------------------------------
| OPERACIONES - SERVICIOS / FORMULARIO / GUARDAR FORMULARIO
|------------------------------------------------------------------
*/

$router->get(
    '/servicios/nueva',
    'operaciones\ServiciosController@nueva'
);

$router->post(
    '/servicios/guardar',
    'operaciones\ServiciosController@guardar'
);


$router->get(
    '/servicios/buscar',
    'operaciones\ServiciosController@buscar'
);

$router->get(
    '/servicios/buscar-dependencia',
    'operaciones\ServiciosController@buscarDependencia'
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

/*
|------------------------------------------------------------------
| CATALOGO - CONTACTO
|------------------------------------------------------------------
*/

$router->get(
    '/contactos',
    'operaciones\ContactosController@index'
);

$router->get(
    '/contactos/nueva',
    'operaciones\ContactosController@nueva'
);

$router->post(
    '/contactos/guardarAnalista',
    'operaciones\ContactosController@guardarAnalista'
);

$router->post(
    '/contactos/guardarEncargado',
    'operaciones\ContactosController@guardarEncargado'
);

/*
|------------------------------------------------------------------
| ADMINISTRACION - PERFIL
|------------------------------------------------------------------
*/

$router->get(
    '/perfil',
    'operaciones\UsuarioController@perfil'
);
$router->post(
    '/perfil/password',
    'operaciones\UsuarioController@actualizarPassword'
);
/*
|--------------------------------------------------------------------------
| EJECUTAR ROUTER
|--------------------------------------------------------------------------
*/

$router->dispatch();