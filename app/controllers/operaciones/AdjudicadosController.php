<?php

require_once APP_PATH . '/controllers/BaseController.php';
require_once __DIR__ . '/../../models/Adjudicados.php';

class AdjudicadosController extends BaseController
{
    protected $permitido = true;
    // =========================
    // Constructor
    // =========================
    public function __construct()
    {
        parent::__construct();

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
        $datos['breadcrumb'] = breadcrumb($datos['tarea'], $breadcrumb);
        return $datos;
    }

    // =========================
    // Vista 2026
    // =========================
    public function adjudicados2026()
    {

        if (!$this->permitido) {

            redirect('login');

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

    // =========================
    // Buscar catálogo AJAX
    // =========================
    public function buscarCatalogoAjax()
    {
        if (!$this->permitido) {

            http_response_code(403);

            echo json_encode([]);

            exit;
        }

        $campo = $_GET['campo'] ?? '';
        $texto = trim($_GET['q'] ?? '');

        if (strlen($texto) < 2) {

            echo json_encode([]);

            exit;
        }

        $modelo = new Adjudicados();

        $resultado = $modelo->buscarCatalogo(
            $campo,
            $texto
        );

        header('Content-Type: application/json');

        echo json_encode($resultado);

        exit;
    }

    // =========================
    // Guardar adjudicación
    // =========================
    public function guardar()
    {
        if (!$this->permitido) {
            redirect('login');
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('adjudicados/2026');
        }
        $modelo = new Adjudicados();
        $datos = [
            'req' => limpiarTexto(
                $_POST['req'] ?? ''
            ),
            'folio' => limpiarTexto(
                $_POST['folio'] ?? ''
            ),
            'elaboro' => limpiarTextoMayusculas(
                $_POST['elaboro'] ?? ''
            ),
            'partida' => limpiarTextoMayusculas(
                $_POST['partida'] ?? ''
            ),
            'analista_id' => !empty($_POST['analista_id'])
                ? (int) $_POST['analista_id']
                : null,
            'fecha_elaboracion' =>
            $_POST['fecha_elaboracion'] ?? null,
            'fecha_inicio_entrega' =>
            $_POST['fecha_inicio_entrega'] ?? null,
            'fecha_fin_entrega' =>
            $_POST['fecha_fin_entrega'] ?? null,
            'total' =>
            $_POST['total'] ?? 0,
            'dia_pago' =>
            $_POST['dia_pago'] ?? null,
            'pago' =>
            $_POST['pago'] ?? 'pendiente',
            'dependencia' =>
            limpiarTextoMayusculas(
                $_POST['dependencia'] ?? ''
            ),
            'cotizacion_id' => !empty($_POST['cotizacion_id'])
            ? (int) $_POST['cotizacion_id']
            : null,
            'anio' =>
            $_POST['anio'] ?? date('Y'),
            'creado_por' =>
            $_SESSION['usuario_id']
        ];
        
        $resultado = $modelo->guardar($datos);
        if ($resultado) {
            mensaje(
                'Adjudicación registrada correctamente',
                ALERT_SUCCESS,
                3000
            );
        } else {
            mensaje(
                'No fue posible registrar la adjudicación',
                ALERT_DANGER,
                3000
            );
        }
        redirect(
            'adjudicados/' . $datos['anio']
        );
    }

    public function update()
    {
        header('Content-Type: application/json');

        $input = json_decode(
            file_get_contents('php://input'),
            true
        );

        if (!$input) {

            echo json_encode([
                'success' => false,
                'message' => 'Datos inválidos'
            ]);

            return;
        }

        $modelo = new Adjudicados();

        $datos = [

            'id' => $input['id'],

            'req' => limpiarTexto(
                $input['req']
            ),

            'folio' => limpiarTexto(
                $input['folio']
            ),

            'elaboro' => limpiarTextoMayusculas(
                $input['elaboro']
            ),

            'partida' => limpiarTextoMayusculas(
                $input['partida']
            ),

            'analista' => limpiarTextoMayusculas(
                $input['analista']
            ),

            'fecha_elaboracion' =>
            $input['fecha_elaboracion'] ?? null,

            'fecha_inicio_entrega' =>
            $input['fecha_inicio_entrega'] ?? null,

            'fecha_fin_entrega' =>
            $input['fecha_fin_entrega'] ?? null,

            'total' =>
            $input['total'] ?? 0,

            'dia_pago' =>
            $input['dia_pago'] ?? null,

            'pago' =>
            $input['pago'],

            'dependencia' =>
            limpiarTextoMayusculas(
                $input['dependencia']
            ),

            'cotizacion_id' =>
            $input['cotizacion_id'] ?? null,

            'anio' =>
            date('Y'),

            'actualizado_por' =>
            $_SESSION['usuario_id']

        ];

        // =========================
        // HISTORIAL
        // =========================

        $antes = $modelo->buscarPorId(
            $datos['id']
        );

        $ok = $modelo->actualizar(
            $datos
        );

        if ($ok) {

            $despues = $modelo->buscarPorId(
                $datos['id']
            );

            registrarHistorial(
                'adjudicados',
                $datos['id'],
                'UPDATE',
                $antes,
                $despues
            );

            echo json_encode([

                'success' => true,

                'message' =>
                'Actualizado correctamente'

            ]);

        } else {

            echo json_encode([

                'success' => false,

                'message' =>
                'Error al actualizar'

            ]);
        }
    }
}
