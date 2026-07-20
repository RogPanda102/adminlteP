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
            SELECT
                ad.*,

                CONCAT(
                    a.nombre,
                    ' ',
                    a.apellido_paterno,
                    IF(
                        a.apellido_materno IS NULL
                        OR a.apellido_materno='',
                        '',
                        CONCAT(' ',a.apellido_materno)
                    )
                ) AS analista

            FROM adjudicados ad

            LEFT JOIN analistas a
                ON a.id = ad.analista_id

            WHERE ad.anio = :anio
            AND ad.eliminado = 0

            ORDER BY ad.id DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':anio'=>$anio
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
                req = :req,
                folio = :folio,
                elaboro = :elaboro,
                partida = :partida,
                analista_id = :analista_id,
                fecha_elaboracion = :fecha_elaboracion,
                fecha_inicio_entrega = :fecha_inicio_entrega,
                fecha_fin_entrega = :fecha_fin_entrega,
                total = :total,
                dia_pago = :dia_pago,
                pago = :pago,
                dependencia = :dependencia,
                cotizacion_id = :cotizacion_id,
                anio = :anio,
                actualizado_por = :actualizado_por
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':req' => $datos['req'],
            ':folio' => $datos['folio'],
            ':elaboro' => $datos['elaboro'],
            ':partida' => $datos['partida'],
            ':analista_id' => $datos['analista_id'] ?? null,
            ':fecha_elaboracion' => $datos['fecha_elaboracion'],
            ':fecha_inicio_entrega' => $datos['fecha_inicio_entrega'],
            ':fecha_fin_entrega' => $datos['fecha_fin_entrega'],
            ':total' => $datos['total'],
            ':dia_pago' => $datos['dia_pago'],
            ':pago' => $datos['pago'],
            ':dependencia' => $datos['dependencia'],
            ':cotizacion_id' => $datos['cotizacion_id'],
            ':anio' => $datos['anio'],
            ':actualizado_por' => $datos['actualizado_por'],
            ':id' => $datos['id']
        ]);
    }

    // =========================
    // Buscar por ID
    // =========================
    public function buscarPorId($id)
    {
        $sql = "
            SELECT
                ad.*,

                CONCAT(
                    a.nombre,
                    ' ',
                    a.apellido_paterno,
                    IF(
                        a.apellido_materno IS NULL
                        OR a.apellido_materno = '',
                        '',
                        CONCAT(' ', a.apellido_materno)
                    )
                ) AS analista

            FROM adjudicados ad

            LEFT JOIN analistas a
                ON a.id = ad.analista_id

            WHERE ad.id = :id

            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    // =========================
    // Guardar adjudicación
    // =========================
    public function guardar($datos)
    {
        $sql = "
            INSERT INTO adjudicados
            (
                req,
                folio,
                elaboro,
                partida,
                analista_id,
                fecha_elaboracion,
                fecha_inicio_entrega,
                fecha_fin_entrega,
                total,
                dia_pago,
                pago,
                dependencia,
                cotizacion_id,
                anio,
                creado_por
            )
            VALUES
            (
                :req,
                :folio,
                :elaboro,
                :partida,
                :analista_id,
                :fecha_elaboracion,
                :fecha_inicio_entrega,
                :fecha_fin_entrega,
                :total,
                :dia_pago,
                :pago,
                :dependencia,
                :cotizacion_id,
                :anio,
                :creado_por
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([

            ':req' => $datos['req'],

            ':folio' => $datos['folio'],

            ':elaboro' => $datos['elaboro'],

            ':partida' => $datos['partida'],

            ':analista_id' => $datos['analista_id'] ?? null,

            ':fecha_elaboracion' => $datos['fecha_elaboracion'],

            ':fecha_inicio_entrega' => $datos['fecha_inicio_entrega'],

            ':fecha_fin_entrega' => $datos['fecha_fin_entrega'],

            ':total' => $datos['total'],

            ':dia_pago' => $datos['dia_pago'],

            ':pago' => $datos['pago'],

            ':dependencia' => $datos['dependencia'],

            ':cotizacion_id' => $datos['cotizacion_id'],

            ':anio' => $datos['anio'],

            ':creado_por' => $datos['creado_por']

        ]);
    }


    // =========================
    // Buscar catálogo genérico
    // =========================
    public function buscarCatalogo($campo, $texto)
    {
        $camposPermitidos = [
            'dependencia',
            'elaboro',
            'partida'
        ];

        if (!in_array($campo, $camposPermitidos, true)) {
            return [];
        }

        $sql = "
            SELECT DISTINCT {$campo}
            FROM cotizaciones
            WHERE eliminado = 0
            AND {$campo} <> ''
            AND {$campo} IS NOT NULL
            AND {$campo} LIKE :texto
            ORDER BY {$campo}
            LIMIT 10
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':texto' => '%' . $texto . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}