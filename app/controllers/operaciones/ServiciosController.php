<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/Servicios.php';

class ServiciosController extends BaseController
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
        $datos = [];

        // =========================
        // USUARIO
        // =========================
        $datos['nombre_usuario'] = $_SESSION['usuario_nombre'];
        $datos['foto_usuario'] = BASE_URL . 'assets/upload/usuarios/' . $_SESSION['foto_usuario'];

        // =========================
        // MODULO
        // =========================
        $datos['nombre_pagina'] = 'Servicios 2026';
        $datos['tarea'] = 'Servicios';

        // =========================
        // BREADCRUMB
        // =========================
        $breadcrumb = [
            [
                'tarea' => 'Servicios',
                'href' => '#'
            ],
            [
                'tarea' => '2026',
                'href' => '#'
            ]
        ];

        $datos['breadcrumb'] = breadcrumb(
            $datos['tarea'],
            $breadcrumb
        );

        return $datos;
    }

    // =========================
    // Vista 2026
    // =========================
    public function servicios2026()
    {
        if (!$this->permitido) {

            redirect('login');
            exit;
        }

        $modelo = new Servicio();

        $datos = $this->cargar_datos();

        $datos['servicios'] =
            $modelo->obtenerPorAnio(2026);

        $this->render(
            'operaciones/servicios/2026',
            $datos
        );
    }

    // =========================
    // Vista 2025
    // =========================
    public function servicios2025()
    {
        if (!$this->permitido) {

            redirect('login');

        }

        $modelo = new Servicio();

        $datos = $this->cargar_datos();

        $datos['nombre_pagina'] = 'Servicios 2025';

        $datos['servicios'] =
            $modelo->obtenerPorAnio(2025);

        $this->render(
            'operaciones/servicios/2025',
            $datos
        );
    }

    // =========================
    // CREAR FORMULARIO
    // =========================
    public function nueva()
    {
        if (!$this->permitido) {

            redirect('login');
            exit;
        }

        $datos = $this->cargar_datos();

        $datos['nombre_pagina'] = 'Servicios';

        $breadcrumb = [
            [
                'tarea' => 'Servicios',
                'href' => '#'
            ],
            [
                'tarea' => 'Agregar Nuevo',
                'href' => '#'
            ]
        ];

        $datos['breadcrumb'] = breadcrumb(
            $datos['tarea'],
            $breadcrumb
        );

        $this->render(
            'operaciones/servicios/nueva',
            $datos
        );
    }

    // =========================
    // Buscar servicios predictivo
    // =========================
    public function buscar()
    {
        if (!$this->permitido) {

            echo json_encode([]);

            exit;
        }


        $texto = trim(
            $_GET['q'] ?? ''
        );


        if ($texto === '') {

            echo json_encode([]);

            exit;
        }


        $modelo = new Servicio();


        $resultado = $modelo->buscarPredictivo(
            $texto
        );


        header(
            'Content-Type: application/json'
        );


        echo json_encode(
            $resultado
        );


        exit;
    }

    // =========================
    // Buscar dependencia predictiva
    // =========================
    public function buscarDependencia()
    {

        if (!$this->permitido) {

            echo json_encode([]);

            exit;
        }


        $texto = trim(
            $_GET['q'] ?? ''
        );


        if ($texto === '') {

            echo json_encode([]);

            exit;
        }


        $modelo = new Servicio();


        $datos =
            $modelo->buscarDependencias(
                $texto
            );


        header(
            'Content-Type: application/json'
        );


        echo json_encode($datos);

        exit;
    }



    // =========================
    // Guardar servicio
    // =========================
    public function guardar()
    {
        if (!$this->permitido) {

            redirect('login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            redirect('servicios/2026');
            exit;
        }

        $modelo = new Servicio();

        $datos = [

            'req'                 => trim($_POST['req'] ?? ''),
            'folio'               => trim($_POST['folio'] ?? ''),
            'elaboro'             => trim($_POST['elaboro'] ?? ''),
            'partida'             => trim($_POST['partida'] ?? ''),
            'analista'            => trim($_POST['analista'] ?? ''),
            'tiempo_contratacion' => trim($_POST['tiempo_contratacion'] ?? ''),
            'fecha_contratacion'  => $_POST['fecha_contratacion'] ?? null,
            'inicio'              => $_POST['inicio'] ?? null,
            'finalizacion'        => $_POST['finalizacion'] ?? null,
            'dependencia'         => trim($_POST['dependencia'] ?? ''),
            'anio'                => $_POST['anio'] ?? null

        ];

        $modelo->guardar($datos);

        mensaje(
            'Servicio registrado correctamente',
            ALERT_SUCCESS,
            3000
        );

        redirect(
            'servicios/' . $datos['anio']
        );

        exit;
    }
}
