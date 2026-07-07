<?php
    require_once __DIR__ . '/../config/database.php';
    class Dashboard 
    {
        private $db;
        public function __construct() {
            $this->db = Database::connect();
        }



        // =========================
        // Contar registros
        // =========================
        private function contarRegistros($tabla, $anio)
        {
            $tablasPermitidas = [
                'cotizaciones',
                'adjudicados',
                'servicios',
                'proveedores'
            ];

            if (!in_array($tabla, $tablasPermitidas, true)) {
                return 0;
            }

            if ($tabla === 'proveedores') {

                $sql = "
                    SELECT COUNT(*) AS total
                    FROM proveedores
                ";

                $stmt = $this->db->prepare($sql);

                $stmt->execute();

            } else {

                $sql = "
                    SELECT COUNT(*) AS total
                    FROM {$tabla}
                    WHERE anio = :anio
                ";

                $stmt = $this->db->prepare($sql);

                $stmt->execute([
                    ':anio' => $anio
                ]);
            }

            return (int) $stmt->fetchColumn();
        }


        public function obtenerResumen($anio)
        {
            return [

                'cotizaciones' => $this->contarRegistros(
                    'cotizaciones',
                    $anio
                ),

                'adjudicados' => $this->contarRegistros(
                    'adjudicados',
                    $anio
                ),

                'servicios' => $this->contarRegistros(
                    'servicios',
                    $anio
                ),

                'proveedores' => $this->contarRegistros(
                    'proveedores',
                    $anio
                )

            ];
        }

        // =========================
        // Top genérico
        // =========================
        private function obtenerTop($tabla, $campo, $anio, $limite = 10)
        {
            $tablasPermitidas = [
                'cotizaciones',
                'adjudicados',
                'servicios'
            ];

            $camposPermitidos = [
                'analista',
                'dependencia',
                'elaboro',
                'partida'
            ];

            if (
                !in_array($tabla, $tablasPermitidas, true) ||
                !in_array($campo, $camposPermitidos, true)
            ) {
                return [];
            }

            $sql = "
                SELECT
                    {$campo},
                    COUNT(*) AS total
                FROM {$tabla}
                WHERE anio = :anio
                AND {$campo} IS NOT NULL
                AND {$campo} <> ''
                GROUP BY {$campo}
                ORDER BY total DESC, {$campo} ASC
                LIMIT {$limite}
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':anio' => $anio
            ]);

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // =========================
        // Obtener ranking
        // =========================
        public function obtenerRanking($tabla, $campo, $anio, $limite = 10)
        {
            return $this->obtenerTop(
                $tabla,
                $campo,
                $anio,
                $limite
            );
        }

        // =========================
        // Contar por campo
        // =========================
        private function contarPorCampo($tabla, $campo, $valor, $anio)
        {
            $tablasPermitidas = [
                'cotizaciones',
                'adjudicados',
                'servicios'
            ];

            $camposPermitidos = [
                'estatus',
                'pago',
                'analista',
                'dependencia',
                'elaboro',
                'partida'
            ];

            if (
                !in_array($tabla, $tablasPermitidas, true) ||
                !in_array($campo, $camposPermitidos, true)
            ) {
                return 0;
            }

            $sql = "
                SELECT COUNT(*)
                FROM {$tabla}
                WHERE {$campo} = :valor
                AND anio = :anio
            ";

            $stmt = $this->db->prepare($sql);

            $stmt->execute([
                ':valor' => $valor,
                ':anio'  => $anio
            ]);

            return (int) $stmt->fetchColumn();
        }

    }