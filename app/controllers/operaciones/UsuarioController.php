<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/Usuario.php';

class UsuarioController extends BaseController
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

        $datos['foto_usuario'] =
            BASE_URL .
            'assets/upload/usuarios/' .
            $_SESSION['foto_usuario'];
            

        // =========================
        // MODULO
        // =========================

        $datos['nombre_pagina'] = 'Mi perfil';

        $datos['tarea'] = 'Perfil';

        // =========================
        // BREADCRUMB
        // =========================

        $breadcrumb = [

            [
                'tarea' => 'Perfil',
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
    // Vista Perfil
    // =========================
    public function perfil()
    {

        if (!$this->permitido) {

            redirect('login');

        }

        $modelo = new Usuario();

        $datos = $this->cargar_datos();
        
        $datos['usuario'] = $modelo->buscarPorId( $_SESSION['usuario_id']);
        
        $this->render(
            'usuario/perfil',
            $datos
        );

        
    }

}