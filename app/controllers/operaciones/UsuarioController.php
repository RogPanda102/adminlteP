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
        $modeloUsuario = new Usuario();
        $modeloCotizacion = new Cotizacion();

        $datos = $this->cargar_datos();
        
        // Información del usuario
        $datos['usuario'] = $modeloUsuario->buscarPorId(
            $_SESSION['usuario_id']
        );
        // Estadísticas
        $datos['estadisticas'] =
            $modeloCotizacion->obtenerEstadisticasUsuario(
                $_SESSION['usuario_id']
            );

        $datos['anios'] =
            $modeloCotizacion->obtenerAnios();

        $datos['tab_activo'] = oldTab();
        $this->render(
            'usuario/perfil',
            $datos
        );
        limpiarOld();
        limpiarErrores();
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
    // Actualizar información personal
    // =========================
    public function actualizar()
    {
        
        
        if (!$this->permitido) {

            redirect('login');

        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            redirect('perfil');

        }

        $datos = [

            'id' => $_SESSION['usuario_id'],

            'nombre' => limpiarTexto(
                $_POST['nombre'] ?? ''
            ),

            'apellido_paterno' => limpiarTexto(
                $_POST['apellido_paterno'] ?? ''
            ),

            'apellido_materno' => limpiarTexto(
                $_POST['apellido_materno'] ?? ''
            ),

            'correo' => trim(
                $_POST['correo'] ?? ''
            ),

            'telefono' => trim(
                $_POST['telefono'] ?? ''
            ),

            'actualizado_por' => $_SESSION['usuario_id']

        ];

        $errores = [];

        // =========================
        // Validaciones
        // =========================

        if (empty($datos['nombre'])) {

            $errores['nombre'] =
                'El nombre es obligatorio.';
        }

        if (empty($datos['apellido_paterno'])) {

            $errores['apellido_paterno'] =
                'El apellido paterno es obligatorio.';
        }

        if (empty($datos['apellido_materno'])) {

            $errores['apellido_materno'] =
                'El apellido materno es obligatorio.';
        }

        if (empty($datos['telefono'])) {

            $errores['telefono'] =
                'El teléfono es obligatorio.';
        }

        if (empty($datos['correo'])) {

            $errores['correo'] =
                'El correo es obligatorio.';
        }
        if (
            !empty($datos['correo']) &&
            !filter_var(
                $datos['correo'],
                FILTER_VALIDATE_EMAIL
            )
        ) {

            $errores['correo'] =
                'El correo electrónico no es válido.';
        }
        
        if (
            !empty($datos['nombre']) &&
            !preg_match(
                "/^[\p{L}\s'’-]+$/u",
                $datos['nombre']
            )
        ) {

            $errores['nombre'] =
                'El nombre contiene caracteres no permitidos.';
        }
        $modelo = new Usuario();

        // =========================
        // Validar correo duplicado
        // =========================

        if (
            $modelo->correoExiste(
                $datos['correo'],
                $_SESSION['usuario_id']
            )
        ) {

            $errores['correo'] =
                'Ese correo ya está registrado.';
        }

        if (!empty($errores)) {

            guardarOld($datos);

            guardarErrores($errores);

            guardarTab('settings');

            redirect('perfil');
        }

        $resultado = $modelo->actualizar(
            $datos
        );

        if (!$resultado) {

            mensaje(
                'No fue posible actualizar la información.',
                ALERT_DANGER,
                3000
            );

            redirect('perfil');
        }
        // =========================
        // Actualizar sesión
        // =========================

        $_SESSION['usuario_nombre'] =
            $datos['nombre'] . ' ' .
            $datos['apellido_paterno'];

        $_SESSION['correo'] =
            $datos['correo'];

        mensaje(
            'Información actualizada correctamente.',
            ALERT_SUCCESS,
            3000
        );

        redirect('perfil');

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