<?php

require_once 'BaseController.php';
require_once __DIR__ . '/../models/Cotizacion.php';
require_once __DIR__ . '/../models/Dashboard.php';

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

        $modeloDashboard = new Dashboard();

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

        // Estadísticas de cotizaciones
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

        // =========================
        // Dashboard General
        // =========================

        $datos['dashboard'] =
            $modeloDashboard->obtenerResumen(
                $anioActual
            );

        // =========================
        // Rankings
        // =========================

        $datos['top_analistas_adjudicados'] =
            $modeloDashboard->obtenerRanking(
                'adjudicados',
                'analista',
                $anioActual,
                10
            );

        $datos['top_dependencias_adjudicados'] =
            $modeloDashboard->obtenerRanking(
                'adjudicados',
                'dependencia',
                $anioActual,
                10
            );

        $datos['top_analistas_cotizaciones'] =
            $modeloDashboard->obtenerRanking(
                'cotizaciones',
                'analista',
                $anioActual,
                10
            );

        $datos['top_dependencias_cotizaciones'] =
            $modeloDashboard->obtenerRanking(
                'cotizaciones',
                'dependencia',
                $anioActual,
                10
            );




        $this->render(
            'home/index',
            $datos
        );
    }

    // ========================
    // DATOS DE AJAX
    // ========================

    public function estadisticas()
    {
        $modelo = new Cotizacion();

        $modeloDashboard = new Dashboard();

        $anio = $_GET['anio'] ?? date('Y');

        $estadisticas =
            $modelo->obtenerEstadisticasPorAnio($anio);

        // =========================
        // Dashboard General
        // =========================

        $estadisticas['dashboard'] =
            $modeloDashboard->obtenerResumen(
                $anio
            );

        // =========================
        // Rankings
        // =========================

        $estadisticas['top_analistas_adjudicados'] =
            $modeloDashboard->obtenerRanking(
                'adjudicados',
                'analista',
                $anio,
                10
            );

        $estadisticas['top_dependencias_adjudicados'] =
            $modeloDashboard->obtenerRanking(
                'adjudicados',
                'dependencia',
                $anio,
                10
            );

        $estadisticas['top_analistas_cotizaciones'] =
            $modeloDashboard->obtenerRanking(
                'cotizaciones',
                'analista',
                $anio,
                10
            );

        $estadisticas['top_dependencias_cotizaciones'] =
            $modeloDashboard->obtenerRanking(
                'cotizaciones',
                'dependencia',
                $anio,
                10
            );

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

        if (!empty($_SESSION['foto_usuario'])) {
            $datos['foto_usuario'] =
                BASE_URL . 'assets/upload/usuarios/' . $_SESSION['foto_usuario'];
        } else {
            $datos['foto_usuario'] =
                BASE_URL . 'assets/upload/usuarios/default.webp';
        }

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
