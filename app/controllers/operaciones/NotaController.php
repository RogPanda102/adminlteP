<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/Notas.php';

class NotaController extends BaseController
{
    private $modelo;

    public function __construct()
    {
        parent::__construct();

        $this->modelo = new Nota();
    }

    // =========================
    // Validar sesión
    // =========================
    private function validarSesion()
    {
        if (!isset($_SESSION['logueado'], $_SESSION['usuario_id'])) {

            $this->responderJSON([
                'success' => false,
                'mensaje' => 'La sesión ha expirado.'
            ], 401);
        }
    }

    // =========================
    // Respuesta JSON
    // =========================
    private function responderJSON($datos, $codigo = 200)
    {
        http_response_code($codigo);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            $datos,
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }

    // =========================
    // Validar color
    // =========================
    private function validarColor($color)
    {
        $coloresPermitidos = [
            'amarillo',
            'azul',
            'verde',
            'rosa',
            'morado'
        ];

        return in_array($color, $coloresPermitidos, true);
    }

        // =========================
    // Listar notas del usuario
    // =========================
    public function listar()
    {
        $this->validarSesion();

        try {

            $usuarioId = (int) $_SESSION['usuario_id'];

            $notas = $this->modelo->obtenerPorUsuario($usuarioId);

            $this->responderJSON([
                'success' => true,
                'notas' => $notas
            ]);

        } catch (Throwable $e) {

            error_log('Error al listar notas: ' . $e->getMessage());

            $this->responderJSON([
                'success' => false,
                'mensaje' => 'No fue posible obtener las notas.'
            ], 500);
        }
    }

    // =========================
    // Crear nota
    // =========================
    public function guardar()
    {
        $this->validarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            $this->responderJSON([
                'success' => false,
                'mensaje' => 'Método no permitido.'
            ], 405);
        }

        try {

            $titulo = trim($_POST['titulo'] ?? '');
            $contenido = trim($_POST['contenido'] ?? '');
            $color = $_POST['color'] ?? 'amarillo';

            // Validar título
            if (mb_strlen($titulo) > 150) {

                $this->responderJSON([
                    'success' => false,
                    'mensaje' => 'El título no puede superar los 150 caracteres.'
                ], 422);
            }

            // Evitar notas vacías
            if ($titulo === '' && $contenido === '') {

                $this->responderJSON([
                    'success' => false,
                    'mensaje' => 'La nota no puede estar vacía.'
                ], 422);
            }

            // Validar color
            if (!$this->validarColor($color)) {
                $color = 'amarillo';
            }

            $datos = [
                'usuario_id' => (int) $_SESSION['usuario_id'],
                'titulo' => $titulo,
                'contenido' => $contenido,
                'color' => $color,
                'fijada' => 0,
                'estado' => 1
            ];

            $id = $this->modelo->guardar($datos);

            if (!$id) {

                $this->responderJSON([
                    'success' => false,
                    'mensaje' => 'No fue posible guardar la nota.'
                ], 500);
            }

            $this->responderJSON([
                'success' => true,
                'mensaje' => 'Nota creada correctamente.',
                'id' => $id
            ], 201);

        } catch (Throwable $e) {

            error_log('Error al guardar nota: ' . $e->getMessage());

            $this->responderJSON([
                'success' => false,
                'mensaje' => 'Ocurrió un error al guardar la nota.'
            ], 500);
        }
    }
        // =========================
    // Actualizar nota
    // =========================
    public function actualizar()
    {
        $this->validarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            $this->responderJSON([
                'success' => false,
                'mensaje' => 'Método no permitido.'
            ], 405);
        }

        try {

            $id = (int) ($_POST['id'] ?? 0);

            $usuarioId = (int) $_SESSION['usuario_id'];

            $nota = $this->modelo->buscarPorId($id, $usuarioId);

            if (!$nota) {

                $this->responderJSON([
                    'success' => false,
                    'mensaje' => 'La nota no existe o no tienes acceso.'
                ], 404);
            }

            $titulo = trim($_POST['titulo'] ?? '');
            $contenido = $_POST['contenido'] ?? '';
            $color = $_POST['color'] ?? 'amarillo';
            $fijada = (int) ($_POST['fijada'] ?? 0);

            if (mb_strlen($titulo) > 150) {

                $this->responderJSON([
                    'success' => false,
                    'mensaje' => 'El título no puede superar los 150 caracteres.'
                ], 422);
            }

            if (trim($contenido) === '' && $titulo === '') {

                $this->responderJSON([
                    'success' => false,
                    'mensaje' => 'La nota no puede estar vacía.'
                ], 422);
            }

            if (!$this->validarColor($color)) {
                $color = 'amarillo';
            }

            $resultado = $this->modelo->actualizar([
                'id' => $id,
                'usuario_id' => $usuarioId,
                'titulo' => $titulo,
                'contenido' => $contenido,
                'color' => $color,
                'fijada' => $fijada ? 1 : 0
            ]);

            $this->responderJSON([
                'success' => (bool) $resultado,
                'mensaje' => $resultado
                    ? 'Nota actualizada correctamente.'
                    : 'No fue posible actualizar la nota.'
            ], $resultado ? 200 : 500);

        } catch (Throwable $e) {

            error_log('Error al actualizar nota: ' . $e->getMessage());

            $this->responderJSON([
                'success' => false,
                'mensaje' => 'Ocurrió un error al actualizar la nota.'
            ], 500);
        }
    }

    // =========================
    // Eliminar nota
    // =========================
    public function eliminar()
    {
        $this->validarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            $this->responderJSON([
                'success' => false,
                'mensaje' => 'Método no permitido.'
            ], 405);
        }

        try {

            $id = (int) ($_POST['id'] ?? 0);

            $usuarioId = (int) $_SESSION['usuario_id'];

            $nota = $this->modelo->buscarPorId($id, $usuarioId);

            if (!$nota) {

                $this->responderJSON([
                    'success' => false,
                    'mensaje' => 'La nota no existe o no tienes acceso.'
                ], 404);
            }

            $resultado = $this->modelo->eliminar($id, $usuarioId);

            $this->responderJSON([
                'success' => (bool) $resultado,
                'mensaje' => $resultado
                    ? 'Nota eliminada correctamente.'
                    : 'No fue posible eliminar la nota.'
            ]);

        } catch (Throwable $e) {

            error_log('Error al eliminar nota: ' . $e->getMessage());

            $this->responderJSON([
                'success' => false,
                'mensaje' => 'Ocurrió un error al eliminar la nota.'
            ], 500);
        }
    }

    // =========================
    // Cambiar estado de nota
    // =========================
    public function cambiarEstado()
    {
        $this->validarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            $this->responderJSON([
                'success' => false,
                'mensaje' => 'Método no permitido.'
            ], 405);
        }

        try {

            $id = (int) ($_POST['id'] ?? 0);

            $estado = filter_var(
                $_POST['estado'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (!in_array($estado, [0, 1], true)) {

                $this->responderJSON([
                    'success' => false,
                    'mensaje' => 'Estado no válido.'
                ], 422);
            }

            $resultado = $this->modelo->cambiarEstado(
                $id,
                (int) $_SESSION['usuario_id'],
                $estado
            );

            $this->responderJSON([
                'success' => (bool) $resultado,
                'mensaje' => $resultado
                    ? 'Estado actualizado correctamente.'
                    : 'No fue posible actualizar el estado.'
            ]);

        } catch (Throwable $e) {

            error_log('Error al cambiar estado de nota: ' . $e->getMessage());

            $this->responderJSON([
                'success' => false,
                'mensaje' => 'Ocurrió un error al cambiar el estado.'
            ], 500);
        }
    }

    // =========================
    // Fijar / desfijar nota
    // =========================
    public function cambiarFijada()
    {
        $this->validarSesion();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            $this->responderJSON([
                'success' => false,
                'mensaje' => 'Método no permitido.'
            ], 405);
        }

        try {

            $id = (int) ($_POST['id'] ?? 0);

            $fijada = filter_var(
                $_POST['fijada'] ?? null,
                FILTER_VALIDATE_INT
            );

            if (!in_array($fijada, [0, 1], true)) {

                $this->responderJSON([
                    'success' => false,
                    'mensaje' => 'Valor de fijación no válido.'
                ], 422);
            }

            $resultado = $this->modelo->cambiarFijada(
                $id,
                (int) $_SESSION['usuario_id'],
                $fijada
            );

            $this->responderJSON([
                'success' => (bool) $resultado,
                'mensaje' => $resultado
                    ? 'Nota actualizada correctamente.'
                    : 'No fue posible actualizar la nota.'
            ]);

        } catch (Throwable $e) {

            error_log('Error al fijar nota: ' . $e->getMessage());

            $this->responderJSON([
                'success' => false,
                'mensaje' => 'Ocurrió un error al fijar la nota.'
            ], 500);
        }
    }



}