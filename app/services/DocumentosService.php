<?php

class DocumentosService
{
    // =========================
    // RUTA BASE DE DOCUMENTOS
    // =========================
    private $rutaBase;


    // =========================
    // CONSTRUCTOR
    // =========================
    public function __construct()
    {
        $this->rutaBase = dirname(__DIR__, 2) . '/storage/documentos';
    }

        // =========================
    // OBTENER RUTA DEL DOCUMENTO
    // =========================
    private function obtenerRutaDestino(
        string $modulo,
        int $registro_id,
        string $tipo
    ): string {

        return $this->rutaBase
            . DIRECTORY_SEPARATOR
            . $modulo
            . DIRECTORY_SEPARATOR
            . $registro_id
            . DIRECTORY_SEPARATOR
            . $tipo;
    }

        // =========================
    // CREAR DIRECTORIO
    // =========================
    private function crearDirectorio(string $ruta): void
    {
        if (is_dir($ruta)) {
            return;
        }

        if (!mkdir($ruta, 0755, true) && !is_dir($ruta)) {
            throw new RuntimeException(
                'No se pudo crear el directorio de documentos.'
            );
        }
    }

    // =========================
    // GENERAR NOMBRE FÍSICO
    // =========================
    private function generarNombreArchivo(string $extension): string
    {
        return bin2hex(random_bytes(16))
            . '.' . strtolower($extension);
    }

    // =========================
    // GUARDAR ARCHIVO FÍSICO
    // =========================
    private function guardarArchivoFisico(
        array $archivo,
        string $rutaDestino,
        string $nombreArchivo
    ): string {

        $this->crearDirectorio($rutaDestino);

        $rutaCompleta = $rutaDestino
            . DIRECTORY_SEPARATOR
            . $nombreArchivo;

        if (!move_uploaded_file(
            $archivo['tmp_name'],
            $rutaCompleta
        )) {

            throw new RuntimeException(
                'No se pudo guardar físicamente el archivo.'
            );
        }

        return $rutaCompleta;
    }

    // =========================
    // GUARDAR DOCUMENTO
    // =========================
    public function guardar(
        array $archivo,
        string $modulo,
        int $registro_id,
        string $tipo,
        string $extension
    ): array {

        // =========================
        // RUTA DESTINO
        // =========================

        $rutaDestino = $this->obtenerRutaDestino(
            $modulo,
            $registro_id,
            $tipo
        );

        // =========================
        // NOMBRE FÍSICO
        // =========================

        $nombreArchivo = $this->generarNombreArchivo(
            $extension
        );

        // =========================
        // GUARDAR ARCHIVO
        // =========================

        $rutaCompleta = $this->guardarArchivoFisico(
            $archivo,
            $rutaDestino,
            $nombreArchivo
        );

        // =========================
        // RUTA RELATIVA
        // =========================

        $rutaRelativa =
            'storage/documentos/'
            . $modulo
            . '/'
            . $registro_id
            . '/'
            . $tipo
            . '/'
            . $nombreArchivo;

        // =========================
        // RESPUESTA
        // =========================

        return [
            'nombre_archivo' => $nombreArchivo,
            'ruta' => $rutaRelativa,
            'ruta_fisica' => $rutaCompleta
        ];
    }

    // =========================
    // OBTENER EXTENSIÓN POR MIME
    // =========================
    public function obtenerExtensionPorMime(
        string $mime
    ): ?string {

        $extensiones = [

            'application/pdf' => 'pdf',

            'image/jpeg' => 'jpg',

            'image/png' => 'png',

            'application/msword' => 'doc',

            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',

            'application/vnd.ms-excel' => 'xls',

            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx'

        ];

        return $extensiones[$mime] ?? null;
    }
}