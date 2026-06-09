<?php

require_once CORE_PATH . '/view.php';
require_once CONTROLLERS_PATH . 'BaseController.php';
require_once APP_PATH . '/helpers/funciones_globales.php';
require_once MODELS_PATH . 'Usuario.php';

class AuthController extends BaseController
{

    // =========================
    // Vista Login
    // =========================
    public function login()
    {

        // Si ya inició sesión
        if (isset($_SESSION['logueado'])) {

            header('Location: ' . BASE_URL . 'dashboard');
            exit;

        }

        View::renderLogin('auth/login');

    }

    // =========================
    // Procesar Login
    // =========================
    public function autenticar()
    {

        // Validar método POST
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {

            header('Location: ' . BASE_URL . 'login');
            exit;

        }

        // Capturar datos
        $usuario = trim($_POST['usuario'] ?? '');
        $password = trim($_POST['password'] ?? '');

        // Validar campos vacíos
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

            // Modelo usuario
            $modeloUsuario = new Usuario();

            // Buscar usuario
            $usuarioDB = $modeloUsuario->buscarPorUsuario($usuario);

            // Usuario no encontrado
            if (!$usuarioDB) {

                mensaje(
                    'Usuario no encontrado',
                    ALERT_DANGER,
                    3000
                );

                header('Location: ' . BASE_URL . 'login');
                exit;

            }

            // Verificar contraseña
            if (!password_verify($password, $usuarioDB['password'])) {

                mensaje(
                    'Contraseña incorrecta',
                    ALERT_DANGER,
                    3000
                );

                header('Location: ' . BASE_URL . 'login');
                exit;

            }
                //             // DEBUG ESTATUS
                // var_dump($usuarioDB['estado']);
                // exit;


            // Validar estatus
            if ($usuarioDB['estado'] != 'activo') {
                

                mensaje(
                    'Usuario deshabilitado',
                    ALERT_WARNING,
                    3000
                );

                header('Location: ' . BASE_URL . 'login');
                exit;

            }

            // DEBUG
            // echo '<pre>';
            // print_r($usuarioDB);
            // exit;
            // =========================
            // CREAR SESIÓN
            // =========================

            $_SESSION['logueado'] = true;

            $_SESSION['usuario_id'] = $usuarioDB['id'];

            $_SESSION['usuario_nombre'] =
                $usuarioDB['nombre'] . ' ' .
                $usuarioDB['apellido_paterno'];

            $_SESSION['usuario'] = $usuarioDB['usuario'];

            $_SESSION['correo'] = $usuarioDB['correo'];

            $_SESSION['foto_usuario'] = $usuarioDB['foto'];

            $_SESSION['rol_actual'] = $usuarioDB['rol_id'];
            

            // Mensaje éxito
            mensaje(
                'Bienvenido al sistema',
                ALERT_SUCCESS,
                3000
            );

            
            // Redirección
            header('Location: ' . BASE_URL . 'dashboard');
            exit;

        } catch (Exception $e) {

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

        session_destroy();

        header('Location: ' . BASE_URL . 'login');
        exit;

    }

}