<?php

require_once CORE_PATH . '/view.php';
require_once CONFIG_PATH . '/database.php';
require_once APP_PATH . '/helpers/funciones_globales.php';
require_once APP_PATH . '/helpers/permisos.php';

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
        View::render($vista, $datos);
    }
}