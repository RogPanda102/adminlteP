<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/Cotizacion.php';

class CotizacionesController extends BaseController
{
    protected $permitido = true;
    // =========================
    // Constructor
    // =========================
    public function __construct()
    {
        if (!isset($_SESSION['logueado'])) {
            $this->permitido = false;
        }
    }
    // =========================
    // Datos plantilla
    // =========================
    private function cargar_datos()
    {
        $datos = array();
        // =========================
        // USUARIO
        // =========================
        $datos['nombre_usuario'] = $_SESSION['usuario_nombre'];
        $datos['foto_usuario'] = BASE_URL . 'assets/upload/usuarios/' . $_SESSION['foto_usuario'];
        // =========================
        // MODULO
        // =========================
        $datos['nombre_pagina'] = 'Cotizaciones 2026';
        $datos['tarea'] = 'Cotizaciones';
        // =========================
        // BREADCRUMB
        // =========================
        $breadcrumb = array(
            array(
                'tarea' => 'Cotizaciones',
                'href'  => '#'
            ),
                    array(
                'tarea' => '2026',
                'href'  => '#'
            )
        );
        $datos['breadcrumb'] = breadcrumb( $datos['tarea'], $breadcrumb );
        return $datos;

    }

    // =========================
    // Vista 2025
    // =========================
    public function cotizaciones2025()
    {

        if (!$this->permitido) {

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        // MODELO
        $modelo = new Cotizacion();

        // DATOS GENERALES
        $datos = $this->cargar_datos();
        
        $datos['nombre_pagina'] = 'Cotizaciones 2025';

        $breadcrumb = [
            [
                'tarea' => 'Cotizaciones',
                'href' => '#'
            ],
            [
                'tarea' => '2025',
                'href' => '#'
            ]
        ];

        $datos['breadcrumb'] = breadcrumb(
            $datos['tarea'],
            $breadcrumb
        );

        // COTIZACIONES
        $datos['cotizaciones'] =
            $modelo->obtenerPorAnio(2025);

        $this->render(
            'operaciones/cotizaciones/2025',
            $datos
        );

    }

    // =========================
    // Vista 2026
    // =========================
    public function cotizaciones2026()
    {

        if (!$this->permitido) {

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        // MODELO
        $modelo = new Cotizacion();

        // DATOS GENERALES
        $datos = $this->cargar_datos();

        // COTIZACIONES
        $datos['cotizaciones'] =
            $modelo->obtenerPorAnio(2026);

        // VISTA
        
        $this->render(
            'operaciones/cotizaciones/2026',
            $datos
        );

    }

    // =========================
    // CREAR FORMULARIO
    // =========================
    public function nueva()
    {
        // DATOS GENERALES
        $datos = $this->cargar_datos();
        
        $datos['nombre_pagina'] = 'Cotizaciones 2025';

        $breadcrumb = [
            [
                'tarea' => 'Cotizaciones',
                'href' => '#'
            ],
            [
                'tarea' => 'Agregar nueva cotizacion',
                'href' => '#'
            ]
        ];

        $datos['breadcrumb'] = breadcrumb(
            $datos['tarea'],
            $breadcrumb
        );

        $this->render(
            'operaciones/cotizaciones/nueva',
            $datos
        );
    }

    // =========================
    // Guardar cotización
    // =========================
    public function guardar()
    {
        if (!$this->permitido) {

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: ' . BASE_URL . 'cotizaciones/2026');
            exit;

        }

        $modelo = new Cotizacion();

        $datos = [

            'fecha'      => $_POST['fecha'] ?? null,
            'anio'       => $_POST['anio'] ?? null,
            'req'        => limpiarTexto($_POST['req'] ?? ''),
            'folio'      => limpiarTexto($_POST['folio'] ?? ''),
            'elaboro'    => limpiarTextoMayusculas($_POST['elaboro'] ?? ''),
            'partida'    => limpiarTextoMayusculas($_POST['partida'] ?? ''),
            'proveedor'  => limpiarTextoMayusculas($_POST['proveedor'] ?? ''),
            'analista'   => limpiarTextoMayusculas($_POST['analista'] ?? ''),
            'estatus'    => $_POST['estatus'] ?? 'enviado',
            'reenviar'   => isset($_POST['reenviar']) ? 1 : 0,

            // usuario logueado
            'creado_por' => $_SESSION['usuario_id']

        ];

        $modelo->guardar($datos);

        mensaje(
            'Cotización registrada correctamente',
            ALERT_SUCCESS,
            3000
        );

        redirect(
            'cotizaciones/' . $datos['anio']
        );

        exit;
    }

    // =========================
    // Buscar cotización AJAX
    // =========================
    public function buscarAjax()
    {
        $anio = date('Y');
        if (!$this->permitido) {

            http_response_code(403);

            echo json_encode([]);

            exit;
        }

        $termino = trim($_GET['q'] ?? '');

        if (strlen($termino) < 2) {

            echo json_encode([]);

            exit;
        }

        $modelo = new Cotizacion();

        $resultado = $modelo->buscarCotizacion($termino, $anio);

        header('Content-Type: application/json');

        echo json_encode($resultado);

        exit;
    }

    // =========================
    // Buscar catálogo AJAX
    // =========================
    public function buscarCatalogoAjax()
    {
        if (!$this->permitido) {

            http_response_code(403);

            echo json_encode([]);

            exit;
        }

        $campo = trim($_GET['campo'] ?? '');

        $texto = trim($_GET['q'] ?? '');

        if (strlen($texto) < 2) {

            echo json_encode([]);

            exit;
        }

        $modelo = new Cotizacion();

        $resultado = $modelo->buscarCatalogo(
            $campo,
            $texto
        );

        header('Content-Type: application/json');

        echo json_encode($resultado);

        exit;
    }

}