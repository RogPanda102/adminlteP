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

}