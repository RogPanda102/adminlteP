<?php

require_once 'BaseController.php';
require_once __DIR__ . '/../models/Cotizacion.php';

class HomeController extends BaseController
{

    protected $permitido = true;

    // =========================
    // Constructor
    // =========================
    public function __construct()
    {

        // Validar login
        if (!isset($_SESSION['logueado'])) {

            $this->permitido = false;

        }

        // Validar permisos
        if ($this->permitido) {

            if (!comprobar_acceso(TAREA_DASHBOARD)) {

                $this->permitido = false;

            }

        }

    }

    // =========================
    // Dashboard
    // =========================
    public function index()
    {
        $modelo = new Cotizacion();
        if (!$this->permitido) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
        $datos = $this->cargar_datos();
        $datos['total_cotizaciones'] = $modelo->contarTotal();
        $datos['total_enviadas'] = $modelo->contarPorEstatus('enviado');
        $datos['total_respaldo'] = $modelo->contarPorEstatus('respaldo');
        $datos['total_reenviar'] = $modelo->contarReenviar();
        $this->render('home/index', $datos);
        
    }

    // =========================
    // Datos plantilla
    // =========================
    private function cargar_datos()
    {

        $datos = array();

        $datos['nombre_usuario'] = $_SESSION['usuario_nombre'];
        $datos['foto_usuario'] = BASE_URL . 'assets/upload/usuarios/' . $_SESSION['foto_usuario'];
        $datos['tarea'] = 'Dashboard';

        $breadcrumb = array(
            array(
                'tarea' => 'Dashboard',
                'href' => '#'
            )
        );

        $datos['breadcrumb'] =
            breadcrumb($datos['tarea'], $breadcrumb);

        return $datos;

    }

}