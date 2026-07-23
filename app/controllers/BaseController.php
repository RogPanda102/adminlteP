<?php

require_once CORE_PATH . '/view.php';
require_once CONFIG_PATH . '/database.php';
require_once APP_PATH . '/helpers/funciones_globales.php';
require_once APP_PATH . '/helpers/permisos.php';
require_once APP_PATH . '/helpers/historial.php';
require_once APP_PATH . '/helpers/menu.php';
require_once APP_PATH . '/helpers/formularios.php';
require_once APP_PATH . '/helpers/notificaciones.php';

class BaseController
{
    protected $conexion;
    protected $permitido = true;

    public function __construct()
    {
        // Conexion BD
        $this->conexion = Database::connect();

        // Iniciar sesión
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Renderizar vistas
    protected function render($vista, $datos = [])
    {
        $datos['menu'] = crear_menu_panel();
        View::render(
            $vista,
            $datos
        );

    }

}