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

    // =========================
    // Obtener años disponibles
    // =========================
    public function obtenerAnios()
    {
        $sql = "
            SELECT DISTINCT anio
            FROM cotizaciones
            WHERE eliminado = 0
            ORDER BY anio DESC
        ";

        return $this->db
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Estadísticas por año
    // =========================
    public function obtenerEstadisticasPorAnio($anio)
    {
        $sql = "
            SELECT

                COUNT(*) AS total_cotizaciones,

                SUM(
                    CASE
                        WHEN estatus = 'enviado'
                        THEN 1
                        ELSE 0
                    END
                ) AS total_enviadas,

                SUM(
                    CASE
                        WHEN estatus = 'respaldo'
                        THEN 1
                        ELSE 0
                    END
                ) AS total_respaldo,

                SUM(
                    CASE
                        WHEN reenviar = 1
                        THEN 1
                        ELSE 0
                    END
                ) AS total_reenviar

            FROM cotizaciones

            WHERE anio = :anio
            AND eliminado = 0
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':anio' => $anio
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total_cotizaciones' => $resultado['total_cotizaciones'] ?? 0,
            'total_enviadas'     => $resultado['total_enviadas'] ?? 0,
            'total_respaldo'     => $resultado['total_respaldo'] ?? 0,
            'total_reenviar'     => $resultado['total_reenviar'] ?? 0
        ];
    }

    // =========================
    // Guardar cotización
    // =========================
    public function guardar($datos)
    {
        $sql = "
            INSERT INTO cotizaciones
            (
                fecha,
                req,
                numero,
                elaboro,
                partida,
                proveedor,
                analista,
                estatus,
                reenviar,
                anio,
                creado_por
            )
            VALUES
            (
                :fecha,
                :req,
                :numero,
                :elaboro,
                :partida,
                :proveedor,
                :analista,
                :estatus,
                :reenviar,
                :anio,
                :creado_por
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([

            ':fecha'      => $datos['fecha'],
            ':req'        => $datos['req'],
            ':numero'     => $datos['numero'],
            ':elaboro'    => $datos['elaboro'],
            ':partida'    => $datos['partida'],
            ':proveedor'  => $datos['proveedor'],
            ':analista'   => $datos['analista'],
            ':estatus'    => $datos['estatus'],
            ':reenviar'   => $datos['reenviar'],
            ':anio'       => $datos['anio'],
            ':creado_por' => $datos['creado_por']

        ]);
    }
}