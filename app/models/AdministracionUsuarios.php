
<?php

require_once __DIR__ . '/../config/database.php';

class AdministracionUsuarios
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = Database::connect();
    }

    // =========================
    // Obtener usuarios registrados
    // =========================
    public function obtenerUsuarios()
    {
        $sql = "SELECT
                    u.id,
                    u.usuario AS nick,

                    CONCAT_WS(
                        ' ',
                        u.nombre,
                        u.apellido_paterno,
                        u.apellido_materno
                    ) AS nombre_completo,

                    COALESCE(
                        r.nombre,
                        'Sin rol asignado'
                    ) AS rol,

                    u.fecha_creacion AS fecha_registro

                FROM usuarios u

                LEFT JOIN roles r
                    ON r.id = u.rol_id

                WHERE COALESCE(u.eliminado, 0) = 0

                ORDER BY u.id DESC";

        $query = $this->conexion->prepare($sql);

        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

        // =========================
        // Obtener historial por usuario
        // =========================
        public function obtenerHistorialPorUsuario($usuarioId)
        {
            $sql = "SELECT
                        id,
                        modulo,
                        registro_id,
                        accion,
                        descripcion,
                        datos_anteriores,
                        datos_nuevos,
                        fecha

                    FROM historial

                    WHERE usuario_id = :usuario_id

                    ORDER BY fecha DESC, id DESC";

            $query = $this->conexion->prepare($sql);

            $query->bindValue(
                ':usuario_id',
                (int) $usuarioId,
                PDO::PARAM_INT
            );

            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        // =========================
        // Obtener historial general o por usuario
        // =========================
        public function obtenerHistorial($usuarioId = null)
        {
            $sql = "SELECT
                        h.id,
                        h.usuario_id,
                        h.modulo,
                        h.registro_id,
                        h.accion,
                        h.descripcion,
                        h.datos_anteriores,
                        h.datos_nuevos,
                        h.fecha,

                        u.usuario AS usuario_nick,

                        CONCAT_WS(
                            ' ',
                            u.nombre,
                            u.apellido_paterno,
                            u.apellido_materno
                        ) AS usuario_nombre

                    FROM historial h

                    LEFT JOIN usuarios u
                        ON u.id = h.usuario_id";

            if ($usuarioId !== null) {
                $sql .= " WHERE h.usuario_id = :usuario_id";
            }

            $sql .= " ORDER BY h.fecha DESC, h.id DESC";

            $query = $this->conexion->prepare($sql);

            if ($usuarioId !== null) {
                $query->bindValue(
                    ':usuario_id',
                    (int) $usuarioId,
                    PDO::PARAM_INT
                );
            }

            $query->execute();

            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
}
