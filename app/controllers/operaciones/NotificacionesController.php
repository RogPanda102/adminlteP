<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/Notificacion.php';

class NotificacionesController extends BaseController
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

        // Usuario
        $datos['nombre_usuario'] = $_SESSION['usuario_nombre'];
        $datos['foto_usuario'] = BASE_URL . 'assets/upload/usuarios/' . $_SESSION['foto_usuario'];

        // Página
        $datos['nombre_pagina'] = 'Notificaciones';
        $datos['tarea'] = 'Notificaciones';

        // Breadcrumb
        $breadcrumb = [
            [
                'tarea' => 'Notificaciones',
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
    // Listado
    // =========================
    public function index()
    {

        if (!$this->permitido) {

            redirect('login');

        }

        $modelo = new Notificacion();

        $datos = $this->cargar_datos();

        $datos['notificaciones'] = $modelo->obtenerPorUsuario(
            $_SESSION['usuario_id'],
            50
        );

        $this->render(
            'operaciones/notificaciones/index',
            $datos
        );

    }

    public function abrir()
    {

        if (!$this->permitido) {

            redirect('login');

        }

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {

            redirect('notificaciones');

        }

        $modelo = new Notificacion();

        $notificacion = $modelo->buscarPorId($id);

        if (!$notificacion) {

            redirect('notificaciones');

        }

        if (
            $notificacion['usuario_id'] != $_SESSION['usuario_id']
        ) {

            redirect('notificaciones');

        }

        $modelo->marcarLeida($id);

        redirect(
            ltrim($notificacion['url'], '/')
        );

    }

}