<?php

require_once __DIR__ . '/../config/database.php';

class Documentos
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // =========================
    // OBTENER DOCUMENTOS
    // =========================
    public function obtenerPorRegistro($modulo, $registro_id)
    {
        $sql = "
            SELECT
                d.*,
                u.nombre AS usuario_nombre
            FROM documentos d
            LEFT JOIN usuarios u
                ON u.id = d.subido_por
            WHERE d.modulo = ?
              AND d.registro_id = ?
            ORDER BY d.fecha_creacion DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $modulo,
            (int) $registro_id
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // =========================
    // GUARDAR DOCUMENTO
    // =========================
    public function guardar(
        string $modulo,
        int $registro_id,
        string $tipo,
        string $nombre_original,
        string $nombre_archivo,
        string $ruta,
        string $extension,
        string $mime,
        int $tamano,
        ?int $subido_por = null
    ): int {

        $sql = "
            INSERT INTO documentos (
                modulo,
                registro_id,
                tipo,
                nombre_original,
                nombre_archivo,
                ruta,
                extension,
                mime,
                tamano,
                subido_por
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            $modulo,
            $registro_id,
            $tipo,
            $nombre_original,
            $nombre_archivo,
            $ruta,
            $extension,
            $mime,
            $tamano,
            $subido_por
        ]);

        return (int) $this->db->lastInsertId();
    }
}