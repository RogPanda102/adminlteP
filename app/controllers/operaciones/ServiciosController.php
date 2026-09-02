<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/Servicios.php';
require_once __DIR__ . '/../../models/Notificacion.php';
require_once __DIR__ . '/../../models/Adjudicados.php';
require_once __DIR__ . '/../../helpers/servicios.php';

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

    // // =========================
    // // Vista 2026
    // // =========================
    // public function servicios2026()
    // {
    //     if (!$this->permitido) {

    //         redirect('login');
    //         exit;
    //     }

    //     $modelo = new Servicio();

    //     $datos = $this->cargar_datos();

    //     $datos['servicios'] =
    //         $modelo->obtenerPorAnio(2026);

    //     $this->render(
    //         'operaciones/servicios/2026',
    //         $datos
    //     );
    // }

    // // =========================
    // // Vista 2025
    // // =========================
    // public function servicios2025()
    // {
    //     if (!$this->permitido) {

    //         redirect('login');

    //     }

    //     $modelo = new Servicio();

    //     $datos = $this->cargar_datos();

    //     $datos['nombre_pagina'] = 'Servicios 2025';

    //     $datos['servicios'] =
    //         $modelo->obtenerPorAnio(2025);

    //     $this->render(
    //         'operaciones/servicios/2025',
    //         $datos
    //     );
    // }

    // =========================
    // Vista dinámica por año
    // =========================
    public function servicios($anio)
    {
        if (!$this->permitido) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $anio = (int) $anio;

        if ($anio < 2000 || $anio > 2100) {
            header('Location: ' . BASE_URL . 'servicios/' . date('Y'));
            exit;
        }

        $modelo = new Servicio();

        $datos = $this->cargar_datos();

        $datos['anio'] = $anio;
        $datos['nombre_pagina'] = 'Servicios ' . $anio;

        $breadcrumb = [
            [
                'tarea' => 'Servicios',
                'href' => '#'
            ],
            [
                'tarea' => (string) $anio,
                'href' => '#'
            ]
        ];

        $datos['breadcrumb'] = breadcrumb(
            $datos['tarea'],
            $breadcrumb
        );

        $datos['servicios'] =
            $modelo->obtenerPorAnio($anio);

        $this->render(
            'operaciones/servicios/index',
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
    // Buscar adjudicación predictivo
    // =========================
    public function buscar()
    {
        if (!$this->permitido) {

            echo json_encode([]);

            exit;
        }

        $texto = trim($_GET['q'] ?? '');

        if ($texto === '') {

            echo json_encode([]);

            exit;
        }

        $modelo = new Adjudicados();

        $resultado = $modelo->buscarParaServicio($texto);

        header('Content-Type: application/json');

        echo json_encode($resultado);

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
    // Buscar tipo de servicio
    // =========================
    public function buscarTipoServicio()
    {

        if (!$this->permitido) {

            echo json_encode([]);

            exit;

        }

        $texto = trim($_GET['q'] ?? '');

        $modelo = new Servicio();

        if ($texto === '') {

            $datos = $modelo->buscarTiposServicio('');

        } else {

            $datos = $modelo->buscarTiposServicio($texto);

        }

        header('Content-Type: application/json');

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

            'analista_id'         => !empty($_POST['analista_id'])
                ? (int) $_POST['analista_id']
                : null,

            'tipo_servicio_id'    => !empty($_POST['tipo_servicio_id'])
                ? (int) $_POST['tipo_servicio_id']
                : null,

            'tiempo_cantidad'  => !empty($_POST['tiempo_cantidad'])
                ? (int) $_POST['tiempo_cantidad']
                : null,

            'tiempo_unidad'    => !empty($_POST['tiempo_unidad'])
                ? trim($_POST['tiempo_unidad'])
                : null,
            'fecha_contratacion'  => $_POST['fecha_contratacion'] ?? null,
            'inicio'              => $_POST['inicio'] ?? null,
            'finalizacion'        => null,
            'dependencia'         => trim($_POST['dependencia'] ?? ''),

            'adjudicado_id'       => !empty($_POST['adjudicado_id'])
                ? (int) $_POST['adjudicado_id']
                : null,

            'anio'                => $_POST['anio'] ?? null,
            'creado_por'          => $_SESSION['usuario_id'] ?? null,
            'actualizado_por'     => null

        ];
        // ========================================
        // CALCULAR FECHAS DEL SERVICIO
        // ========================================
        $fechas = calcularFechasServicio(
            $datos['inicio'],
            null,
            $datos['tiempo_cantidad'],
            $datos['tiempo_unidad']
        );

        $datos['inicio'] = $fechas['inicio'];
        $datos['finalizacion'] = $fechas['finalizacion'];

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

    // =========================
    // Revisar vencimientos
    // =========================
    public function revisarVencimientos()
    {
        if (!$this->permitido) {

            redirect('login');
            exit;
        }

        $modeloServicio = new Servicio();
        $modeloNotificacion = new Notificacion();

        $servicios = $modeloServicio->obtenerServiciosParaNotificar();

        foreach ($servicios as $servicio) {

            // ========================================
            // DETERMINAR EVENTO
            // ========================================

            $evento = determinarEventoVencimiento(
                $servicio['finalizacion']
            );

            if ($evento === null) {
                continue;
            }

            // ========================================
            // COMPROBAR SI YA FUE GENERADO
            // ========================================

            $yaExiste = $modeloNotificacion->existeEvento(

                $servicio['creado_por'],

                'servicios',

                $servicio['id'],

                $evento

            );

            if ($yaExiste) {
                continue;
            }

            // ========================================
            // CREAR NOTIFICACIÓN
            // ========================================

            notificar(

                $servicio['creado_por'],

                'Vencimiento de servicio',

                'El servicio ' .
                $servicio['req'] .
                ' tiene fecha de finalización ' .
                date(
                    'd/m/Y',
                    strtotime($servicio['finalizacion'])
                ) .
                '.',

                'servicios/' . $servicio['anio'],

                'warning',

                'servicios',

                $servicio['id'],

                $evento

            );
        }

        echo 'Revisión de vencimientos completada.';
    }
}
