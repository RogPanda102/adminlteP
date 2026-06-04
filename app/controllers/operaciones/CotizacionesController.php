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

        $datos = $this->cargar_datos();

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
        $datos = $this->cargar_datos();

        $this->render(
            'operaciones/cotizaciones/nueva',
            $datos
        );
    }
}