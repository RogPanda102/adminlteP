<?php

require_once __DIR__ . '/../config/database.php';

class RecordatorioServicio
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // =========================================
    // CREAR RECORDATORIO
    // =========================================
    public function crear($datos)
    {
        $sql = "
            INSERT INTO recordatorios_servicio
            (
                servicio_id,
                dias_antes,
                activo
            )
            VALUES
            (
                :servicio_id,
                :dias_antes,
                :activo
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':servicio_id' => $datos['servicio_id'],
            ':dias_antes' => $datos['dias_antes'],
            ':activo' => $datos['activo'] ?? 1
        ]);
    }

    // =========================================
    // OBTENER TODOS LOS RECORDATORIOS
    // DE UN SERVICIO
    // =========================================
    public function obtenerPorServicio($servicioId)
    {
        $sql = "
            SELECT
                id,
                servicio_id,
                dias_antes,
                activo,
                fecha_creacion,
                fecha_actualizacion
            FROM recordatorios_servicio
            WHERE servicio_id = :servicio_id
            ORDER BY dias_antes DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':servicio_id' => $servicioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================
    // OBTENER SOLO LOS ACTIVOS
    // =========================================
    public function obtenerActivosPorServicio($servicioId)
    {
        $sql = "
            SELECT
                id,
                servicio_id,
                dias_antes,
                activo
            FROM recordatorios_servicio
            WHERE servicio_id = :servicio_id
            AND activo = 1
            ORDER BY dias_antes DESC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':servicio_id' => $servicioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================
    // CAMBIAR ESTADO
    // =========================================
    public function cambiarEstado($id, $activo)
    {
        $sql = "
            UPDATE recordatorios_servicio
            SET activo = :activo
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id,
            ':activo' => $activo ? 1 : 0
        ]);
    }

    // =========================================
    // ELIMINAR RECORDATORIO
    // =========================================
    public function eliminar($id)
    {
        $sql = "
            DELETE FROM recordatorios_servicio
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    // =========================================
    // ELIMINAR TODOS LOS RECORDATORIOS
    // DE UN SERVICIO
    // =========================================
    public function eliminarPorServicio($servicioId)
    {
        $sql = "
            DELETE FROM recordatorios_servicio
            WHERE servicio_id = :servicio_id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':servicio_id' => $servicioId
        ]);
    }
}