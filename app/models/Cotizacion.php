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
    // Estadísticas por usuario
    // =========================
    public function obtenerEstadisticasUsuario($usuarioId, $fechaInicio = null, $fechaFin = null)
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
                ) AS total_pendientes,

                SUM(
                    CASE
                        WHEN estatus = 'no se cotiza'
                        THEN 1
                        ELSE 0
                    END
                ) AS total_rechazadas

            FROM cotizaciones

            WHERE creado_por = :usuario_id
            AND eliminado = 0
        ";

        $params = [
            ':usuario_id' => $usuarioId
        ];

        if ($fechaInicio && $fechaFin) {

            $sql .= "
                AND fecha BETWEEN :fecha_inicio
                AND :fecha_fin
            ";

            $params[':fecha_inicio'] = $fechaInicio;
            $params[':fecha_fin'] = $fechaFin;

        }

        $stmt = $this->db->prepare($sql);

        $stmt->execute($params);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return [

            'total_cotizaciones' =>
                $resultado['total_cotizaciones'] ?? 0,

            'total_enviadas' =>
                $resultado['total_enviadas'] ?? 0,

            'total_pendientes' =>
                $resultado['total_pendientes'] ?? 0,

            'total_rechazadas' =>
                $resultado['total_rechazadas'] ?? 0

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
                folio,
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
                :folio,
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
            ':folio'     => $datos['folio'],
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

    // =========================
    // Buscar cotización AJAX
    // =========================
    public function buscarCotizacion($termino, $anio)
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
            WHERE eliminado = 0
            AND anio = :anio
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


    // =========================
    // Cotizaciones por periodo (mes)
    // =========================
    public function obtenerCotizacionesPorMes($usuarioId, $anio, $mes = null)
    {
        $sql = "
            SELECT
                MONTH(fecha) AS mes,
                COUNT(*) AS total
            FROM cotizaciones
            WHERE creado_por = :usuario_id
            AND anio = :anio
            AND eliminado = 0
        ";

        $params = [
            ':usuario_id' => $usuarioId,
            ':anio' => $anio
        ];

        // FILTRO MES (opcional)
        if ($mes) {
            $sql .= " AND MONTH(fecha) = :mes ";
            $params[':mes'] = $mes;
        }

        $sql .= "
            GROUP BY MONTH(fecha)
            ORDER BY MONTH(fecha)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // normalizar 12 meses
        $meses = array_fill(1, 12, 0);

        foreach ($resultado as $fila) {
            $meses[(int)$fila['mes']] = (int)$fila['total'];
        }

        return $meses;
    }
    
    public function obtenerMetricasCotizaciones($usuarioId, $anio, $mes = null)
    {
        $sql = "
            SELECT
                COUNT(*) AS total,
                MAX(mensual.total_mes) AS max_mes,
                AVG(mensual.total_mes) AS promedio
            FROM cotizaciones c
            LEFT JOIN (
                SELECT
                    MONTH(fecha) AS mes,
                    COUNT(*) AS total_mes
                FROM cotizaciones
                WHERE creado_por = :usuario_id
                AND anio = :anio
                AND eliminado = 0
        ";

        $params = [
            ':usuario_id' => $usuarioId,
            ':anio' => $anio
        ];

        if ($mes) {
            $sql .= " AND MONTH(fecha) = :mes ";
            $params[':mes'] = $mes;
        }

        $sql .= "
                GROUP BY MONTH(fecha)
            ) mensual ON 1=1

            WHERE c.creado_por = :usuario_id
            AND c.anio = :anio
            AND c.eliminado = 0
        ";

        if ($mes) {
            $sql .= " AND MONTH(c.fecha) = :mes ";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }



    // =========================
    // Buscar catálogo genérico
    // =========================
    public function buscarCatalogo($campo, $texto)
    {
        $camposPermitidos = [
            'analista',
            'dependencia',
            'proveedor',
            'partida',
            'elaboro'
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