<?php

require_once __DIR__ . '/../config/database.php';

class Cotizacion
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
            FROM cotizaciones
            WHERE anio = ?
            AND eliminado = 0
            ORDER BY id DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([$anio]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
    public function contarTotal()
    {
        $sql = "SELECT COUNT(*) total
                FROM cotizaciones
                WHERE eliminado = 0";

        return $this->db->query($sql)->fetch()['total'];
    }

    public function contarPorEstatus($estatus)
    {
        $sql = "SELECT COUNT(*) total
                FROM cotizaciones
                WHERE eliminado = 0
                AND estatus = :estatus";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':estatus' => $estatus
        ]);

        return $stmt->fetch()['total'];
    }

    public function contarReenviar()
    {
        $sql = "SELECT COUNT(*) total
                FROM cotizaciones
                WHERE eliminado = 0
                AND reenviar = 1";

        return $this->db->query($sql)->fetch()['total'];
    }

}