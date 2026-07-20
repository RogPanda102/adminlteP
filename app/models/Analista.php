<?php

require_once __DIR__ . '/../config/database.php';

class Analista
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
            FROM analistas
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
            INSERT INTO analistas
            (
                nombre,
                telefono,
                correo
            )
            VALUES
            (
                :nombre,
                :telefono,
                :correo
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombre'   => $datos['nombre'],
            ':telefono' => $datos['telefono'],
            ':correo'   => $datos['correo']
        ]);
    }

    // =========================
    // Guardar (Nuevo)
    // =========================
    public function guardarNuevo($datos)
    {
        $sql = "
            INSERT INTO analistas
            (
                nombre,
                apellido_paterno,
                apellido_materno,
                telefono
            )
            VALUES
            (
                :nombre,
                :apellido_paterno,
                :apellido_materno,
                :telefono
            )
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':nombre'             => $datos['nombre'],
            ':apellido_paterno'   => $datos['apellido_paterno'],
            ':apellido_materno'   => $datos['apellido_materno'],
            ':telefono'           => $datos['telefono']
        ]);

        return $this->db->lastInsertId();
    }



} 