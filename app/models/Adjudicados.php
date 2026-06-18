<?php

require_once __DIR__ . '/../config/database.php';

class Adjudicados
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }
    // =========================
    // Obtener cotizaciones por año
    // =========================
    public function obtenerPorAnio($anio)
    {
        $sql = "
            SELECT *
            FROM adjudicados
            WHERE anio = ?
            AND eliminado = 0
            ORDER BY id DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$anio]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    public function buscarDependencias($texto)
    {
        $sql = "
            SELECT DISTINCT dependencia
            FROM adjudicados
            WHERE dependencia IS NOT NULL
            AND dependencia <> ''
            AND dependencia LIKE :texto
            ORDER BY dependencia ASC
            LIMIT 10
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':texto' => "%{$texto}%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Actualizar adjudicación
    // =========================
    public function actualizar($datos)
    {
        $sql = "
            UPDATE adjudicados
            SET
                req = ?,
                folio = ?,
                elaboro = ?,
                partida = ?,
                analista = ?,
                total = ?,
                pago = ?,
                dependencia = ?,
                actualizado_por = ?
            WHERE id = ?
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            $datos['req'],
            $datos['folio'],
            $datos['elaboro'],
            $datos['partida'],
            $datos['analista'],
            $datos['total'],
            $datos['pago'],
            $datos['dependencia'],
            $datos['actualizado_por'],
            $datos['id']
        ]);
    }

    // =========================
    // Buscar por ID
    // =========================
    public function buscarPorId($id)
    {
        $sql = "
            SELECT *
            FROM adjudicados
            WHERE id = ?
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}