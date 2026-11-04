<?php

require_once __DIR__ . '/../BaseController.php';

class BusquedaGlobalController extends BaseController
{
    protected $permitido = true;

    public function __construct()
    {
        parent::__construct();

        if (!isset($_SESSION['logueado'])) {
            $this->permitido = false;
        }
    }

    public function buscar()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!$this->permitido) {
            http_response_code(401);

            echo json_encode([
                'ok' => false,
                'mensaje' => 'Debes iniciar sesión.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $termino = trim($_GET['q'] ?? '');

        if (mb_strlen($termino) < 2) {
            echo json_encode([
                'ok' => true,
                'resultados' => []
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        try {
            $resultados = buscarGlobalmente($termino);

            echo json_encode([
                'ok' => true,
                'resultados' => $resultados
            ], JSON_UNESCAPED_UNICODE);

        } catch (Throwable $e) {
            error_log(
                'Error en la búsqueda global: ' . $e->getMessage()
            );

            http_response_code(500);

            echo json_encode([
                'ok' => false,
                'mensaje' => 'No fue posible realizar la búsqueda.'
            ], JSON_UNESCAPED_UNICODE);
        }

        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | Consultar detalle de un expediente
    |--------------------------------------------------------------------------
    */

    public function detalle()
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!$this->permitido) {
            http_response_code(401);

            echo json_encode([
                'ok' => false,
                'mensaje' => 'Debes iniciar sesión.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $ids = [
            'cotizacion' => (int) ($_GET['cotizacion_id'] ?? 0),
            'adjudicado' => (int) ($_GET['adjudicado_id'] ?? 0),
            'servicio' => (int) ($_GET['servicio_id'] ?? 0)
        ];

        if (
            $ids['cotizacion'] <= 0 &&
            $ids['adjudicado'] <= 0 &&
            $ids['servicio'] <= 0
        ) {
            http_response_code(400);

            echo json_encode([
                'ok' => false,
                'mensaje' => 'No se recibió un identificador válido.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        try {
            $detalle = obtenerDetalleBusquedaGlobal($ids);

            if (empty($detalle['cotizacion']) &&
                empty($detalle['adjudicado']) &&
                empty($detalle['servicios'])) {

                http_response_code(404);

                echo json_encode([
                    'ok' => false,
                    'mensaje' => 'No se encontró información para este expediente.'
                ], JSON_UNESCAPED_UNICODE);

                exit;
            }

            echo json_encode([
                'ok' => true,
                'detalle' => $detalle
            ], JSON_UNESCAPED_UNICODE);

        } catch (Throwable $e) {
            error_log(
                'Error al consultar el detalle de búsqueda global: '
                . $e->getMessage()
            );

            http_response_code(500);

            echo json_encode([
                'ok' => false,
                'mensaje' => 'No fue posible consultar el detalle del expediente.'
            ], JSON_UNESCAPED_UNICODE);
        }

        exit;
    }
}
