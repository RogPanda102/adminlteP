<?php
require_once APP_PATH . '/controllers/BaseController.php';
require_once __DIR__ . '/../../models/Historial.php';

class HistorialController
{
    public function adjudicados()
    {
        header('Content-Type: application/json');

        $id = $_GET['id'] ?? null;

        $model = new Historial();

        $data = $model->obtener('adjudicados', $id);

        foreach ($data as &$h) {
            $h['datos_anteriores'] = json_decode($h['datos_anteriores'], true);
            $h['datos_nuevos'] = json_decode($h['datos_nuevos'], true);
        }

        echo json_encode([
            'success' => true,
            'data' => $data
        ]);
    }
}
