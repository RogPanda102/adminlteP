<?php

require_once __DIR__ . '/../BaseController.php';

class LogisticaController extends BaseController
{
    protected $permitido = true;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // Verificar acceso al módulo
        $this->verificarPermiso(
            TAREA_LOGISTICA,
            'el módulo de Logística'
        );

        // ==========================================
        // DATOS DE LA VISTA
        // ==========================================

        $datos = [];

        $datos['nombre_usuario'] = $_SESSION['usuario_nombre'];

        $datos['foto_usuario'] =
            BASE_URL . 'assets/upload/usuarios/' . $_SESSION['foto_usuario'];

        $datos['nombre_pagina'] = 'Logística';
        $datos['tarea'] = 'Logística';

        $breadcrumb = [
            [
                'tarea' => 'Logística',
                'href' => '#'
            ]
        ];

        $datos['breadcrumb'] = breadcrumb(
            $datos['tarea'],
            $breadcrumb
        );

        // ==========================================
        // RENDERIZAR VISTA
        // ==========================================

        $this->render(
            'operaciones/logistica/index',
            $datos
        );
    }
}