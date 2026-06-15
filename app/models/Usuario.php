<?php

require_once __DIR__ . '/../config/database.php';

class Usuario
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = Database::connect();
    }

    // =========================
    // Buscar usuario por username
    // =========================
    public function buscarPorUsuario($usuario)
    {

        $sql = "SELECT *
                FROM usuarios
                WHERE usuario = :usuario
                AND eliminado = 0
                LIMIT 1";

        $query = $this->conexion->prepare($sql);

        $query->bindParam(':usuario', $usuario);

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);

    }

    public function buscarPorCorreo($correo)
    {

        $sql = "SELECT *
                FROM usuarios
                WHERE correo = :correo
                AND eliminado = 0
                LIMIT 1";

        $query = $this->conexion->prepare($sql);

        $query->bindParam(':correo', $correo);

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);

    }
    public function crearUsuario($datos)
    {
        $sql = "INSERT INTO usuarios
                (
                    nombre,
                    apellido_paterno,
                    apellido_materno,
                    usuario,
                    correo,
                    password,
                    rol_id,
                    estado
                )
                VALUES
                (
                    :nombre,
                    :apellido_paterno,
                    :apellido_materno,
                    :usuario,
                    :correo,
                    :password,
                    2,
                    'activo'
                )";

        $query = $this->conexion->prepare($sql);

        return $query->execute([
            ':nombre'             => $datos['nombre'],
            ':apellido_paterno'   => $datos['apellido_paterno'],
            ':apellido_materno'   => $datos['apellido_materno'],
            ':usuario'            => $datos['usuario'],
            ':correo'             => $datos['correo'],
            ':password'           => $datos['password']
        ]);
    }
    

}