<?php

class Env
{
    public static function cargar($ruta)
    {
        if (!file_exists($ruta)) {
            throw new RuntimeException(
                'No se encontró el archivo .env'
            );
        }

        $lineas = file(
            $ruta,
            FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES
        );

        foreach ($lineas as $linea) {
            $linea = trim($linea);

            if ($linea === '' || strpos($linea, '#') === 0) {
                continue;
            }

            if (strpos($linea, '=') === false) {
                continue;
            }

            [$nombre, $valor] = explode('=', $linea, 2);

            $nombre = trim($nombre);
            $valor = trim($valor);

            // Eliminar comillas exteriores.
            if (
                strlen($valor) >= 2 &&
                (
                    ($valor[0] === '"' && substr($valor, -1) === '"') ||
                    ($valor[0] === "'" && substr($valor, -1) === "'")
                )
            ) {
                $valor = substr($valor, 1, -1);
            }

            if ($nombre !== '') {
                putenv("$nombre=$valor");
                $_ENV[$nombre] = $valor;
            }
        }
    }
}