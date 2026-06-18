<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/Adjudicados.php';

class AdjudicadosController extends BaseController
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
        $datos['nombre_pagina'] = 'Adjudicados 2026';
        $datos['tarea'] = 'Adjudicados';
        // =========================
        // BREADCRUMB
        // =========================
        $breadcrumb = array(
            array(
                'tarea' => 'Adjudicados',
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
    // Vista 2026
    // =========================
    public function adjudicados2026()
    {

        if (!$this->permitido) {

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        // MODELO
        $modelo = new Adjudicados;

        // DATOS GENERALES
        $datos = $this->cargar_datos();

        // Adjudicados
         $datos['adjudicados'] =
             $modelo->obtenerPorAnio(2026);

        // VISTA
        $this->render(
            'operaciones/adjudicados/2026',
            $datos
        );

    }

    // =========================
    // Vista 2025
    // =========================
    public function adjudicados2025()
    {

        if (!$this->permitido) {

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        // MODELO
        $modelo = new Adjudicados;

        // DATOS GENERALES
        $datos = $this->cargar_datos();

        // Adjudicados
         $datos['adjudicados'] =
             $modelo->obtenerPorAnio(2025);

        // VISTA
        $this->render(
            'operaciones/adjudicados/2025',
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
        
        $datos['nombre_pagina'] = 'Adjudicados';

        $breadcrumb = [
            [
                'tarea' => 'Adjudicados',
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
            'operaciones/adjudicados/nueva',
            $datos
        );
    }

    public function buscarDependenciaAjax()
    {
        $texto = trim($_GET['q'] ?? '');

        $modelo = new Adjudicados();

        $resultado =
            $modelo->buscarDependencias($texto);

        header('Content-Type: application/json');

        echo json_encode($resultado);

        exit;
    }

}
