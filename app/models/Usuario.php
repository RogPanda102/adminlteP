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

        $sql = "SELECT u.*, r.nombre AS rol
                FROM usuarios u
                INNER JOIN roles r ON r.id = u.rol_id
                WHERE u.usuario = :usuario
                AND u.eliminado = 0
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

    public function estadisticasUsuario($usuario_id)
    {
        $sql = "
        SELECT
            SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) AS pendientes,
            SUM(CASE WHEN estado = 'proceso' THEN 1 ELSE 0 END) AS proceso,
            SUM(CASE WHEN estado = 'completado' THEN 1 ELSE 0 END) AS completados
        FROM adjudicaciones
        WHERE usuario_id = :id
    ";

        $query = $this->conexion->prepare($sql);
        $query->bindParam(':id', $usuario_id);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function actividadReciente($usuario_id, $limit = 10)
    {
        $sql = "
        SELECT *
        FROM actividad_usuario
        WHERE usuario_id = :id
        ORDER BY created_at DESC
        LIMIT $limit
    ";

        $query = $this->conexion->prepare($sql);
        $query->bindParam(':id', $usuario_id);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarPassword(
        $usuarioId,
        $password,
        $actualizadoPor
    )
    {

        $sql = "UPDATE usuarios
                SET
                    password = :password,
                    actualizado_por = :actualizado_por
                WHERE id = :id";

        $query = $this->conexion->prepare($sql);

        return $query->execute([
            ':password' => $password,
            ':actualizado_por' => $actualizadoPor,
            ':id' => $usuarioId
        ]);

    }

    public function buscarPorId($id)
    {

        $sql = "SELECT *
                FROM usuarios
                WHERE id = :id
                AND eliminado = 0
                LIMIT 1";

        $query = $this->conexion->prepare($sql);

        $query->bindParam(
            ':id',
            $id,
            PDO::PARAM_INT
        );

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);

    }

}
