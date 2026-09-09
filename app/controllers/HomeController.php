<?php

require_once 'BaseController.php';

require_once __DIR__ . '/../models/Cotizacion.php';
require_once __DIR__ . '/../models/Adjudicados.php';
require_once __DIR__ . '/../models/Servicios.php';
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

        if (!$this->permitido) {

            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        // =========================
        // Modelos
        // =========================

        $modeloCotizacion = new Cotizacion();

        $modeloAdjudicado = new Adjudicados();

        $modeloServicio = new Servicio();

        $modeloDashboard = new Dashboard();


        // =========================
        // Datos base
        // =========================

        $datos = $this->cargar_datos();

        $anioActual = date('Y');

        $moduloActual = 'cotizaciones';


        // =========================
        // MÓDULOS DISPONIBLES
        // =========================

        $datos['modulos'] = [

            'cotizaciones' => 'Cotizaciones',

            'adjudicados' => 'Adjudicados',

            'servicios' => 'Servicios'

        ];

        $datos['modulo_actual'] = $moduloActual;


        // =========================
        // AÑOS POR MÓDULO
        // =========================

        $aniosCotizaciones =
            $modeloCotizacion->obtenerAnios();

        $aniosAdjudicados =
            $modeloAdjudicado->obtenerAnios();

        $aniosServicios =
            $modeloServicio->obtenerAnios();


        // =========================
        // UNIFICAR AÑOS
        // =========================

        $aniosUnificados = [];

        foreach ($aniosCotizaciones as $item) {

            $aniosUnificados[] =
                (int) $item['anio'];
        }

        foreach ($aniosAdjudicados as $item) {

            $aniosUnificados[] =
                (int) $item['anio'];
        }

        foreach ($aniosServicios as $item) {

            $aniosUnificados[] =
                (int) $item['anio'];
        }


        // Eliminar duplicados
        $aniosUnificados =
            array_unique($aniosUnificados);


        // Ordenar de mayor a menor
        rsort($aniosUnificados);


        // Convertir al mismo formato que espera la vista
        $datos['anios'] = [];

        foreach ($aniosUnificados as $anio) {

            $datos['anios'][] = [

                'anio' => $anio

            ];
        }


        $datos['anio_actual'] =
            $anioActual;


        // =========================
        // ESTADÍSTICAS INICIALES
        // MÓDULO: COTIZACIONES
        // =========================

        $estadisticasCotizaciones =
            $modeloCotizacion->obtenerEstadisticasPorAnio(
                $anioActual
            );


        $datos['total_cotizaciones'] =
            $estadisticasCotizaciones['total_cotizaciones'] ?? 0;

        $datos['total_enviadas'] =
            $estadisticasCotizaciones['total_enviadas'] ?? 0;

        $datos['total_respaldo'] =
            $estadisticasCotizaciones['total_respaldo'] ?? 0;

        $datos['total_reenviar'] =
            $estadisticasCotizaciones['total_reenviar'] ?? 0;


        // =========================
        // RESUMEN GENERAL
        // =========================

        $datos['dashboard'] =
            $modeloDashboard->obtenerResumen(
                $anioActual
            );


        // =========================
        // RANKINGS
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
        // =========================
        // RANKINGS DE SERVICIOS
        // =========================
        $datos['top_analistas_servicios'] =
            $modeloDashboard->obtenerRanking(
                'servicios',
                'analista',
                $anioActual,
                10
            );
        $datos['top_dependencias_servicios'] =
            $modeloDashboard->obtenerRanking(
                'servicios',
                'dependencia',
                $anioActual,
                10
            );
        // =========================
        // RENDER
        // =========================
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

        $modeloCotizacion =
            new Cotizacion();

        $modeloAdjudicado =
            new Adjudicados();

        $modeloServicio =
            new Servicio();

        $modeloDashboard =
            new Dashboard();


        // =========================
        // PARÁMETROS
        // =========================

        $anio =
            isset($_GET['anio'])
                ? (int) $_GET['anio']
                : (int) date('Y');


        $modulo =
            $_GET['modulo']
            ?? 'cotizaciones';


        // =========================
        // VALIDAR MÓDULO
        // =========================

        $modulosPermitidos = [

            'cotizaciones',

            'adjudicados',

            'servicios'

        ];


        if (!in_array($modulo, $modulosPermitidos, true)) {

            $modulo =
                'cotizaciones';
        }


        // =========================
        // RESPUESTA BASE
        // =========================

        $estadisticas = [

            'modulo' => $modulo,

            'anio' => $anio,

            'total_cotizaciones' => 0,

            'total_enviadas' => 0,

            'total_respaldo' => 0,

            'total_reenviar' => 0,

            'total_adjudicados' => 0,

            'total_servicios' => 0

        ];


        // =========================
        // COTIZACIONES
        // =========================

        if ($modulo === 'cotizaciones') {

            $resultado =
                $modeloCotizacion
                    ->obtenerEstadisticasPorAnio($anio);


            $estadisticas['total_cotizaciones'] =
                $resultado['total_cotizaciones'] ?? 0;


            $estadisticas['total_enviadas'] =
                $resultado['total_enviadas'] ?? 0;


            $estadisticas['total_respaldo'] =
                $resultado['total_respaldo'] ?? 0;


            $estadisticas['total_reenviar'] =
                $resultado['total_reenviar'] ?? 0;
        }


        // =========================
        // ADJUDICADOS
        // =========================

        if ($modulo === 'adjudicados') {

            $resultado =
                $modeloAdjudicado
                    ->obtenerEstadisticasPorAnio($anio);


            $estadisticas['total_adjudicados'] =
                $resultado['adjudicados'] ?? 0;
        }


        // =========================
        // SERVICIOS
        // =========================

        if ($modulo === 'servicios') {

            $resultado =
                $modeloServicio
                    ->obtenerEstadisticasPorAnio($anio);


            $estadisticas['total_servicios'] =
                $resultado['total_servicios'] ?? 0;
        }


        // =========================
        // DASHBOARD GENERAL
        // =========================

        $estadisticas['dashboard'] =
            $modeloDashboard
                ->obtenerResumen($anio);


        // =========================
        // RANKINGS
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


        $estadisticas['top_analistas_servicios'] =
            $modeloDashboard->obtenerRanking(
                'servicios',
                'analista',
                $anio,
                10
            );


        $estadisticas['top_dependencias_servicios'] =
            $modeloDashboard->obtenerRanking(
                'servicios',
                'dependencia',
                $anio,
                10
            );


        // =========================
        // JSON
        // =========================

        header(
            'Content-Type: application/json'
        );


        echo json_encode(
            $estadisticas
        );


        exit;
    }


    // =========================
    // Datos plantilla
    // =========================
    private function cargar_datos()
    {

        $datos = array();


        $datos['nombre_usuario'] =
            $_SESSION['usuario_nombre'];


        if (!empty($_SESSION['foto_usuario'])) {

            $datos['foto_usuario'] =
                BASE_URL .
                'assets/upload/usuarios/' .
                $_SESSION['foto_usuario'];

        } else {

            $datos['foto_usuario'] =
                BASE_URL .
                'assets/upload/usuarios/default.webp';
        }


        $datos['tarea'] =
            'Dashboard';


        $breadcrumb = array(

            array(

                'tarea' => 'Dashboard',

                'href' => '#'

            )

        );


        $datos['breadcrumb'] =
            breadcrumb(
                $datos['tarea'],
                $breadcrumb
            );


        return $datos;
    }
}