
<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/procesar_eventos_notificacion.php';

class CoordinadorNotificaciones
{
    private PDO $db;

    private const NOMBRE_BLOQUEO = 'mini_erp_recordatorios';
    private const INTERVALO_SEGUNDOS = 60;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function ejecutar(): array
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return [
                'ejecutado' => false,
                'motivo' => 'sin_sesion'
            ];
        }

        // Evitar repetir la revisión en la misma sesión.
        $ultimaEjecucion = (int) (
            $_SESSION['ultima_revision_recordatorios'] ?? 0
        );

        if (time() - $ultimaEjecucion < self::INTERVALO_SEGUNDOS) {
            return [
                'ejecutado' => false,
                'motivo' => 'intervalo'
            ];
        }

        // Intentar adquirir el bloqueo global de MySQL.
        $consulta = $this->db->prepare(
            'SELECT GET_LOCK(:nombre, 0)'
        );

        $consulta->execute([
            'nombre' => self::NOMBRE_BLOQUEO
        ]);

        $bloqueo = $consulta->fetchColumn();

        if ((int) $bloqueo !== 1) {
            return [
                'ejecutado' => false,
                'motivo' => 'bloqueado'
            ];
        }

        try {
            $_SESSION['ultima_revision_recordatorios'] = time();

            $procesador = new ProcesadorNotificaciones();
            $resultado = $procesador->procesar();

            return [
                'ejecutado' => true,
                'resultado' => $resultado
            ];

        } finally {
            $liberar = $this->db->prepare(
                'SELECT RELEASE_LOCK(:nombre)'
            );

            $liberar->execute([
                'nombre' => self::NOMBRE_BLOQUEO
            ]);
        }
    }
}
