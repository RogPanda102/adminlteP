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

}