<?php

function comprobar_acceso($tarea_actual = '')
{
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    $rol_actual = $_SESSION['rol_actual'] ?? null;

    switch ($rol_actual) {

        case ROL_ADMINISTRADOR['clave']:
            return in_array($tarea_actual, ACCESO_ADMINISTRADOR);
        break;

        case ROL_USUARIO['clave']:
            return in_array($tarea_actual, ACCESO_USUARIO);
        break;

        default:
            return false;
        break;
    }
}