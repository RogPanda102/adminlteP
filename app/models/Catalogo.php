<?php

require_once __DIR__ . '/../config/database.php';

class Catalogo
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // =========================
    // Buscar catálogo genérico
    // =========================
    public function buscar($catalogo, $texto)
    {
        $catalogos = [

            // =========================
            // ANALISTAS
            // =========================
            'analista' => [
                'tabla' => 'analistas',
                'id' => 'id',

                // Lo que se mostrará al usuario
                'mostrar' => "
                    CONCAT(
                        nombre,
                        ' ',
                        apellido_paterno,
                        IF(
                            apellido_materno IS NULL
                            OR apellido_materno = '',
                            '',
                            CONCAT(' ', apellido_materno)
                        )
                    )
                ",

                // Lo que se utilizará para buscar
                'buscar' => "
                    CONCAT(
                        nombre,
                        ' ',
                        apellido_paterno,
                        IF(
                            apellido_materno IS NULL
                            OR apellido_materno = '',
                            '',
                            CONCAT(' ', apellido_materno)
                        )
                    )
                "
            ],

            // =========================
            // PROVEEDORES
            // =========================
            'proveedor' => [
                'tabla' => 'proveedores',
                'id' => 'id',
                'mostrar' => 'nombre',
                'buscar' => 'nombre'
            ],

            // =========================
            // DEPENDENCIAS
            // =========================
            'dependencia' => [
                'tabla' => 'cotizaciones',
                'id' => 'dependencia',
                'mostrar' => 'dependencia',
                'buscar' => 'dependencia'
            ],

            // =========================
            // PARTIDAS
            // =========================
            'partida' => [
                'tabla' => 'cotizaciones',
                'id' => 'partida',
                'mostrar' => 'partida',
                'buscar' => 'partida'
            ],

            // =========================
            // ELABORÓ
            // =========================
            'elaboro' => [
                'tabla' => 'cotizaciones',
                'id' => 'elaboro',
                'mostrar' => 'elaboro',
                'buscar' => 'elaboro'
            ]

        ];

        if (!isset($catalogos[$catalogo])) {
            return [];
        }

        $cfg = $catalogos[$catalogo];

        $sql = "
            SELECT DISTINCT
                {$cfg['id']} AS id,
                {$cfg['mostrar']} AS nombre
            FROM {$cfg['tabla']}
            WHERE {$cfg['buscar']} LIKE :texto
            ORDER BY nombre
            LIMIT 10
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':texto' => '%' . $texto . '%'
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}