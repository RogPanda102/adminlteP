<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Servicios.php';
require_once __DIR__ . '/RecordatorioServicio.php';

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
    // CALCULAR FECHA DEL RECORDATORIO
    // =========================================
    private function calcularFechaEvento($fechaFinalizacion, $recordatorio)
    {
        $zonaHoraria = new DateTimeZone('America/Mexico_City');

        // Usar la misma zona horaria que utiliza $hoy
        $fecha = new DateTimeImmutable(
            $fechaFinalizacion,
            $zonaHoraria
        );

        $cantidad = (int)$recordatorio['cantidad'];
        $unidad = $recordatorio['unidad'];
        $momento = $recordatorio['momento'];

        // Determinar si el recordatorio es previo o posterior
        $signo = ($momento === 'antes') ? -1 : 1;

        // Recordatorios expresados en días
        if ($unidad === 'dias') {
            $dias = $signo * $cantidad;

            return $fecha->modify(
                ($dias >= 0 ? '+' : '') . $dias . ' days'
            );
        }

        // Recordatorios expresados en meses calendario
        if ($unidad === 'meses') {
            $meses = $signo * $cantidad;

            $anio = (int)$fecha->format('Y');
            $mes = (int)$fecha->format('n');
            $dia = (int)$fecha->format('j');

            // Calcular el año y mes destino
            $totalMeses = ($anio * 12) + ($mes - 1) + $meses;

            $anioDestino = intdiv($totalMeses, 12);
            $mesDestino = ($totalMeses % 12) + 1;

            // Evitar desbordamientos en meses más cortos
            $ultimoDia = cal_days_in_month(
                CAL_GREGORIAN,
                $mesDestino,
                $anioDestino
            );

            $diaDestino = min($dia, $ultimoDia);

            return new DateTimeImmutable(
                sprintf(
                    '%04d-%02d-%02d',
                    $anioDestino,
                    $mesDestino,
                    $diaDestino
                ),
                $zonaHoraria
            );
        }

        throw new InvalidArgumentException(
            'Unidad de recordatorio no válida: ' . $unidad
        );
    }


    // =========================
    // Generar eventos para un servicio
    // =========================
    public function generarParaServicio($servicioId)
    {
        $servicioModelo = new Servicio();
        $recordatorioModelo = new RecordatorioServicio();

        $servicio = $servicioModelo->buscarPorId($servicioId);

        if (!$servicio) {
            return false;
        }

        if (empty($servicio['finalizacion']) || empty($servicio['creado_por'])) {
            return false;
        }

        $recordatorios =
            $recordatorioModelo->obtenerActivosPorServicio($servicioId);

        if (empty($recordatorios)) {
            return true;
        }

        $zonaHoraria = new DateTimeZone('America/Mexico_City');
        $hoy = new DateTimeImmutable('today', $zonaHoraria);

        error_log('DEBUG recordatorios: ' . json_encode([
            'servicio_id' => $servicioId,
            'finalizacion' => $servicio['finalizacion'],
            'hoy_php' => $hoy->format('Y-m-d H:i:s'),
            'ahora_php' => (new DateTimeImmutable())->format('Y-m-d H:i:s'),
            'zona_horaria' => date_default_timezone_get()
        ]));

        $horarios = [
            '09:00:00',
            '13:00:00',
            '17:00:00'
        ];

        foreach ($recordatorios as $recordatorio) {

            // Validar la configuración del recordatorio
            if (
                !isset(
                    $recordatorio['cantidad'],
                    $recordatorio['unidad'],
                    $recordatorio['momento']
                ) ||
                !in_array($recordatorio['unidad'], ['dias', 'meses'], true) ||
                !in_array($recordatorio['momento'], ['antes', 'despues'], true) ||
                (int)$recordatorio['cantidad'] < 0
            ) {
                continue;
            }

            try {
                $fechaEvento = $this->calcularFechaEvento(
                    $servicio['finalizacion'],
                    $recordatorio
                );
            } catch (Throwable $e) {
                error_log(
                    'Error calculando recordatorio ' .
                        $recordatorio['id'] . ': ' . $e->getMessage()
                );

                continue;
            }

            // No generar eventos de días que ya pasaron
            if ($fechaEvento < $hoy) {
                continue;
            }

            foreach ($horarios as $hora) {

                $fechaHoraProgramada =
                    $fechaEvento->format('Y-m-d') . ' ' . $hora;

                // Evitar duplicar el mismo evento
                if ($this->existe(
                    $recordatorio['id'],
                    $fechaHoraProgramada
                )) {
                    continue;
                }

                $creado = $this->crear([
                    'servicio_id' => $servicioId,
                    'recordatorio_id' => $recordatorio['id'],
                    'usuario_id' => $servicio['creado_por'],
                    'fecha_hora_programada' => $fechaHoraProgramada
                ]);

                if (!$creado) {
                    error_log(
                        'No se pudo crear el evento del recordatorio ' .
                            $recordatorio['id'] .
                            ' para ' . $fechaHoraProgramada
                    );
                }
            }
        }

        return true;
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
                r.cantidad,
                r.unidad,
                r.momento,
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

    
    public function eliminarEventosVencidos()
    {
        $sql = "DELETE FROM eventos_notificacion
                WHERE enviado = 0
                AND fecha_hora_programada < CURDATE()
                ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute();
    }

}
