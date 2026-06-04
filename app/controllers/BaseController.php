<?php

require_once '../app/core/View.php';
require_once '../app/config/database.php';
require_once '../app/helpers/funciones_globales.php';
require_once __DIR__ . '/../helpers/permisos.php';

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