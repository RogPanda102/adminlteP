<?php

require_once __DIR__ . '/../models/Historial.php';


function registrarHistorial(
    $modulo,
    $registro_id,
    $accion,
    $antes = null,
    $despues = null,
    $descripcion = null
)
{

    $modelo = new Historial();


    return $modelo->registrar([

        'modulo' => $modulo,

        'registro_id' => $registro_id,

        'accion' => $accion,

        'descripcion' =>
            $descripcion,


        'datos_anteriores' =>
            $antes,


        'datos_nuevos' =>
            $despues,


        'usuario_id' =>
            $_SESSION['usuario_id'] ?? null

    ]);

}