
<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/AdministracionUsuarios.php';

class AdministracionUsuariosController extends BaseController
{
    protected $permitido = false;

    // =========================
    // Constructor
    // =========================
    public function __construct()
    {
        if (
            isset($_SESSION['logueado']) &&
            comprobar_acceso(TAREA_USUARIOS)
        ) {
            $this->permitido = true;
        }
    }

    // =========================
    // Datos plantilla
    // =========================
    private function cargar_datos($pagina)
    {
        $datos = [];

        // Usuario
        $datos['nombre_usuario'] = $_SESSION['usuario_nombre'];
        $datos['foto_usuario'] = BASE_URL . 'assets/upload/usuarios/' . $_SESSION['foto_usuario'];

        // Página
        $datos['nombre_pagina'] = $pagina;
        $datos['tarea'] = 'Administración de usuarios';

        // Breadcrumb
        $breadcrumb = [
            [
                'tarea' => 'Administración',
                'href' => '#'
            ],
            [
                'tarea' => $pagina,
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
    // Validar acceso
    // =========================
    private function validar_acceso()
    {
        if (!$this->permitido) {

            if (!isset($_SESSION['logueado'])) {
                redirect('login');
            }

            http_response_code(403);

            exit('No tienes permisos para acceder a esta sección.');

        }
    }

    // =========================
    // Pantalla principal
    // =========================
    public function index()
    {
        $this->validar_acceso();

        $datos = $this->cargar_datos(
            'Administración de usuarios'
        );

        $this->render(
            'operaciones/administracion_usuarios/index',
            $datos
        );
    }

    // =========================
    // Registro de usuarios
    // =========================
    public function registro()
    {
        $this->validar_acceso();

        $modelo = new AdministracionUsuarios();

        $datos = $this->cargar_datos(
            'Usuarios registrados'
        );

        $datos['usuarios'] = $modelo->obtenerUsuarios();

        $this->render(
            'operaciones/administracion_usuarios/registro',
            $datos
        );
    }

    // =========================
    // Historial general y por usuario
    // =========================
    public function historial($id = null)
    {
        $this->validar_acceso();

        $modelo = new AdministracionUsuarios();

        // Identificar si se consulta un usuario específico
        if ($id !== null) {

            $id = (int) $id;

            if ($id <= 0) {
                http_response_code(400);
                exit('Identificador de usuario no válido.');
            }

        }

        $datos = $this->cargar_datos(
            'Historial de actividad'
        );

        // Usuario seleccionado (o null para historial general)
        $datos['usuario_id'] = $id;

        // Obtener movimientos
        $datos['historial'] = $modelo->obtenerHistorial($id);

        // Obtener usuarios para filtros posteriores
        $datos['usuarios'] = $modelo->obtenerUsuarios();

        $this->render(
            'operaciones/administracion_usuarios/historial',
            $datos
        );
    }
}
