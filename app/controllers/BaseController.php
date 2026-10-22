<?php

require_once CORE_PATH . '/view.php';
require_once CONFIG_PATH . '/database.php';
require_once APP_PATH . '/helpers/funciones_globales.php';
require_once APP_PATH . '/helpers/permisos.php';
require_once APP_PATH . '/helpers/historial.php';
require_once APP_PATH . '/helpers/menu.php';
require_once APP_PATH . '/helpers/formularios.php';
require_once APP_PATH . '/helpers/notificaciones.php';
require_once APP_PATH . '/helpers/modales_modulos.php';
require_once APP_PATH . '/helpers/busqueda_global.php';

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
        // Procesar recordatorios solo para usuarios autenticados.
        if (isset($_SESSION['logueado'])) {
            try {
                require_once __DIR__ . '/../tareas/CoordinadorNotificaciones.php';

                $coordinador = new CoordinadorNotificaciones();
                $coordinador->ejecutar();

            } catch (Throwable $e) {
                // Un error en los recordatorios no debe impedir
                // que se muestre la página del usuario.
                error_log(
                    'Error en el coordinador de notificaciones: ' .
                    $e->getMessage()
                );
            }
        }

        $datos['menu'] = crear_menu_panel();
        $datos['navbarNotificaciones'] =
        obtenerNotificacionesNavbar();

        View::render(
            $vista,
            $datos
        );

    }

}