<?php

require_once __DIR__ . '/../config/database.php';

class EventoNotificacion
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    // =========================================
    // CREAR EVENTO
    // =========================================
    public function crear($datos)
    {
        $sql = "
            INSERT INTO eventos_notificacion
            (
                servicio_id,
                recordatorio_id,
                usuario_id,
                fecha_hora_programada,
                enviado
            )
            VALUES
            (
                :servicio_id,
                :recordatorio_id,
                :usuario_id,
                :fecha_hora_programada,
                :enviado
            )
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':servicio_id' => $datos['servicio_id'],
            ':recordatorio_id' => $datos['recordatorio_id'],
            ':usuario_id' => $datos['usuario_id'],
            ':fecha_hora_programada' => $datos['fecha_hora_programada'],
            ':enviado' => $datos['enviado'] ?? 0
        ]);
    }

    // =========================================
    // OBTENER EVENTOS PENDIENTES
    // =========================================
    public function obtenerPendientes()
    {
        $sql = "
            SELECT
                e.id,
                e.servicio_id,
                e.recordatorio_id,
                e.usuario_id,
                e.fecha_hora_programada,
                e.enviado,
                r.dias_antes,
                s.req,
                s.folio,
                s.finalizacion,
                s.anio
            FROM eventos_notificacion e

            INNER JOIN recordatorios_servicio r
                ON r.id = e.recordatorio_id

            INNER JOIN servicios s
                ON s.id = e.servicio_id

            WHERE e.enviado = 0
            AND e.fecha_hora_programada <= NOW()
            AND r.activo = 1

            ORDER BY e.fecha_hora_programada ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================
    // OBTENER EVENTOS DE UN SERVICIO
    // =========================================
    public function obtenerPorServicio($servicioId)
    {
        $sql = "
            SELECT
                id,
                servicio_id,
                recordatorio_id,
                usuario_id,
                fecha_hora_programada,
                enviado,
                fecha_envio
            FROM eventos_notificacion
            WHERE servicio_id = :servicio_id
            ORDER BY fecha_hora_programada ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':servicio_id' => $servicioId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================
    // MARCAR EVENTO COMO ENVIADO
    // =========================================
    public function marcarEnviado($id)
    {
        $sql = "
            UPDATE eventos_notificacion
            SET
                enviado = 1,
                fecha_envio = NOW()
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    // =========================================
    // ELIMINAR EVENTOS PENDIENTES
    // DE UN SERVICIO
    // =========================================
    public function eliminarPendientesPorServicio($servicioId)
    {
        $sql = "
            DELETE FROM eventos_notificacion
            WHERE servicio_id = :servicio_id
            AND enviado = 0
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':servicio_id' => $servicioId
        ]);
    }

    // =========================================
    // VERIFICAR SI EXISTE UN EVENTO
    // =========================================
    public function existe($recordatorioId, $fechaHora)
    {
        $sql = "
            SELECT id
            FROM eventos_notificacion
            WHERE recordatorio_id = :recordatorio_id
            AND fecha_hora_programada = :fecha_hora
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':recordatorio_id' => $recordatorioId,
            ':fecha_hora' => $fechaHora
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) !== false;
    }
}