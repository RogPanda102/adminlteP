<?php

require_once __DIR__ . '/../config/database.php';

class Encargado
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // =========================
    // Obtener todos
    // =========================
    public function obtenerTodos()
    {
        $sql = "
            SELECT *
            FROM encargados
            ORDER BY nombre ASC
        ";

        return $this->db
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Guardar
    // =========================
    public function guardar($datos)
    {
        $sql = "
            INSERT INTO encargados
            (
                nombre,
                telefono,
                dependencia
            )
            VALUES
            (
                :nombre,
                :telefono,
                :dependencia
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombre'      => $datos['nombre'],
            ':telefono'    => $datos['telefono'],
            ':dependencia' => $datos['dependencia']
        ]);
    }
}