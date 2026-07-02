<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/Usuario.php';
require_once __DIR__ . '/../../models/Cotizacion.php';

class UsuarioController extends BaseController
{
    protected $permitido = true;

    public function __construct()
    {
        if (!isset($_SESSION['logueado'])) {
            $this->permitido = false;
        }
    }

    private function cargar_datos()
    {
        $datos = [];

        $datos['nombre_usuario'] = $_SESSION['usuario_nombre'];
        $datos['foto_usuario'] =
            BASE_URL . 'assets/upload/usuarios/' . $_SESSION['foto_usuario'];

        $datos['nombre_pagina'] = 'Mi perfil';
        $datos['tarea'] = 'Perfil';

        $breadcrumb = [
            [
                'tarea' => 'Perfil',
                'href' => '#'
            ]
        ];

        $datos['breadcrumb'] = breadcrumb($datos['tarea'], $breadcrumb);

        return $datos;
    }

    public function perfil()
    {
        if (!$this->permitido) {
            redirect('login');
        }

        $modeloCotizacion = new Cotizacion();

        $datos = $this->cargar_datos();

        $datos['estadisticas'] =
            $modeloCotizacion->obtenerEstadisticasUsuario(
                $_SESSION['usuario_id']
            );

        $datos['anios'] =
            $modeloCotizacion->obtenerAnios();

        $this->render(
            'usuario/perfil',
            $datos
        );
    }

    // =========================
    // AJAX - Gráfica cotizaciones
    // =========================
    public function estadisticasCotizaciones()
    {
        if (!$this->permitido) {
            echo json_encode(['error' => true]);
            exit;
        }

        $modelo = new Cotizacion();

        $anio = $_GET['anio'] ?? date('Y');
        $mes  = $_GET['mes'] ?? null;

        // 🔥 gráfica por mes
        $grafica = $modelo->obtenerCotizacionesPorMes(
            $_SESSION['usuario_id'],
            $anio,
            $mes
        );

        // 🔥 métricas base (las sacamos desde la misma data)
        $total = array_sum($grafica);

        $promedio = $total > 0
            ? round($total / 12, 2)
            : 0;

        $maxMes = array_keys($grafica, max($grafica))[0] ?? 1;

        $meses = [
            1=>'Enero',2=>'Febrero',3=>'Marzo',4=>'Abril',
            5=>'Mayo',6=>'Junio',7=>'Julio',8=>'Agosto',
            9=>'Septiembre',10=>'Octubre',11=>'Noviembre',12=>'Diciembre'
        ];

        header('Content-Type: application/json');

        echo json_encode([
            'grafica' => $grafica,
            'metricas' => [
                'total' => $total,
                'promedio' => $promedio,
                'mejor_mes' => $meses[$maxMes]
            ]
        ]);

        exit;
    }
}