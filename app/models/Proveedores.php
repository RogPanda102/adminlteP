<?php

require_once __DIR__ . '/../config/database.php';

class Proveedor
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
            FROM proveedores
            ORDER BY proveedor ASC
        ";

        return $this->db
            ->query($sql)
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Guardar proveedor
    // =========================
    public function guardar($datos)
    {
        $sql = "
            INSERT INTO proveedores
            (
                proveedor,
                servicios,
                ubicacion,
                contacto,
                telefono,
                email,
                enlace
            )
            VALUES
            (
                :proveedor,
                :servicios,
                :ubicacion,
                :contacto,
                :telefono,
                :email,
                :enlace
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([

            ':proveedor' => $datos['proveedor'],
            ':servicios' => $datos['servicios'],
            ':ubicacion' => $datos['ubicacion'],
            ':contacto'  => $datos['contacto'],
            ':telefono'  => $datos['telefono'],
            ':email'     => $datos['email'],
            ':enlace'    => $datos['enlace']

        ]);
    }

    // // =========================
    // // Obtener proveedor
    // // =========================
    // public function obtenerPorId($id)
    // {
    //     $sql = "
    //         SELECT *
    //         FROM proveedores
    //         WHERE id = ?
    //         LIMIT 1
    //     ";

    //     $stmt = $this->db->prepare($sql);

    //     $stmt->execute([$id]);

    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }

    // // =========================
    // // Actualizar proveedor
    // // =========================
    // public function actualizar($id, $datos)
    // {
    //     $sql = "
    //         UPDATE proveedores
    //         SET
    //             proveedor = :proveedor,
    //             servicios = :servicios,
    //             ubicacion = :ubicacion,
    //             contacto = :contacto,
    //             telefono = :telefono,
    //             email = :email,
    //             enlace = :enlace
    //         WHERE id = :id
    //     ";

    //     $stmt = $this->db->prepare($sql);

    //     return $stmt->execute([

    //         ':id'        => $id,
    //         ':proveedor' => $datos['proveedor'],
    //         ':servicios' => $datos['servicios'],
    //         ':ubicacion' => $datos['ubicacion'],
    //         ':contacto'  => $datos['contacto'],
    //         ':telefono'  => $datos['telefono'],
    //         ':email'     => $datos['email'],
    //         ':enlace'    => $datos['enlace']

    //     ]);
    // }

    // // =========================
    // // Eliminar proveedor
    // // =========================
    // public function eliminar($id)
    // {
    //     $sql = "
    //         DELETE FROM proveedores
    //         WHERE id = ?
    //     ";

    //     $stmt = $this->db->prepare($sql);

    //     return $stmt->execute([$id]);
    // }
}