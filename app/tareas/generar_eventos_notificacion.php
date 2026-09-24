<?php

require_once __DIR__ . '/../models/Servicios.php';
require_once __DIR__ . '/../models/RecordatorioServicio.php';
require_once __DIR__ . '/../models/EventoNotificacion.php';

$servicioModelo = new Servicio();
$recordatorioModelo = new RecordatorioServicio();
$eventoModelo = new EventoNotificacion();


// =========================================
// OBTENER SERVICIOS
// =========================================

$servicios = $servicioModelo->obtenerServiciosParaNotificaciones();

if (!$servicios) {
    die("No hay servicios para procesar.\n");
}


// =========================================
// HORARIOS PREDETERMINADOS
// =========================================

$horarios = [
    '09:00:00',
    '13:00:00',
    '17:00:00'
];


// =========================================
// CONTADORES
// =========================================

$totalCreados = 0;
$totalExistentes = 0;
$totalServicios = 0;


// =========================================
// PROCESAR SERVICIOS
// =========================================

foreach ($servicios as $servicio) {

    $servicioId = $servicio['id'];

    echo "\n";
    echo "Servicio ID: {$servicioId}\n";
    echo "Finalización: {$servicio['finalizacion']}\n";


    // =========================================
    // OBTENER RECORDATORIOS ACTIVOS
    // =========================================

    $recordatorios = $recordatorioModelo
        ->obtenerActivosPorServicio($servicioId);

    if (!$recordatorios) {

        echo "Sin recordatorios activos.\n";

        continue;
    }


    $totalServicios++;


    // =========================================
    // PROCESAR CADA RECORDATORIO
    // =========================================

    foreach ($recordatorios as $recordatorio) {

        $diasAntes = (int) $recordatorio['dias_antes'];

        // -----------------------------------------
        // CALCULAR FECHA DEL RECORDATORIO
        // -----------------------------------------

        $fechaRecordatorio = new DateTime(
            $servicio['finalizacion']
        );

        $fechaRecordatorio->modify(
            "-{$diasAntes} days"
        );

        $fecha = $fechaRecordatorio->format('Y-m-d');


        // =========================================
        // IGNORAR FECHAS YA PASADAS
        // =========================================

        $hoy = date('Y-m-d');

        if ($fecha < $hoy) {

            echo "  Recordatorio: {$diasAntes} días antes";
            echo " -> {$fecha} (ya pasó, se omite)\n";

            continue;
        }


        echo "  Recordatorio: {$diasAntes} días antes";
        echo " -> {$fecha}\n";


        // =========================================
        // GENERAR LOS 3 HORARIOS
        // =========================================

        foreach ($horarios as $hora) {

            $fechaHora = $fecha . ' ' . $hora;


            // -----------------------------------------
            // COMPROBAR SI YA EXISTE
            // -----------------------------------------

            if ($eventoModelo->existe(
                $recordatorio['id'],
                $fechaHora
            )) {

                $totalExistentes++;

                echo "    Ya existe: {$fechaHora}\n";

                continue;
            }


            // -----------------------------------------
            // CREAR EVENTO
            // -----------------------------------------

            $eventoModelo->crear([
                'servicio_id' => $servicioId,
                'recordatorio_id' => $recordatorio['id'],
                'usuario_id' => $servicio['creado_por'],
                'fecha_hora_programada' => $fechaHora
            ]);

            $totalCreados++;

            echo "    Creado: {$fechaHora}\n";
        }
    }
}


// =========================================
// RESUMEN
// =========================================

echo "\n";
echo "==============================\n";
echo "GENERADOR FINALIZADO\n";
echo "==============================\n";
echo "Servicios procesados: {$totalServicios}\n";
echo "Eventos creados: {$totalCreados}\n";
echo "Eventos existentes: {$totalExistentes}\n";