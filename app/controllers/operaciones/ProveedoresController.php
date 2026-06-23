<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/Proveedores.php';

class ProveedoresController extends BaseController
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
        $datos['nombre_pagina'] = 'Proveedores 2026';
        $datos['tarea'] = 'Proveedores';
        // =========================
        // BREADCRUMB
        // =========================
        $breadcrumb = array(
            array(
                'tarea' => 'Proveedores',
                'href'  => '#'
            ),
        );
        $datos['breadcrumb'] = breadcrumb( $datos['tarea'], $breadcrumb );
        return $datos;

    }

    // =========================
    // Vista 2025
    // =========================
    public function index()
    {

        if (!$this->permitido) {

            redirect('login');

        }

        // MODELO
        $modelo = new Proveedor();

        $datos = $this->cargar_datos();

        $datos['proveedores'] =
            $modelo->obtenerTodos();

        $this->render(
            'operaciones/proveedores/index',
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
        
        $datos['nombre_pagina'] = 'Proveedores';

        $breadcrumb = [
            [
                'tarea' => 'Proveedores',
                'href' => '#'
            ],
            [
                'tarea' => 'Agregar nuevo proveedor',
                'href' => '#'
            ]
        ];

        $datos['breadcrumb'] = breadcrumb(
            $datos['tarea'],
            $breadcrumb
        );

        $this->render(
            'operaciones/proveedores/nueva',
            $datos
        );
    }

    // =========================
    // Guardar proveedor
    // =========================
    public function guardar()
    {
        if (!$this->permitido) {

            redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            redirect('proveedores');

        }

        $modelo = new Proveedor();

        $datos = [

            'proveedor' => trim($_POST['proveedor'] ?? ''),
            'servicios' => trim($_POST['servicios'] ?? ''),
            'ubicacion' => trim($_POST['ubicacion'] ?? ''),
            'contacto'  => trim($_POST['contacto'] ?? ''),
            'telefono'  => trim($_POST['telefono'] ?? ''),
            'email'     => trim($_POST['email'] ?? ''),
            'enlace'    => trim($_POST['enlace'] ?? ''),

            // usuario logueado
            'creado_por' => $_SESSION['usuario_id']

        ];

        $modelo->guardar($datos);

        mensaje(
            'Proveedor registrado correctamente',
            ALERT_SUCCESS,
            3000
        );

        redirect(
            'proveedores'
        );
    }

}