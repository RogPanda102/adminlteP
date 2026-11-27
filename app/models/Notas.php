<?php

require_once __DIR__ . '/../config/database.php';

class Nota
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // =========================
    // Obtener notas por usuario
    // =========================
    public function obtenerPorUsuario($usuarioId)
    {
        $sql = "
            SELECT *
            FROM notas
            WHERE usuario_id = :usuario_id
            AND estado = 1
            ORDER BY fijada DESC, actualizado_en DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':usuario_id' => $usuarioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Buscar nota por ID y usuario
    // =========================
    public function buscarPorId($id, $usuarioId)
    {
        $sql = "
            SELECT *
            FROM notas
            WHERE id = :id
            AND usuario_id = :usuario_id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $usuarioId
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Crear nota
    // =========================
    public function guardar($datos)
    {
        $sql = "
            INSERT INTO notas (
                usuario_id,
                titulo,
                contenido,
                color,
                fijada,
                estado
            )
            VALUES (
                :usuario_id,
                :titulo,
                :contenido,
                :color,
                :fijada,
                :estado
            )
        ";

        $stmt = $this->db->prepare($sql);

        $ok = $stmt->execute([
            ':usuario_id' => $datos['usuario_id'],
            ':titulo' => $datos['titulo'],
            ':contenido' => $datos['contenido'],
            ':color' => $datos['color'],
            ':fijada' => $datos['fijada'] ?? 0,
            ':estado' => $datos['estado'] ?? 1
        ]);

        if (!$ok) {
            return false;
        }

        return (int) $this->db->lastInsertId();
    }

    // =========================
    // Actualizar nota
    // =========================
    public function actualizar($datos)
    {
        $sql = "
            UPDATE notas
            SET
                titulo = :titulo,
                contenido = :contenido,
                color = :color,
                fijada = :fijada
            WHERE id = :id
            AND usuario_id = :usuario_id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':titulo' => $datos['titulo'],
            ':contenido' => $datos['contenido'],
            ':color' => $datos['color'],
            ':fijada' => $datos['fijada'],
            ':id' => $datos['id'],
            ':usuario_id' => $datos['usuario_id']
        ]);
    }

    // =========================
    // Cambiar estado de nota
    // =========================
    public function cambiarEstado($id, $usuarioId, $estado)
    {
        $sql = "
            UPDATE notas
            SET estado = :estado
            WHERE id = :id
            AND usuario_id = :usuario_id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':estado' => $estado,
            ':id' => $id,
            ':usuario_id' => $usuarioId
        ]);
    }

    // =========================
    // Fijar / desfijar nota
    // =========================
    public function cambiarFijada($id, $usuarioId, $fijada)
    {
        $sql = "
            UPDATE notas
            SET fijada = :fijada
            WHERE id = :id
            AND usuario_id = :usuario_id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':fijada' => $fijada,
            ':id' => $id,
            ':usuario_id' => $usuarioId
        ]);
    }

    // =========================
    // Eliminar nota físicamente
    // =========================
    public function eliminar($id, $usuarioId)
    {
        $sql = "
            DELETE FROM notas
            WHERE id = :id
            AND usuario_id = :usuario_id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $usuarioId
        ]);
    }
}