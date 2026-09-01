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
// =====================================================
// D E T E R M I N A R   E V E N T O   D E   V E N C I M I E N T O
// =====================================================

function determinarEventoVencimiento($finalizacion)
{
    if (empty($finalizacion)) {
        return null;
    }

    try {

        $hoy = new DateTime();
        $fechaFinalizacion = new DateTime($finalizacion);

        $hoy->setTime(0, 0, 0);
        $fechaFinalizacion->setTime(0, 0, 0);

        $diferencia = $hoy->diff($fechaFinalizacion);

        // Si ya pasó la fecha, no genera evento
        if ($fechaFinalizacion < $hoy) {
            return null;
        }

        $diasRestantes = (int) $diferencia->days;

        switch ($diasRestantes) {

            case 90:
                return 'vencimiento_90';

            case 30:
                return 'vencimiento_30';

            case 7:
                return 'vencimiento_7';

            case 0:
                return 'vencimiento_hoy';

            default:
                return null;
        }

    } catch (Exception $e) {

        return null;

    }
}