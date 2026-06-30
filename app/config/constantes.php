<?php

/*
|--------------------------------------------------------------------------
| ALERTAS TOASTR
|--------------------------------------------------------------------------
*/

define("ALERT_SUCCESS", 1);
define("ALERT_DANGER", 2);
define("ALERT_WARNING", 3);
define("ALERT_INFO", 4);

/*
|--------------------------------------------------------------------------
| ROLES
|--------------------------------------------------------------------------
*/

define("ROL_ADMINISTRADOR", [
    'clave' => 1,
    'rol'   => 'Administrador'
]);

define("ROL_USUARIO", [
    'clave' => 2,
    'rol'   => 'Usuario'
]);

define("ROLES", [
    ROL_ADMINISTRADOR['clave'] => ROL_ADMINISTRADOR['rol'],
    ROL_USUARIO['clave']       => ROL_USUARIO['rol']
]);

/*
|--------------------------------------------------------------------------
| ESTATUS
|--------------------------------------------------------------------------
*/

define("ESTATUS_HABILITADO", 1);
define("ESTATUS_DESHABILITADO", 2);

/*
|--------------------------------------------------------------------------
| TAREAS DEL SISTEMA
|--------------------------------------------------------------------------
*/

define("TAREA_DASHBOARD", 'tarea_dashboard');
define("TAREA_PERFIL", 'tarea_perfil');

define("TAREA_USUARIOS", 'tarea_usuarios');
define("TAREA_USUARIO_NUEVO", 'tarea_usuario_nuevo');
define("TAREA_USUARIO_DETALLES", 'tarea_usuario_detalles');

define("TAREA_COTIZACIONES", 'tarea_cotizaciones');
define("TAREA_ADJUDICADOS", 'tarea_adjudicados');
define("TAREA_SERVICIOS", 'tarea_servicios');

define("TAREA_PROVEEDORES", 'tarea_proveedores');
define("TAREA_CONTACTOS", 'tarea_contactos');

/*
|--------------------------------------------------------------------------
| ACCESOS POR ROL
|--------------------------------------------------------------------------
*/

define("ACCESO_ADMINISTRADOR", [
    TAREA_DASHBOARD,
    TAREA_PERFIL,
    TAREA_USUARIOS,
    TAREA_USUARIO_NUEVO,
    TAREA_USUARIO_DETALLES,
    TAREA_COTIZACIONES,
    TAREA_ADJUDICADOS,
    TAREA_SERVICIOS,
    TAREA_PROVEEDORES,
    TAREA_CONTACTOS
]);

define("ACCESO_USUARIO", [
    TAREA_DASHBOARD
]);

/*
|--------------------------------------------------------------------------
| SEXOS
|--------------------------------------------------------------------------
*/

define('SEXO_MASCULINO', [
    'clave' => '1',
    'sexo'  => 'Masculino'
]);

define('SEXO_FEMENINO', [
    'clave' => '2',
    'sexo'  => 'Femenino'
]);

/*
|--------------------------------------------------------------------------
| RUTAS DE ARCHIVOS
|--------------------------------------------------------------------------
*/

define("RECURSOS_USUARIOS", "uploads/usuarios/");



/*
|--------------------------------------------------------------------------
| NOMBRE DEL SISTEMA
|--------------------------------------------------------------------------
*/

define('NOMBRE_SISTEMA', 'Administración de Pandasoft');
define('NOMBRE_EMPRESA', 'Pandasoft');