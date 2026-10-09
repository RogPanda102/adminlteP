<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/Servicios.php';
require_once __DIR__ . '/../../models/Notificacion.php';
require_once __DIR__ . '/../../models/Adjudicados.php';
require_once __DIR__ . '/../../helpers/servicios.php';
require_once __DIR__ . '/../../models/RecordatorioServicio.php';
require_once __DIR__ . '/../../models/EventoNotificacion.php';

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
            'dependencia_id' => !empty($_POST['dependencia_id'])
            ? (int) $_POST['dependencia_id']
            : null,

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

        $servicioId = $modelo->guardar($datos);

        if (!$servicioId) {
            mensaje(
                'No fue posible registrar el servicio',
                ALERT_DANGER,
                3000
            );

            redirect('servicios/' . $datos['anio']);
            exit;
        }
        
        notificarExito(
            null,
            'Servicio registrado',
            'Se registró el servicio con folio "' . $datos['folio'] . '".',
            '/servicios/' . $datos['anio'],
            'servicios',
            $servicioId,
            'creado'
        );

        // ========================================
        // CREAR RECORDATORIOS PREDETERMINADOS
        // ========================================

        $recordatorioModelo = new RecordatorioServicio();

        $recordatoriosPredeterminados = [
            90,
            30,
            7,
            0
        ];

        foreach ($recordatoriosPredeterminados as $diasAntes) {

            $recordatorioModelo->crear([
                'servicio_id' => $servicioId,
                'dias_antes' => $diasAntes,
                'activo' => 1
            ]);
        }

        // ========================================
        // GENERAR EVENTOS DE NOTIFICACIÓN
        // ========================================

        $eventoModelo = new EventoNotificacion();

        $eventoModelo->generarParaServicio($servicioId);

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
    // Actualizar servicio
    // =========================
    public function actualizar()
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

        $modelo = new Servicio();

        // =========================
        // ID DEL SERVICIO
        // =========================

        $id = (int)($input['id'] ?? 0);

        if ($id <= 0) {

            echo json_encode([
                'success' => false,
                'message' => 'ID de servicio inválido'
            ]);

            return;
        }

        // =========================
        // ESTADO ANTERIOR
        // =========================

        $antes = $modelo->buscarPorId($id);

        if (!$antes) {

            echo json_encode([
                'success' => false,
                'message' => 'Servicio no encontrado'
            ]);

            return;
        }

        // =========================
        // DATOS EDITABLES
        // =========================

        $cantidad = !empty($input['tiempo_cantidad'])
            ? (int)$input['tiempo_cantidad']
            : null;

        $unidad = !empty($input['tiempo_unidad'])
            ? trim($input['tiempo_unidad'])
            : null;

        $fechaContratacion = !empty($input['fecha_contratacion'])
            ? trim($input['fecha_contratacion'])
            : null;

        $inicio = !empty($input['inicio'])
            ? $input['inicio']
            : null;

        // =========================
        // VALIDAR DURACIÓN
        // =========================

        if (empty($cantidad) || empty($unidad)) {

            echo json_encode([
                'success' => false,
                'message' => 'La cantidad y la unidad de duración son obligatorias'
            ]);

            return;
        }

        // =========================
        // CALCULAR NUEVA FINALIZACIÓN
        // =========================

        $fechas = calcularFechasServicio(
            $inicio,
            null,
            $cantidad,
            $unidad
        );

        $nuevaFinalizacion = $fechas['finalizacion'];

        if (empty($nuevaFinalizacion)) {

            echo json_encode([
                'success' => false,
                'message' => 'No se pudo calcular la fecha de finalización'
            ]);

            return;
        }

        // =========================
        // DATOS PARA ACTUALIZAR
        // =========================

        $datos = [
            'id' => $id,

            'tiempo_cantidad' => $cantidad,

            'tiempo_unidad' => $unidad,

            'fecha_contratacion' => $fechaContratacion,

            'inicio' => $fechas['inicio'],

            'finalizacion' => $nuevaFinalizacion,

            'actualizado_por' => $_SESSION['usuario_id']
        ];

        // =========================
        // ACTUALIZAR
        // =========================

        $ok = $modelo->actualizar($datos);

        if (!$ok) {

            echo json_encode([
                'success' => false,
                'message' => 'Error al actualizar el servicio'
            ]);

            return;
        }

        $despues = $modelo->buscarPorId($id);

        // Si cambió la fecha de finalización,
        // regeneramos los eventos pendientes.
        if ($antes['finalizacion'] !== $despues['finalizacion']) {

            $eventoModelo = new EventoNotificacion();

            // Eliminar únicamente eventos que todavía no han sido enviados.
            // Los eventos históricos ya enviados se conservan.
            $eventoModelo->eliminarPendientesPorServicio($id);

            // Generar nuevamente los eventos según
            // los recordatorios activos del servicio.
            $eventoModelo->generarParaServicio($id);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Servicio actualizado correctamente',
            'servicio' => $despues
        ]);
    }
}
