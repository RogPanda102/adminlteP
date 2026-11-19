<?php

require_once CORE_PATH . '/view.php';
require_once CONTROLLERS_PATH . 'BaseController.php';
require_once APP_PATH . '/helpers/funciones_globales.php';
require_once MODELS_PATH . 'Usuario.php';
require_once APP_PATH . '/services/CorreoService.php';

class AuthController extends BaseController
{

    // =========================
    // Vista Login
    // =========================
    public function login()
    {

        if (isset($_SESSION['logueado'])) {

            header('Location: ' . BASE_URL . 'dashboard');
            exit;

        }

        if (isset($_GET['sesion_expirada'])) {

            mensaje(
                'Tu sesión expiró por inactividad.',
                ALERT_WARNING,
                5000
            );

        }

        View::renderLogin('auth/login');

    }

    // =========================
    // Procesar Login
    // =========================
    public function autenticar()
    {

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        $usuario = trim($_POST['usuario'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($usuario) || empty($password)) {

            mensaje(
                'Todos los campos son obligatorios',
                ALERT_WARNING,
                3000
            );

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        try {

            $modeloUsuario = new Usuario();

            $usuarioDB = $modeloUsuario->buscarPorUsuario($usuario);

            if (!$usuarioDB) {

                mensaje(
                    'Usuario no encontrado',
                    ALERT_DANGER,
                    3000
                );

                header('Location: ' . BASE_URL . 'login');
                exit;

            }

            if (!password_verify($password, $usuarioDB['password'])) {

                mensaje(
                    'Contraseña incorrecta',
                    ALERT_DANGER,
                    3000
                );

                header('Location: ' . BASE_URL . 'login');
                exit;

            }

            if ($usuarioDB['estado'] != 'activo') {

                mensaje(
                    'Usuario deshabilitado',
                    ALERT_WARNING,
                    3000
                );

                header('Location: ' . BASE_URL . 'login');
                exit;

            }

            // =========================
            // VALIDAR CORREO
            // =========================

            if ((int) $usuarioDB['correo_verificado'] !== 1) {

                $_SESSION['usuario_verificacion_id'] = $usuarioDB['id'];

                mensaje(
                    'Debes verificar tu correo electrónico antes de iniciar sesión.',
                    ALERT_WARNING,
                    5000
                );

                header('Location: ' . BASE_URL . 'verificar-correo');
                exit;

            }

            // =========================
            // CREAR SESIÓN
            // =========================

            $_SESSION['logueado'] = true;

            $_SESSION['ultima_actividad'] = time();

            $_SESSION['usuario_id'] = $usuarioDB['id'];

            $_SESSION['usuario_nombre'] =
                $usuarioDB['nombre'] . ' ' .
                $usuarioDB['apellido_paterno'];

            $_SESSION['usuario'] = $usuarioDB['usuario'];

            $_SESSION['correo'] = $usuarioDB['correo'];

            $_SESSION['foto_usuario'] = $usuarioDB['foto'];

            $_SESSION['rol_actual'] = $usuarioDB['rol_id'];

            mensaje(
                'Bienvenido al sistema',
                ALERT_SUCCESS,
                3000
            );

            header('Location: ' . BASE_URL . 'dashboard');
            exit;

        } catch (Exception $e) {

            error_log('[AuthController] Error en login: ' . $e->getMessage());

            mensaje(
                'Error interno del sistema',
                ALERT_DANGER,
                3000
            );

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

    }

    // =========================
    // Logout
    // =========================
    public function logout()
    {

        $sesionExpirada =
            isset($_GET['sesion_expirada']) &&
            $_GET['sesion_expirada'] == '1';

        session_destroy();

        if ($sesionExpirada) {

            header(
                'Location: ' .
                BASE_URL .
                'login?sesion_expirada=1'
            );

            exit;
        }

        header(
            'Location: ' .
            BASE_URL .
            'login'
        );

        exit;

    }

    // =========================
    // Vista Registro
    // =========================
    public function registro()
    {

        if (isset($_SESSION['logueado'])) {

            header('Location: ' . BASE_URL . 'dashboard');
            exit;

        }

        View::renderLogin('auth/registro');

    }

    // =========================
    // Guardar Registro
    // =========================
    public function guardarRegistro()
    {

        if ($_SERVER['REQUEST_METHOD'] != 'POST') {

            header('Location: ' . BASE_URL . 'registro');
            exit;

        }

        $nombre = trim($_POST['nombre'] ?? '');
        $apellidoPaterno = trim($_POST['apellido_paterno'] ?? '');
        $apellidoMaterno = trim($_POST['apellido_materno'] ?? '');
        $usuario = trim($_POST['usuario'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirmar = trim($_POST['confirmar_password'] ?? '');

        // =========================
        // VALIDAR CAMPOS
        // =========================

        if (
            empty($nombre) ||
            empty($apellidoPaterno) ||
            empty($usuario) ||
            empty($correo) ||
            empty($password) ||
            empty($confirmar)
        ) {

            mensaje(
                'Todos los campos son obligatorios',
                ALERT_WARNING,
                3000
            );

            header('Location: ' . BASE_URL . 'registro');
            exit;

        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {

            mensaje(
                'Correo electrónico inválido',
                ALERT_WARNING,
                3000
            );

            header('Location: ' . BASE_URL . 'registro');
            exit;

        }

        if ($password !== $confirmar) {

            mensaje(
                'Las contraseñas no coinciden',
                ALERT_WARNING,
                3000
            );

            header('Location: ' . BASE_URL . 'registro');
            exit;

        }

        try {

            $modeloUsuario = new Usuario();

            // =========================
            // VALIDAR USUARIO EXISTENTE
            // =========================

            if ($modeloUsuario->buscarPorUsuario($usuario)) {

                mensaje(
                    'El usuario ya existe',
                    ALERT_WARNING,
                    3000
                );

                header('Location: ' . BASE_URL . 'registro');
                exit;

            }

            // =========================
            // VALIDAR CORREO EXISTENTE
            // =========================

            if ($modeloUsuario->buscarPorCorreo($correo)) {

                mensaje(
                    'El correo ya está registrado',
                    ALERT_WARNING,
                    3000
                );

                header('Location: ' . BASE_URL . 'registro');
                exit;

            }

            // =========================
            // CREAR USUARIO
            // =========================

            $passwordHash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $resultado = $modeloUsuario->crearUsuario([
                'nombre' => $nombre,
                'apellido_paterno' => $apellidoPaterno,
                'apellido_materno' => $apellidoMaterno,
                'usuario' => $usuario,
                'correo' => $correo,
                'password' => $passwordHash
            ]);

            if (!$resultado) {

                throw new Exception('No se pudo crear el usuario.');

            }

            // =========================
            // RECUPERAR USUARIO CREADO
            // =========================

            $usuarioDB = $modeloUsuario->buscarPorUsuario($usuario);

            if (!$usuarioDB) {

                throw new Exception('No se pudo recuperar el usuario creado.');

            }

            $usuarioId = (int) $usuarioDB['id'];

            // =========================
            // GENERAR CÓDIGO
            // =========================

            $codigo = (string) random_int(100000, 999999);

            $expira = date(
                'Y-m-d H:i:s',
                strtotime('+10 minutes')
            );

            $guardado = $modeloUsuario->guardarCodigoVerificacion(
                $usuarioId,
                $codigo,
                $expira
            );

            if (!$guardado) {

                throw new Exception('No se pudo guardar el código de verificación.');

            }

            // =========================
            // ENVIAR CORREO
            // =========================

            $asunto = 'Verificación de correo - PandaSoft';

            $mensajeHtml = '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 25px; color: #333;">
                    <h2 style="color: #0d6efd;">Bienvenido a PandaSoft</h2>

                    <p>Hola <strong>' . htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8') . '</strong>,</p>

                    <p>Para completar tu registro, utiliza el siguiente código de verificación:</p>

                    <div style="background: #f1f5f9; padding: 20px; text-align: center; border-radius: 8px; margin: 25px 0;">
                        <span style="font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #0d6efd;">
                            ' . $codigo . '
                        </span>
                    </div>

                    <p>Este código tiene una vigencia de <strong>10 minutos</strong>.</p>

                    <p>Si no solicitaste esta cuenta, puedes ignorar este mensaje.</p>

                    <hr style="border: 0; border-top: 1px solid #ddd; margin-top: 25px;">

                    <p style="font-size: 12px; color: #777;">PandaSoft - Sistema de administración</p>
                </div>
            ';

            $mensajeTexto =
                "Bienvenido a PandaSoft.\n\n" .
                "Tu código de verificación es: {$codigo}\n\n" .
                "Este código tiene una vigencia de 10 minutos.";

            $envio = CorreoService::enviar(
                $correo,
                $asunto,
                $mensajeHtml,
                $mensajeTexto
            );

            // =========================
            // PREPARAR VERIFICACIÓN
            // =========================

            $_SESSION['usuario_verificacion_id'] = $usuarioId;

            if ($envio['ok']) {

                mensaje(
                    'Cuenta creada. Te enviamos un código de verificación a tu correo.',
                    ALERT_SUCCESS,
                    5000
                );

            } else {

                error_log('[AuthController] No se pudo enviar el código: ' . $envio['mensaje']);

                mensaje(
                    'La cuenta fue creada, pero no pudimos enviar el código. Podrás solicitar otro desde la pantalla de verificación.',
                    ALERT_WARNING,
                    6000
                );

            }

            header('Location: ' . BASE_URL . 'verificar-correo');
            exit;

        } catch (Exception $e) {

            error_log('[AuthController] Error en registro: ' . $e->getMessage());

            mensaje(
                'Error al crear la cuenta. Verifica los datos e inténtalo nuevamente.',
                ALERT_DANGER,
                4000
            );

            header('Location: ' . BASE_URL . 'registro');
            exit;

        }

    }

        // =========================
    // Vista de verificación
    // =========================
    public function verificarCorreo()
    {
        if (empty($_SESSION['usuario_verificacion_id'])) {

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        try {

            $modeloUsuario = new Usuario();

            $usuario = $modeloUsuario->buscarPorId(
                $_SESSION['usuario_verificacion_id']
            );

            if (!$usuario) {

                unset($_SESSION['usuario_verificacion_id']);

                header('Location: ' . BASE_URL . 'registro');
                exit;

            }

            if ((int) $usuario['correo_verificado'] === 1) {

                unset($_SESSION['usuario_verificacion_id']);

                header('Location: ' . BASE_URL . 'login');
                exit;

            }

            View::renderLogin('auth/verificar-correo');

        } catch (Exception $e) {

            error_log('[AuthController] Error al mostrar verificación: ' . $e->getMessage());

            mensaje(
                'Ocurrió un error al cargar la verificación.',
                ALERT_DANGER,
                3000
            );

            header('Location: ' . BASE_URL . 'login');
            exit;

        }
    }

    // =========================
    // Confirmar código
    // =========================
    public function confirmarCodigo()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: ' . BASE_URL . 'verificar-correo');
            exit;

        }

        if (empty($_SESSION['usuario_verificacion_id'])) {

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        $codigo = trim($_POST['codigo'] ?? '');

        if (!preg_match('/^[0-9]{6}$/', $codigo)) {

            mensaje(
                'Ingresa un código válido de 6 dígitos.',
                ALERT_WARNING,
                3000
            );

            header('Location: ' . BASE_URL . 'verificar-correo');
            exit;

        }

        try {

            $modeloUsuario = new Usuario();

            $usuarioId = (int) $_SESSION['usuario_verificacion_id'];

            $usuario = $modeloUsuario->verificarCodigo(
                $usuarioId,
                $codigo
            );

            if (!$usuario) {

                mensaje(
                    'El código es incorrecto o ha expirado.',
                    ALERT_DANGER,
                    4000
                );

                header('Location: ' . BASE_URL . 'verificar-correo');
                exit;

            }

            $resultado = $modeloUsuario->marcarCorreoVerificado(
                $usuarioId
            );

            if (!$resultado) {

                throw new Exception('No se pudo verificar el correo.');

            }

            unset($_SESSION['usuario_verificacion_id']);

            mensaje(
                'Tu correo electrónico ha sido verificado correctamente. Ya puedes iniciar sesión.',
                ALERT_SUCCESS,
                5000
            );

            header('Location: ' . BASE_URL . 'login');
            exit;

        } catch (Exception $e) {

            error_log('[AuthController] Error al confirmar código: ' . $e->getMessage());

            mensaje(
                'Ocurrió un error al verificar tu correo.',
                ALERT_DANGER,
                3000
            );

            header('Location: ' . BASE_URL . 'verificar-correo');
            exit;

        }
    }

    // =========================
    // Reenviar código
    // =========================
    public function reenviarCodigo()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: ' . BASE_URL . 'verificar-correo');
            exit;

        }

        if (empty($_SESSION['usuario_verificacion_id'])) {

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        try {

            $modeloUsuario = new Usuario();

            $usuarioId = (int) $_SESSION['usuario_verificacion_id'];

            $usuario = $modeloUsuario->buscarPorId($usuarioId);

            if (!$usuario) {

                unset($_SESSION['usuario_verificacion_id']);

                header('Location: ' . BASE_URL . 'registro');
                exit;

            }

            if ((int) $usuario['correo_verificado'] === 1) {

                unset($_SESSION['usuario_verificacion_id']);

                header('Location: ' . BASE_URL . 'login');
                exit;

            }

            // Generar nuevo código
            $codigo = (string) random_int(100000, 999999);

            $expira = date(
                'Y-m-d H:i:s',
                strtotime('+10 minutes')
            );

            $guardado = $modeloUsuario->guardarCodigoVerificacion(
                $usuarioId,
                $codigo,
                $expira
            );

            if (!$guardado) {

                throw new Exception('No se pudo guardar el nuevo código.');

            }

            // Preparar correo
            $nombre = htmlspecialchars(
                $usuario['nombre'],
                ENT_QUOTES,
                'UTF-8'
            );

            $asunto = 'Nuevo código de verificación - PandaSoft';

            $mensajeHtml = '
                <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 25px; color: #333;">

                    <h2 style="color: #0d6efd;">Verificación de correo</h2>

                    <p>Hola <strong>' . $nombre . '</strong>,</p>

                    <p>Has solicitado un nuevo código de verificación para tu cuenta de PandaSoft.</p>

                    <div style="background: #f1f5f9; padding: 20px; text-align: center; border-radius: 8px; margin: 25px 0;">

                        <span style="font-size: 32px; font-weight: bold; letter-spacing: 8px; color: #0d6efd;">
                            ' . $codigo . '
                        </span>

                    </div>

                    <p>Este código tiene una vigencia de <strong>10 minutos</strong>.</p>

                    <hr style="border: 0; border-top: 1px solid #ddd; margin-top: 25px;">

                    <p style="font-size: 12px; color: #777;">
                        PandaSoft - Sistema de administración
                    </p>

                </div>
            ';

            $mensajeTexto =
                "Hola {$usuario['nombre']}.\n\n" .
                "Tu nuevo código de verificación es: {$codigo}\n\n" .
                "Este código tiene una vigencia de 10 minutos.";

            $envio = CorreoService::enviar(
                $usuario['correo'],
                $asunto,
                $mensajeHtml,
                $mensajeTexto
            );

            if ($envio['ok']) {

                mensaje(
                    'Te enviamos un nuevo código de verificación.',
                    ALERT_SUCCESS,
                    4000
                );

            } else {

                error_log('[AuthController] Error al reenviar código: ' . $envio['mensaje']);

                mensaje(
                    'No pudimos enviar el correo. Inténtalo nuevamente.',
                    ALERT_WARNING,
                    4000
                );

            }

            header('Location: ' . BASE_URL . 'verificar-correo');
            exit;

        } catch (Exception $e) {

            error_log('[AuthController] Error al reenviar código: ' . $e->getMessage());

            mensaje(
                'Ocurrió un error al reenviar el código.',
                ALERT_DANGER,
                3000
            );

            header('Location: ' . BASE_URL . 'verificar-correo');
            exit;

        }
    }

}
