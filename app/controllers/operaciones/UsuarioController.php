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

    // =========================
    // Actualizar contraseña
    // =========================
    public function actualizarPassword()
    {
        if (!$this->permitido) {

            redirect('login');

        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            redirect('perfil');

        }

        $passwordActual = trim($_POST['password_actual'] ?? '');
        $passwordNueva = trim($_POST['password'] ?? '');
        $passwordConfirmacion = trim($_POST['password_confirmacion'] ?? '');

        // Validar campos vacíos
        if (
            empty($passwordActual) ||
            empty($passwordNueva) ||
            empty($passwordConfirmacion)
        ) {

            mensaje(
                'Todos los campos son obligatorios.',
                ALERT_WARNING,
                3000
            );

            redirect('perfil');

        }

        // Validar coincidencia
        if ($passwordNueva !== $passwordConfirmacion) {

            mensaje(
                'Las nuevas contraseñas no coinciden.',
                ALERT_WARNING,
                3000
            );

            redirect('perfil');

        }

        // Longitud mínima
        if (strlen($passwordNueva) < 8) {

            mensaje(
                'La contraseña debe tener al menos 8 caracteres.',
                ALERT_WARNING,
                3000
            );

            redirect('perfil');

        }

        $modeloUsuario = new Usuario();

        // Obtener usuario
        $usuario = $modeloUsuario->buscarPorId(
            $_SESSION['usuario_id']
        );

        // Validar contraseña actual
        if (!password_verify(
            $passwordActual,
            $usuario['password']
        )) {

            mensaje(
                'La contraseña actual es incorrecta.',
                ALERT_DANGER,
                3000
            );

            redirect('perfil');

        }

        // Evitar reutilizar la misma contraseña
        if (password_verify(
            $passwordNueva,
            $usuario['password']
        )) {

            mensaje(
                'La nueva contraseña debe ser diferente a la actual.',
                ALERT_WARNING,
                3000
            );

            redirect('perfil');

        }

        $passwordHash = password_hash(
            $passwordNueva,
            PASSWORD_DEFAULT
        );

        $resultado = $modeloUsuario->actualizarPassword(
            $_SESSION['usuario_id'],
            $passwordHash,
            $_SESSION['usuario_id']
        );

        if (!$resultado) {

            mensaje(
                'No fue posible actualizar la contraseña.',
                ALERT_DANGER,
                3000
            );

            redirect('perfil');

        }

        mensaje(
            'Contraseña actualizada correctamente.',
            ALERT_SUCCESS,
            3000
        );

        redirect('perfil');
    }
}