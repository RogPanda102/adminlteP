<?php

require_once __DIR__ . '/../config/database.php';

class Dependencia
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
            FROM dependencias
            ORDER BY id DESC
        ";

        return $this->db
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Guardar dependencia
    // =========================
    public function guardar($datos)
    {
        $sql = "
            INSERT INTO dependencias
            (
                nombre,
                descripcion,
                ubicacion
            )
            VALUES
            (
                :nombre,
                :descripcion,
                :ubicacion
            )
        ";

        $stmt = $this->db->prepare($sql);

        $resultado = $stmt->execute([

            ':nombre'      => $datos['nombre'],
            ':descripcion' => $datos['descripcion'],
            ':ubicacion'   => $datos['ubicacion']

        ]);

        if (!$resultado) {
            return false;
        }

        return $this->db->lastInsertId();
    }
}