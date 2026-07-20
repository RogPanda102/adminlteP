<?php

require_once __DIR__ . '/../config/database.php';

class Notificacion
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = Database::connect();
    }

    // =========================
    // Crear notificación
    // =========================
    public function crear($datos)
    {

        $sql = "INSERT INTO notificaciones
                (
                    usuario_id,
                    titulo,
                    mensaje,
                    url,
                    tipo
                )
                VALUES
                (
                    :usuario_id,
                    :titulo,
                    :mensaje,
                    :url,
                    :tipo
                )";

        $query = $this->conexion->prepare($sql);

        return $query->execute([

            ':usuario_id' => $datos['usuario_id'],

            ':titulo' => $datos['titulo'],

            ':mensaje' => $datos['mensaje'],

            ':url' => $datos['url'],

            ':tipo' => $datos['tipo']

        ]);

    }

    // =========================
    // Obtener notificaciones
    // =========================
    public function obtenerPorUsuario($usuarioId, $limite = 10)
    {

        $sql = "SELECT *
                FROM notificaciones
                WHERE usuario_id = :usuario_id
                ORDER BY fecha_creacion DESC
                LIMIT $limite";

        $query = $this->conexion->prepare($sql);

        $query->bindParam(
            ':usuario_id',
            $usuarioId,
            PDO::PARAM_INT
        );

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);

    }

    // =========================
    // Contar no leídas
    // =========================
    public function contarNoLeidas($usuarioId)
    {

        $sql = "SELECT COUNT(*) AS total
                FROM notificaciones
                WHERE usuario_id = :usuario_id
                AND leida = 0";

        $query = $this->conexion->prepare($sql);

        $query->bindParam(
            ':usuario_id',
            $usuarioId,
            PDO::PARAM_INT
        );

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);

    }

    // =========================
    // Marcar una como leída
    // =========================
    public function marcarLeida($id)
    {

        $sql = "UPDATE notificaciones
                SET leida = 1
                WHERE id = :id";

        $query = $this->conexion->prepare($sql);

        return $query->execute([

            ':id' => $id

        ]);

    }

    // =========================
    // Marcar todas como leídas
    // =========================
    public function marcarTodasLeidas($usuarioId)
    {

        $sql = "UPDATE notificaciones
                SET leida = 1
                WHERE usuario_id = :usuario_id
                AND leida = 0";

        $query = $this->conexion->prepare($sql);

        return $query->execute([

            ':usuario_id' => $usuarioId

        ]);

    }

}