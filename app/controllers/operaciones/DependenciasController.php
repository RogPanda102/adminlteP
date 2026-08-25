<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/Dependencia.php';

class DependenciasController extends BaseController
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
    // Guardar dependencia AJAX
    // =========================
    public function guardarAjax()
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

        $modelo = new Dependencia();

        $datos = [

            'nombre' =>
                trim($_POST['nombre'] ?? ''),

            'descripcion' =>
                trim($_POST['descripcion'] ?? ''),

            'ubicacion' =>
                trim($_POST['ubicacion'] ?? '')

        ];

        if ($datos['nombre'] === '') {

            echo json_encode([
                'ok' => false,
                'mensaje' => 'El nombre de la dependencia es obligatorio'
            ]);

            exit;
        }

        $dependenciaId =
            $modelo->guardar($datos);

        if (!$dependenciaId) {

            echo json_encode([
                'ok' => false,
                'mensaje' => 'No se pudo registrar la dependencia'
            ]);

            exit;
        }

        echo json_encode([
            'ok' => true,
            'id' => $dependenciaId,
            'nombre' => $datos['nombre']
        ]);

        exit;
    }
}