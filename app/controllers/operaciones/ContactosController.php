<?php

require_once __DIR__ . '/../BaseController.php';

require_once __DIR__ . '/../../models/Analista.php';
require_once __DIR__ . '/../../models/Encargado.php';

class ContactosController extends BaseController
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

        // USUARIO
        $datos['nombre_usuario'] = $_SESSION['usuario_nombre'];
        $datos['foto_usuario'] = BASE_URL .
            'assets/upload/usuarios/' .
            $_SESSION['foto_usuario'];

        // MODULO
        $datos['nombre_pagina'] = 'Contactos';
        $datos['tarea'] = 'Contactos';

        // BREADCRUMB
        $breadcrumb = [
            [
                'tarea' => 'Contactos',
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
    // Vista principal
    // =========================
    public function index()
    {
        if (!$this->permitido) {

            redirect('login');
        }

        $analistaModel = new Analista();
        $encargadoModel = new Encargado();

        $datos = $this->cargar_datos();

        $datos['analistas'] =
            $analistaModel->obtenerTodos();

        $datos['encargados'] =
            $encargadoModel->obtenerTodos();

        $this->render(
            'operaciones/contactos/index',
            $datos
        );
    }

    // =========================
    // Guardar analista
    // =========================
    public function guardarAnalista()
    {
        if (!$this->permitido) {

            redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            redirect('contactos');
        }

        $datos = [

            'nombre'   => trim($_POST['nombre'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'correo'   => trim($_POST['correo'] ?? '')

        ];

        
        if (
            !validarRequerido(
                $datos['nombre'],
                'El nombre del analista es obligatorio'
            )
        ) {
            redirect('contactos');
        }

        if (
            !empty($datos['correo']) &&
            !filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)
        ) {

            mensaje(
                'El correo no es válido',
                ALERT_DANGER,
                3000
            );

            redirect('contactos');
        }

        $modelo = new Analista();

        

        $modelo->guardar($datos);

        mensaje(
            'Analista registrado correctamente',
            ALERT_SUCCESS,
            3000
        );

        redirect(
            'contactos'
        );

        if ($datos['nombre'] === '') {
            mensaje('Nombre obligatorio', ALERT_DANGER, 3000);
            header('Location: ' . BASE_URL . 'contactos');
            exit;
        }

        exit;
    }

    // =========================
    // Guardar encargado
    // =========================
    public function guardarEncargado()
    {
        if (!$this->permitido) {

            redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            redirect('contactos');
        }

        $datos = [

            'nombre'      => trim($_POST['nombre'] ?? ''),
            'telefono'    => trim($_POST['telefono'] ?? ''),
            'dependencia' => trim($_POST['dependencia'] ?? '')

        ];

        if (
            !validarRequerido(
                $datos['nombre'],
                'El nombre del analista es obligatorio'
            )
        ) {
            redirect('contactos');
        }

        if (
            !validarRequerido(
                $datos['dependencia'],
                'La dependencia es obligatoria'
            )
        ) {
            redirect('contactos');
        }

        if (empty($datos['dependencia'])) {

            mensaje(
                'La dependencia es obligatoria',
                ALERT_DANGER,
                3000
            );

            redirect('contactos');
        }

        $modelo = new Encargado();

        $modelo->guardar($datos);

        mensaje(
            'Encargado registrado correctamente',
            ALERT_SUCCESS,
            3000
        );

        redirect(
            'contactos'
        );

        exit;
    }
}
