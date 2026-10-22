
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
}
