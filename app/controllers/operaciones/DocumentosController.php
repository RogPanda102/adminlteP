<?php

require_once __DIR__ . '/../BaseController.php';
require_once __DIR__ . '/../../models/Documentos.php';
require_once __DIR__ . '/../../services/DocumentosService.php';

class DocumentosController extends BaseController
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
    // OBTENER DOCUMENTOS AJAX
    // =========================
    public function documentos()
    {
        header('Content-Type: application/json');

        if (!$this->permitido) {
            http_response_code(403);

            echo json_encode([
                'success' => false,
                'message' => 'No autorizado'
            ]);

            exit;
        }

        $modulo = trim($_GET['modulo'] ?? '');

        $registro_id = (int) (
            $_GET['registro_id'] ?? 0
        );

        if (
            $modulo === '' ||
            $registro_id <= 0
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'Datos inválidos'
            ]);

            exit;
        }

        $modelo = new Documentos();

        $documentos = $modelo->obtenerPorRegistro(
            $modulo,
            $registro_id
        );

        echo json_encode([
            'success' => true,
            'data' => $documentos
        ]);

        exit;
    }


    // =========================
    // VER DOCUMENTO
    // =========================
    public function ver()
    {
        // =========================
        // AUTORIZACIÓN
        // =========================
        if (!$this->permitido) {
            http_response_code(403);
            exit('No autorizado');
        }


        // =========================
        // OBTENER ID
        // =========================
        $documentoId = (int) (
            $_GET['id'] ?? 0
        );


        // =========================
        // VALIDAR ID
        // =========================
        if ($documentoId <= 0) {
            http_response_code(400);
            exit('Documento inválido');
        }


        // =========================
        // OBTENER DOCUMENTO
        // =========================
        $modelo = new Documentos();

        $documento = $modelo->obtenerPorId(
            $documentoId
        );


        // =========================
        // DOCUMENTO NO EXISTE
        // =========================
        if (!$documento) {
            http_response_code(404);
            exit('Documento no encontrado');
        }


        // =========================
        // DATOS DEL DOCUMENTO
        // =========================
        $modulo = $documento['modulo'] ?? '';
        $registroId = (int) (
            $documento['registro_id'] ?? 0
        );
        $tipo = $documento['tipo'] ?? '';
        $nombreArchivo = $documento['nombre_archivo'] ?? '';
        $mime = $documento['mime'] ?? 'application/octet-stream';


        // =========================
        // VALIDAR DATOS
        // =========================
        $modulosPermitidos = [
            'cotizaciones',
            'servicios',
            'adjudicados'
        ];

        $tiposPermitidos = [
            'solicitud',
            'cotizacion',
            'entrega',
            'factura',
            'evidencia',
            'otro'
        ];


        if (
            !in_array(
                $modulo,
                $modulosPermitidos,
                true
            ) ||
            !in_array(
                $tipo,
                $tiposPermitidos,
                true
            ) ||
            $registroId <= 0 ||
            $nombreArchivo === ''
        ) {
            http_response_code(400);
            exit('Datos del documento inválidos');
        }


        // =========================
        // VALIDAR NOMBRE FÍSICO
        // =========================
        if (
            basename($nombreArchivo) !== $nombreArchivo
        ) {
            http_response_code(400);
            exit('Nombre de archivo inválido');
        }


        // =========================
        // RUTA FÍSICA
        // =========================
        $rutaBase =
            dirname(__DIR__, 3)
            . DIRECTORY_SEPARATOR
            . 'storage'
            . DIRECTORY_SEPARATOR
            . 'documentos';


        $rutaArchivo =
            $rutaBase
            . DIRECTORY_SEPARATOR
            . $modulo
            . DIRECTORY_SEPARATOR
            . $registroId
            . DIRECTORY_SEPARATOR
            . $tipo
            . DIRECTORY_SEPARATOR
            . $nombreArchivo;


        // =========================
        // VERIFICAR ARCHIVO
        // =========================
        if (!is_file($rutaArchivo)) {
            http_response_code(404);
            exit('Archivo no encontrado');
        }


        // =========================
        // VERIFICAR LECTURA
        // =========================
        if (!is_readable($rutaArchivo)) {
            http_response_code(403);
            exit('No se puede acceder al archivo');
        }


        // =========================
        // LIMPIAR BUFFER
        // =========================
        while (ob_get_level() > 0) {
            ob_end_clean();
        }


        // =========================
        // INFORMACIÓN DEL ARCHIVO
        // =========================
        $tamano = filesize($rutaArchivo);

        if ($tamano === false) {
            http_response_code(500);
            exit('No se pudo determinar el tamaño del archivo');
        }


        // =========================
        // MIME
        // =========================
        $finfo = new finfo(
            FILEINFO_MIME_TYPE
        );

        $mimeReal = $finfo->file(
            $rutaArchivo
        );


        if ($mimeReal) {
            $mime = $mimeReal;
        }


        // =========================
        // NOMBRE ORIGINAL
        // =========================
        $nombreOriginal =
            $documento['nombre_original']
            ?? $nombreArchivo;


        // =========================
        // HEADERS
        // =========================
        header(
            'Content-Type: ' . $mime
        );

        header(
            'Content-Length: ' . $tamano
        );

        header(
            'Content-Disposition: inline; filename="' .
            addslashes($nombreOriginal) .
            '"'
        );

        header(
            'X-Content-Type-Options: nosniff'
        );

        header(
            'Cache-Control: private, max-age=0, must-revalidate'
        );


        // =========================
        // ENVIAR ARCHIVO
        // =========================
        readfile($rutaArchivo);

        exit;
    }


    // =========================
    // SUBIR DOCUMENTO AJAX
    // =========================
    public function subirDocumento()
    {
        // =========================
        // SERVICIO DE DOCUMENTOS
        // =========================
        $servicio = new DocumentosService();

        header('Content-Type: application/json');

        // =========================
        // AUTORIZACIÓN
        // =========================
        if (!$this->permitido) {
            http_response_code(403);

            echo json_encode([
                'success' => false,
                'message' => 'No autorizado'
            ]);

            exit;
        }

        // =========================
        // MÉTODO
        // =========================
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);

            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido'
            ]);

            exit;
        }

        // =========================
        // DATOS
        // =========================
        $modulo = trim(
            $_POST['modulo'] ?? ''
        );

        $registro_id = (int) (
            $_POST['registro_id'] ?? 0
        );

        $tipo = trim(
            $_POST['tipo'] ?? ''
        );

        // =========================
        // VALIDAR DATOS
        // =========================
        if (
            $modulo === '' ||
            $registro_id <= 0 ||
            $tipo === ''
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'Datos del documento inválidos'
            ]);

            exit;
        }

        // =========================
        // VALIDAR ARCHIVO
        // =========================
        if (
            !isset($_FILES['archivo']) ||
            $_FILES['archivo']['error'] !== UPLOAD_ERR_OK
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'No se recibió correctamente el archivo'
            ]);

            exit;
        }

        $archivo = $_FILES['archivo'];

        // =========================
        // MIME REAL
        // =========================
        $finfo = new finfo(
            FILEINFO_MIME_TYPE
        );

        $mimeReal = $finfo->file(
            $archivo['tmp_name']
        );

        // =========================
        // TIPOS MIME PERMITIDOS
        // =========================
        $mimesPermitidos = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];

        // =========================
        // VALIDAR MIME
        // =========================
        if (
            !in_array(
                $mimeReal,
                $mimesPermitidos,
                true
            )
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'El tipo de archivo no está permitido.',
                'data' => [
                    'mime' => $mimeReal
                ]
            ]);

            exit;
        }

        // =========================
        // VALIDAR TAMAÑO
        // =========================
        $maximoBytes = 5 * 1024 * 1024; // 5 MB

        if (
            $archivo['size'] > $maximoBytes
        ) {
            echo json_encode([
                'success' => false,
                'message' => 'El archivo supera el tamaño máximo permitido de 5 MB.',
                'data' => [
                    'tamano' => $archivo['size']
                ]
            ]);

            exit;
        }

        // =========================
        // OBTENER EXTENSIÓN
        // =========================
        $extension = $servicio->obtenerExtensionPorMime(
            $mimeReal
        );

        // =========================
        // GUARDAR ARCHIVO FÍSICO
        // =========================
        try {

            $resultado = $servicio->guardar(
                $archivo,
                $modulo,
                $registro_id,
                $tipo,
                $extension
            );

        } catch (Throwable $e) {

            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);

            exit;
        }

        // =========================
        // GUARDAR EN BASE DE DATOS
        // =========================
        try {

            $modelo = new Documentos();

            $subidoPor = isset($_SESSION['usuario_id'])
                ? (int) $_SESSION['usuario_id']
                : null;

            $documentoId = $modelo->guardar(
                $modulo,
                $registro_id,
                $tipo,
                $archivo['name'],
                $resultado['nombre_archivo'],
                $resultado['ruta'],
                $extension,
                $mimeReal,
                (int) $archivo['size'],
                $subidoPor
            );

        } catch (Throwable $e) {

            // Si falla la BD, eliminamos el archivo físico
            if (
                isset($resultado['ruta_fisica']) &&
                is_file($resultado['ruta_fisica'])
            ) {
                unlink($resultado['ruta_fisica']);
            }

            echo json_encode([
                'success' => false,
                'message' => 'No se pudo registrar el documento.',
                'error' => $e->getMessage()
            ]);

            exit;
        }

        // =========================
        // RESPUESTA
        // =========================
        echo json_encode([
            'success' => true,
            'message' => 'Documento guardado correctamente',
            'data' => [
                'id' => $documentoId,
                'nombre' => $archivo['name'],
                'mime' => $mimeReal,
                'extension' => $extension,
                'tamano' => $archivo['size'],
                'tipo' => $tipo,
                'modulo' => $modulo,
                'registro_id' => $registro_id,
                'nombre_archivo' => $resultado['nombre_archivo'],
                'ruta' => $resultado['ruta']
            ]
        ]);

        exit;
    }
}