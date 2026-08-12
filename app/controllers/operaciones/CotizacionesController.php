<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/Cotizacion.php';
require_once __DIR__ . '/../../models/Catalogo.php';

class CotizacionesController extends BaseController
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
        $datos['nombre_pagina'] = 'Cotizaciones 2026';
        $datos['tarea'] = 'Cotizaciones';
        // =========================
        // BREADCRUMB
        // =========================
        $breadcrumb = array(
            array(
                'tarea' => 'Cotizaciones',
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
    // Vista 2025
    // =========================
    public function cotizaciones2025()
    {

        if (!$this->permitido) {

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        // MODELO
        $modelo = new Cotizacion();

        // DATOS GENERALES
        $datos = $this->cargar_datos();
        
        $datos['nombre_pagina'] = 'Cotizaciones 2025';

        $breadcrumb = [
            [
                'tarea' => 'Cotizaciones',
                'href' => '#'
            ],
            [
                'tarea' => '2025',
                'href' => '#'
            ]
        ];

        $datos['breadcrumb'] = breadcrumb(
            $datos['tarea'],
            $breadcrumb
        );

        // COTIZACIONES
        $datos['cotizaciones'] =
            $modelo->obtenerPorAnio(2025);

        $this->render(
            'operaciones/cotizaciones/2025',
            $datos
        );

    }

    // =========================
    // Vista 2026
    // =========================
    public function cotizaciones2026()
    {

        if (!$this->permitido) {

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        // MODELO
        $modelo = new Cotizacion();

        // DATOS GENERALES
        $datos = $this->cargar_datos();

        // COTIZACIONES
        $datos['cotizaciones'] =
            $modelo->obtenerPorAnio(2026);

        // VISTA
        
        $this->render(
            'operaciones/cotizaciones/2026',
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
        
        $datos['nombre_pagina'] = 'Cotizaciones 2025';

        $breadcrumb = [
            [
                'tarea' => 'Cotizaciones',
                'href' => '#'
            ],
            [
                'tarea' => 'Agregar nueva cotizacion',
                'href' => '#'
            ]
        ];

        $datos['breadcrumb'] = breadcrumb(
            $datos['tarea'],
            $breadcrumb
        );

        $this->render(
            'operaciones/cotizaciones/nueva',
            $datos
        );
    }

    // =========================
    // Guardar cotización
    // =========================
    public function guardar()
    {
        if (!$this->permitido) {

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: ' . BASE_URL . 'cotizaciones/2026');
            exit;

        }

        $modelo = new Cotizacion();

        $datos = [

            'fecha'      => $_POST['fecha'] ?? null,
            'anio'       => $_POST['anio'] ?? null,
            'req'        => limpiarTexto($_POST['req'] ?? ''),
            'folio'      => limpiarTexto($_POST['folio'] ?? ''),
            'elaboro'    => limpiarTextoMayusculas($_POST['elaboro'] ?? ''),
            'partida'    => limpiarTextoMayusculas($_POST['partida'] ?? ''),
            'dependencia' => limpiarTextoMayusculas($_POST['dependencia'] ?? ''),
            'proveedor'  => limpiarTextoMayusculas($_POST['proveedor'] ?? ''),
            'analista_id' => !empty($_POST['analista_id'])
                ? (int) $_POST['analista_id']
                : null,
            'estatus'    => $_POST['estatus'] ?? 'enviado',
            'reenviar'   => isset($_POST['reenviar']) ? 1 : 0,

            // usuario logueado
            'creado_por' => $_SESSION['usuario_id']

        ];

        $modelo->guardar($datos);

        mensaje(
            'Cotización registrada correctamente',
            ALERT_SUCCESS,
            3000
        );

        redirect(
            'cotizaciones/' . $datos['anio']
        );

        exit;
    }

    // =========================
    // Buscar cotización AJAX
    // =========================
    public function buscarAjax()
    {
        $anio = date('Y');
        if (!$this->permitido) {

            http_response_code(403);

            echo json_encode([]);

            exit;
        }

        $termino = trim($_GET['q'] ?? '');

        if (strlen($termino) < 2) {

            echo json_encode([]);

            exit;
        }

        $modelo = new Cotizacion();

        $resultado = $modelo->buscarCotizacion($termino, $anio);

        header('Content-Type: application/json');

        echo json_encode($resultado);

        exit;
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

        $campo = trim($_GET['campo'] ?? '');

        $texto = trim($_GET['q'] ?? '');

        if ($texto !== '' && strlen($texto) < 2) {

            echo json_encode([]);

            exit;
        }

        $modelo = new Catalogo();

        $resultado = $modelo->buscar(
            $campo,
            $texto
        );

        header('Content-Type: application/json');

        echo json_encode($resultado);

        exit;
    }


    // =========================
    // Guardar proveedor AJAX
    // =========================
    public function guardarProveedorAjax()
    {
        header('Content-Type: application/json');

        if (!$this->permitido) {

            http_response_code(403);

            echo json_encode([
                'ok' => false,
                'mensaje' => 'No autorizado'
            ]);

            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            http_response_code(405);

            echo json_encode([
                'ok' => false,
                'mensaje' => 'Método no permitido'
            ]);

            exit;
        }

        require_once __DIR__ . '/../../models/Proveedor.php';

        $modelo = new Proveedor();

        $datos = [
            'proveedor' => trim($_POST['proveedor'] ?? ''),
            'servicios' => trim($_POST['servicios'] ?? ''),
            'ubicacion' => trim($_POST['ubicacion'] ?? ''),
            'contacto'  => trim($_POST['contacto'] ?? ''),
            'telefono'  => trim($_POST['telefono'] ?? ''),
            'email'     => trim($_POST['email'] ?? ''),
            'enlace'    => trim($_POST['enlace'] ?? '')
        ];

        $proveedorId = $modelo->guardar($datos);

        if (!$proveedorId) {

            echo json_encode([
                'ok' => false,
                'mensaje' => 'No se pudo guardar el proveedor'
            ]);

            exit;
        }

        echo json_encode([
            'ok' => true,
            'id' => $proveedorId,
            'nombre' => $datos['proveedor']
        ]);

        exit;
    }

    // =========================
    // Actualizar cotizacion
    // =========================
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

        $modelo = new Cotizacion();

        $datos = [

            'id' => (int)$input['id'],

            'fecha' => !empty($input['fecha'])
                ? $input['fecha']
                : null,

            'req' => limpiarTexto($input['req']),

            'folio' => limpiarTexto($input['folio']),

            'elaboro' => limpiarTextoMayusculas($input['elaboro']),

            'partida' => limpiarTextoMayusculas($input['partida']),

            'proveedor' => limpiarTextoMayusculas($input['proveedor']),

            'analista_id' => !empty($input['analista_id'])
                ? (int)$input['analista_id']
                : null,

            'dependencia' => limpiarTextoMayusculas(
                $input['dependencia']
            ),

            'estatus' => $input['estatus'],

            'reenviar' => !empty($input['reenviar']) ? 1 : 0,

            'anio' => (int)$input['anio'],

            'actualizado_por' => $_SESSION['usuario_id']

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
                'cotizaciones',
                $datos['id'],
                'UPDATE',
                $antes,
                $despues
            );

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