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
            'analista' => limpiarTextoMayusculas(
                $_POST['analista'] ?? ''
            ),
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
            'cotizacion_id' =>
                $_POST['cotizacion_id'] ?? null,
            'anio' =>
                date('Y'),
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

        $input = json_decode(file_get_contents('php://input'), true);

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
            'req' => $input['req'],
            'folio' => $input['folio'],
            'elaboro' => $input['elaboro'],
            'partida' => $input['partida'],
            'analista' => $input['analista'],
            'fecha_elaboracion' => $input['fecha_elaboracion'] ?? null,
            'fecha_inicio_entrega' => $input['fecha_inicio_entrega'] ?? null,
            'fecha_fin_entrega' => $input['fecha_fin_entrega'] ?? null,
            'total' => $input['total'] ?? 0,
            'dia_pago' => $input['dia_pago'] ?? null,
            'pago' => $input['pago'],
            'dependencia' => $input['dependencia'],
            'cotizacion_id' => $input['cotizacion_id'] ?? null,
            'anio' => date('Y'),
            'actualizado_por' => $_SESSION['usuario_id']
        ];

        $ok = $modelo->actualizar($datos);

        if ($ok) {
            echo json_encode([
                'success' => true,
                'message' => 'Actualizado correctamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar'
            ]);
        }
    }
}
