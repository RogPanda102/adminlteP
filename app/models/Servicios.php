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
            SELECT *
            FROM servicios
            WHERE anio = ?
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
                analista,
                tiempo_contratacion,
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
                :analista,
                :tiempo_contratacion,
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
            ':analista'            => $datos['analista'],
            ':tiempo_contratacion' => $datos['tiempo_contratacion'],
            ':fecha_contratacion'  => $datos['fecha_contratacion'],
            ':inicio'              => $datos['inicio'],
            ':finalizacion'        => $datos['finalizacion'],
            ':dependencia'         => $datos['dependencia'],
            ':adjudicado_id' => $datos['adjudicado_id'] ?? null,
            // ':adjudicado_id' => $datos['adjudicado_id'], se comenta y se pone opcional null pues ahora puede marcar error el controlador al apuntar ningun dato
            ':anio'                => $datos['anio'],
            ':creado_por'          => $datos['creado_por'],
            ':actualizado_por'     => $datos['actualizado_por']
        ]);
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

}
