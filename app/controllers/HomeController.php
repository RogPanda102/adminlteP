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

        // Año actual
        $anioActual = date('Y');

        // Años disponibles
        $datos['anios'] =
            $modelo->obtenerAnios();

        $datos['anio_actual'] =
            $anioActual;

        // Estadísticas
        $estadisticas =
            $modelo->obtenerEstadisticasPorAnio($anioActual);

        $datos['total_cotizaciones'] =
            $estadisticas['total_cotizaciones'];

        $datos['total_enviadas'] =
            $estadisticas['total_enviadas'];

        $datos['total_respaldo'] =
            $estadisticas['total_respaldo'];

        $datos['total_reenviar'] =
            $estadisticas['total_reenviar'];

        $this->render(
            'home/index',
            $datos
        );
    }
    // ========================
    //  DATOS DE AJAX
    // ========================

    public function estadisticas()
    {
        $modelo = new Cotizacion();

        $anio = $_GET['anio'] ?? date('Y');

        $estadisticas =
            $modelo->obtenerEstadisticasPorAnio($anio);

        header('Content-Type: application/json');

        echo json_encode($estadisticas);

        exit;
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