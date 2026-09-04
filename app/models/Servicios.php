<?php

require_once __DIR__ . '/../config/database.php';

class Servicio
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // =========================
    // Obtener servicios por año
    // =========================
    public function obtenerPorAnio($anio)
    {
        $sql = "
            SELECT

                s.*,

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
                ) AS analista,

                ts.nombre AS tipo_servicio

            FROM servicios s

            LEFT JOIN analistas a
                ON a.id = s.analista_id

            LEFT JOIN tipos_servicio ts
                ON ts.id = s.tipo_servicio_id

            WHERE s.anio = :anio

            ORDER BY s.id DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':anio' => $anio
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Obtener años disponibles
    // =========================
    public function obtenerAnios()
    {
        $sql = "
            SELECT DISTINCT anio
            FROM servicios
            WHERE anio IS NOT NULL
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
            SELECT COUNT(*) AS total_servicios
            FROM servicios
            WHERE anio = :anio
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':anio' => $anio
        ]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total_servicios' => $resultado['total_servicios'] ?? 0
        ];
    }

    // =========================
    // Obtener servicios para detector de vencimientos
    // =========================
    public function obtenerServiciosParaNotificaciones()
    {
        $sql = "
            SELECT
                id,
                req,
                folio,
                inicio,
                finalizacion,
                creado_por,
                anio
            FROM servicios
            WHERE finalizacion IS NOT NULL
            AND creado_por IS NOT NULL
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Guardar servicio
    // =========================
    public function guardar($datos)
    {
        $sql = "
            INSERT INTO servicios
            (
                req,
                folio,
                elaboro,
                partida,
                analista_id,
                tipo_servicio_id,
                tiempo_cantidad,
                tiempo_unidad,
                fecha_contratacion,
                inicio,
                finalizacion,
                dependencia,
                adjudicado_id,
                anio,
                creado_por,
                actualizado_por
            )
            VALUES
            (
                :req,
                :folio,
                :elaboro,
                :partida,
                :analista_id,
                :tipo_servicio_id,
                :tiempo_cantidad,
                :tiempo_unidad,
                :fecha_contratacion,
                :inicio,
                :finalizacion,
                :dependencia,
                :adjudicado_id,
                :anio,
                :creado_por,
                :actualizado_por
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([

            ':req'                 => $datos['req'],
            ':folio'               => $datos['folio'],
            ':elaboro'             => $datos['elaboro'],
            ':partida'             => $datos['partida'],

            ':analista_id'         => $datos['analista_id'] ?? null,
            ':tipo_servicio_id'    => $datos['tipo_servicio_id'] ?? null,
            ':tiempo_cantidad'     => $datos['tiempo_cantidad'] ?? null,
            ':tiempo_unidad'       => $datos['tiempo_unidad'] ?? null,
            ':fecha_contratacion'  => $datos['fecha_contratacion'] ?? null,
            ':inicio'              => $datos['inicio'] ?? null,
            ':finalizacion'        => $datos['finalizacion'] ?? null,
            ':dependencia'         => $datos['dependencia'] ?? null,

            ':adjudicado_id'       => $datos['adjudicado_id'] ?? null,

            ':anio'                => $datos['anio'],
            ':creado_por'          => $datos['creado_por'],
            ':actualizado_por'     => $datos['actualizado_por']

        ]);
    }

    // =========================
    // Obtener servicios para notificación
    // =========================
    public function obtenerServiciosParaNotificar()
    {
        $sql = "
            SELECT
                id,
                req,
                folio,
                finalizacion,
                creado_por,
                anio
            FROM servicios
            WHERE finalizacion IS NOT NULL
            AND creado_por IS NOT NULL
            AND finalizacion IN (
                DATE_ADD(CURDATE(), INTERVAL 90 DAY),
                DATE_ADD(CURDATE(), INTERVAL 30 DAY),
                DATE_ADD(CURDATE(), INTERVAL 7 DAY),
                CURDATE()
            )
            ORDER BY finalizacion ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Buscar servicio AJAX
    // =========================
    public function buscarServicio($termino, $anio)
    {
        $sql = "
            SELECT
                id,
                req,
                folio,
                elaboro,
                partida,
                analista
            FROM servicios
            WHERE anio = :anio
            AND (
                req LIKE :termino
                OR folio LIKE :termino
            )
            ORDER BY id DESC
            LIMIT 10
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':anio'    => $anio,
            ':termino' => "%{$termino}%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPredictivo($texto)
    {

        $sql = "
        SELECT
            id,
            req,
            folio,
            elaboro,
            partida,
            analista
        FROM cotizaciones
        WHERE anio = :anio
        AND (
            req LIKE :texto
            OR folio LIKE :texto
        )
        ORDER BY id DESC
        LIMIT 10
    ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([

            ':anio' => date('Y'),

            ':texto' => '%' . $texto . '%'

        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Buscar dependencias
    // =========================
    public function buscarDependencias($texto)
    {

        $sql = "
            SELECT DISTINCT
                dependencia
            FROM servicios
            WHERE dependencia LIKE :texto
            ORDER BY dependencia ASC
            LIMIT 10
        ";


        $stmt = $this->db->prepare($sql);


        $stmt->execute([

            ':texto' => '%' . $texto . '%'

        ]);


        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );

    }


    // =========================
    // Buscar tipos de servicio
    // =========================
    public function buscarTiposServicio($texto)
    {
        $sql = "
            SELECT
                id,
                nombre
            FROM tipos_servicio
            WHERE nombre LIKE :texto
            ORDER BY nombre ASC
            LIMIT 10
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':texto' => '%' . $texto . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
