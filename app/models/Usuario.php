<?php

require_once __DIR__ . '/../config/database.php';

class Usuario
{

    private $conexion;

    public function __construct()
    {
        $this->conexion = Database::connect();
    }

    // =========================
    // Buscar usuario por username
    // =========================
    public function buscarPorUsuario($usuario)
    {

        $sql = "SELECT u.*, r.nombre AS rol
                FROM usuarios u
                INNER JOIN roles r ON r.id = u.rol_id
                WHERE u.usuario = :usuario
                AND u.eliminado = 0
                LIMIT 1";

        $query = $this->conexion->prepare($sql);

        $query->bindParam(':usuario', $usuario);

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Buscar usuario por correo
    // =========================
    public function buscarPorCorreo($correo)
    {

        $sql = "SELECT *
                FROM usuarios
                WHERE correo = :correo
                AND eliminado = 0
                LIMIT 1";

        $query = $this->conexion->prepare($sql);

        $query->bindParam(':correo', $correo);

        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Crear usuario
    // =========================
    public function crearUsuario($datos)
    {
        $sql = "INSERT INTO usuarios
                (
                    nombre,
                    apellido_paterno,
                    apellido_materno,
                    usuario,
                    correo,
                    password,
                    rol_id,
                    estado
                )
                VALUES
                (
                    :nombre,
                    :apellido_paterno,
                    :apellido_materno,
                    :usuario,
                    :correo,
                    :password,
                    2,
                    'activo'
                )";

        $query = $this->conexion->prepare($sql);

        return $query->execute([
            ':nombre'             => $datos['nombre'],
            ':apellido_paterno'   => $datos['apellido_paterno'],
            ':apellido_materno'   => $datos['apellido_materno'],
            ':usuario'            => $datos['usuario'],
            ':correo'             => $datos['correo'],
            ':password'           => $datos['password']
        ]);
    }

    // =========================
    // Guardar código de verificación
    // =========================
    public function guardarCodigoVerificacion(
        $usuarioId,
        $codigo,
        $expira
    ) {
        $sql = "
            UPDATE usuarios
            SET
                correo_verificado = 0,
                correo_codigo = :codigo,
                correo_codigo_expira = :expira,
                correo_verificado_at = NULL
            WHERE id = :id
            AND eliminado = 0
        ";

        $query = $this->conexion->prepare($sql);

        return $query->execute([
            ':codigo' => $codigo,
            ':expira' => $expira,
            ':id'     => $usuarioId
        ]);
    }

    // =========================
    // Verificar código
    // =========================
    public function verificarCodigo(
        $usuarioId,
        $codigo
    ) {
        $sql = "
            SELECT id
            FROM usuarios
            WHERE id = :id
            AND correo_codigo = :codigo
            AND correo_codigo_expira >= NOW()
            AND eliminado = 0
            LIMIT 1
        ";

        $query = $this->conexion->prepare($sql);

        $query->execute([
            ':id'     => $usuarioId,
            ':codigo' => $codigo
        ]);

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Marcar correo como verificado
    // =========================
    public function marcarCorreoVerificado($usuarioId)
    {
        $sql = "
            UPDATE usuarios
            SET
                correo_verificado = 1,
                correo_codigo = NULL,
                correo_codigo_expira = NULL,
                correo_verificado_at = NOW()
            WHERE id = :id
            AND eliminado = 0
        ";

        $query = $this->conexion->prepare($sql);

        return $query->execute([
            ':id' => $usuarioId
        ]);
    }

    // =========================
    // Obtener código vigente
    // =========================
    public function obtenerCodigoVerificacion($usuarioId)
    {
        $sql = "
            SELECT
                correo_codigo,
                correo_codigo_expira
            FROM usuarios
            WHERE id = :id
            AND eliminado = 0
            LIMIT 1
        ";

        $query = $this->conexion->prepare($sql);

        $query->execute([
            ':id' => $usuarioId
        ]);

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Estadísticas usuario
    // =========================
    public function estadisticasUsuario($usuario_id)
    {
        $sql = "
        SELECT
            SUM(CASE WHEN estado = 'pendiente' THEN 1 ELSE 0 END) AS pendientes,
            SUM(CASE WHEN estado = 'proceso' THEN 1 ELSE 0 END) AS proceso,
            SUM(CASE WHEN estado = 'completado' THEN 1 ELSE 0 END) AS completados
        FROM adjudicaciones
        WHERE usuario_id = :id
        ";

        $query = $this->conexion->prepare($sql);
        $query->bindParam(':id', $usuario_id);
        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // =========================
    // Actividad reciente
    // =========================
    public function actividadReciente($usuario_id, $limit = 10)
    {
        $sql = "
        SELECT *
        FROM actividad_usuario
        WHERE usuario_id = :id
        ORDER BY created_at DESC
        LIMIT $limit
        ";

        $query = $this->conexion->prepare($sql);
        $query->bindParam(':id', $usuario_id);
        $query->execute();

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================
    // Actualizar password
    // =========================
    public function actualizarPassword(
        $usuarioId,
        $password
    )
    {

        $sql = "UPDATE usuarios
                SET
                    password = :password
                WHERE id = :id";

        $query = $this->conexion->prepare($sql);

        return $query->execute([
            ':password' => $password,
            ':id' => $usuarioId
        ]);

    }

    // =========================
    // Buscar usuario por ID
    // =========================
    public function buscarPorId($id)
    {

        $sql = "SELECT *
                FROM usuarios
                WHERE id = :id
                AND eliminado = 0
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
    // Actualizar información
    // =========================
    public function actualizar($datos)
    {
        
        $sql = "

            UPDATE usuarios

            SET

                nombre = :nombre,

                apellido_paterno = :apellido_paterno,

                apellido_materno = :apellido_materno,

                correo = :correo,

                telefono = :telefono

            WHERE id = :id

        ";

        $query = $this->conexion->prepare($sql);

        return $query->execute([

            ':nombre' => $datos['nombre'],

            ':apellido_paterno' => $datos['apellido_paterno'],

            ':apellido_materno' => $datos['apellido_materno'],

            ':correo' => $datos['correo'],

            ':telefono' => $datos['telefono'],

            ':id' => $datos['id']

        ]);

    }

    // =========================
    // Buscar correo en otro usuario
    // =========================
    public function correoExiste($correo, $usuarioId)
    {

        $sql = "

            SELECT id

            FROM usuarios

            WHERE correo = :correo

            AND id <> :id

            AND eliminado = 0

            LIMIT 1

        ";

        $query = $this->conexion->prepare($sql);

        $query->execute([

            ':correo' => $correo,

            ':id' => $usuarioId

        ]);

        return $query->fetch(PDO::FETCH_ASSOC);

    }

    // =========================
    // Actualizar foto de perfil
    // =========================
    public function actualizarFoto($usuarioId, $nombreFoto)
    {
        $sql = "
            UPDATE usuarios
            SET foto = :foto
            WHERE id = :id
            AND eliminado = 0
        ";

        $query = $this->conexion->prepare($sql);

        return $query->execute([
            ':foto' => $nombreFoto,
            ':id' => $usuarioId
        ]);
    }

}