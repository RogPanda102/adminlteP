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
                    tipo,
                    modulo,
                    registro_id,
                    evento
                )
                VALUES
                (
                    :usuario_id,
                    :titulo,
                    :mensaje,
                    :url,
                    :tipo,
                    :modulo,
                    :registro_id,
                    :evento
                )";

        $query = $this->conexion->prepare($sql);

        return $query->execute([

            ':usuario_id' => $datos['usuario_id'],

            ':titulo' => $datos['titulo'],

            ':mensaje' => $datos['mensaje'],

            ':url' => $datos['url'],

            ':tipo' => $datos['tipo'],

            ':modulo' => $datos['modulo'] ?? null,
            ':registro_id' => $datos['registro_id'] ?? null,
            ':evento' => $datos['evento'] ?? null

        ]);

    }

    

    // =========================
    // Verificar si ya existe un evento
    // =========================
    public function existeEvento(
        $usuarioId,
        $modulo,
        $registroId,
        $evento
    ) {

        $sql = "SELECT id
                FROM notificaciones
                WHERE usuario_id = :usuario_id
                AND modulo = :modulo
                AND registro_id = :registro_id
                AND evento = :evento
                LIMIT 1";

        $query = $this->conexion->prepare($sql);

        $query->execute([

            ':usuario_id' => $usuarioId,
            ':modulo' => $modulo,
            ':registro_id' => $registroId,
            ':evento' => $evento

        ]);

        return $query->fetch(PDO::FETCH_ASSOC) !== false;
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
    // Obtener para Navbar
    // =========================
    public function obtenerParaNavbar(
        $usuarioId,
        $limite = 5
    )
    {

        $sql = "SELECT *
                FROM notificaciones
                WHERE usuario_id = :usuario_id
                ORDER BY
                    leida ASC,
                    fecha_creacion DESC
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
    // Buscar por ID
    // =========================
    public function buscarPorId($id)
    {

        $sql = "SELECT *
                FROM notificaciones
                WHERE id = :id
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