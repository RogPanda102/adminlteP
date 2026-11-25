
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

}
