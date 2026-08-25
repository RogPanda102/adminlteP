<?php
// =====================================================
// C A L C U L A R   F E C H A   I N I C I O
// =====================================================

function calcularFechaInicio($finalizacion, $cantidad, $unidad)
{
    if (empty($finalizacion) || empty($cantidad) || empty($unidad)) {
        return null;
    }
    $cantidad = (int) $cantidad;
    try {

        $fecha = new DateTime($finalizacion);

        switch ($unidad) {

            case 'dias':
                $fecha->modify("-{$cantidad} days");
                break;

            case 'meses':
                $fecha->modify("-{$cantidad} months");
                break;

            case 'años':
                $fecha->modify("-{$cantidad} years");
                break;

            default:
                return null;
        }

        return $fecha->format('Y-m-d');

    } catch (Exception $e) {

        return null;

    }
}
// =====================================================
// C A L C U L A R   F E C H A   F I N A L
// =====================================================

function calcularFechaFinalizacion($inicio, $cantidad, $unidad)
{
    if (empty($inicio) || empty($cantidad) || empty($unidad)) {
        return null;
    }
    $cantidad = (int) $cantidad;
    try {

        $fecha = new DateTime($inicio);

        switch ($unidad) {

            case 'dias':
                $fecha->modify("+{$cantidad} days");
                break;

            case 'meses':
                $fecha->modify("+{$cantidad} months");
                break;

            case 'años':
                $fecha->modify("+{$cantidad} years");
                break;

            default:
                return null;
        }

        return $fecha->format('Y-m-d');

    } catch (Exception $e) {

        return null;

    }
}
// =====================================================
// C A L C U L A R   F E C H A S   D E L   S E R V I C I O
// =====================================================

function calcularFechasServicio(
    $inicio,
    $finalizacion,
    $cantidad,
    $unidad
) {

    $resultado = [
        'inicio' => $inicio ?: null,
        'finalizacion' => $finalizacion ?: null
    ];

    // ==========================================
    // FALTAN DATOS DE DURACIÓN
    // ==========================================

    if (empty($cantidad) || empty($unidad)) {
        return $resultado;
    }

    // ==========================================
    // EXISTE INICIO PERO NO FINALIZACIÓN
    // ==========================================

    if (!empty($inicio) && empty($finalizacion)) {

        $resultado['finalizacion'] =
            calcularFechaFinalizacion(
                $inicio,
                $cantidad,
                $unidad
            );

        return $resultado;
    }

    // ==========================================
    // EXISTE FINALIZACIÓN PERO NO INICIO
    // ==========================================

    if (empty($inicio) && !empty($finalizacion)) {

        $resultado['inicio'] =
            calcularFechaInicio(
                $finalizacion,
                $cantidad,
                $unidad
            );

        return $resultado;
    }

    return $resultado;
}