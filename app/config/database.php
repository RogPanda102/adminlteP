<?php

class Database
{
    private static $host = 'localhost';
    private static $database = 'DataPandasoft';
    private static $username = 'root';
    private static $password = '';
    private static $charset = 'utf8mb4';

    public static function connect()
    {
        try {

            $conexion_string = "mysql:host=" . self::$host .
                               ";dbname=" . self::$database .
                               ";charset=" . self::$charset;

            $conexion = new PDO(
                $conexion_string,
                self::$username,
                self::$password
            );

            $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $conexion->setAttribute(
                PDO::ATTR_DEFAULT_FETCH_MODE,
                PDO::FETCH_ASSOC
            );

            return $conexion;

        } catch (PDOException $e) {

            die("Error de conexión: " . $e->getMessage());

        }
    }
}