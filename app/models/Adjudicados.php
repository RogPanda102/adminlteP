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

}