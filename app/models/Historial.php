<?php

require_once __DIR__ . '/../config/database.php';

class Historial
{
    private $db;
    public function __construct()
    {
        $this->db = Database::connect();
    }
    // =========================
    // Registrar movimiento
    // =========================
    public function registrar($datos)
    {
        $sql = "
            INSERT INTO historial
            (
                modulo,
                registro_id,
                accion,
                descripcion,
                datos_anteriores,
                datos_nuevos,
                usuario_id
            )
            VALUES
            (
                :modulo,
                :registro_id,
                :accion,
                :descripcion,
                :datos_anteriores,
                :datos_nuevos,
                :usuario_id
            )
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':modulo' =>
                $datos['modulo'],
            ':registro_id' =>
                $datos['registro_id'],
            ':accion' =>
                $datos['accion'],
            ':descripcion' =>
                $datos['descripcion'] ?? null,
            ':datos_anteriores' =>
                isset($datos['datos_anteriores'])
                ? json_encode(
                    $datos['datos_anteriores'],
                    JSON_UNESCAPED_UNICODE
                )
                : null,
            ':datos_nuevos' =>
                isset($datos['datos_nuevos'])
                ? json_encode(
                    $datos['datos_nuevos'],
                    JSON_UNESCAPED_UNICODE
                )
                : null,
            ':usuario_id' =>
                $datos['usuario_id'] ?? null
        ]);
    }
    // =========================
    // Obtener historial
    // =========================
    public function obtener($modulo, $registro_id)
    {

        $sql = "
            SELECT
                h.*,
                u.nombre AS usuario
            FROM historial h

            LEFT JOIN usuarios u
            ON u.id = h.usuario_id

            WHERE
                h.modulo = ?

            AND
                h.registro_id = ?

            ORDER BY
                h.fecha DESC
        ";


        $stmt = $this->db->prepare($sql);


        $stmt->execute([

            $modulo,
            $registro_id

        ]);


        return $stmt->fetchAll(
            PDO::FETCH_ASSOC
        );

    }

}