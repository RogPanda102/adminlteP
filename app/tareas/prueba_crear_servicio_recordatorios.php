<?php

require_once __DIR__ . '/../models/Servicios.php';
require_once __DIR__ . '/../models/RecordatorioServicio.php';

$servicioModelo = new Servicio();
$recordatorioModelo = new RecordatorioServicio();


// =========================================
// DATOS DE PRUEBA
// =========================================

$datos = [

    'req'                 => 'PRUEBA-REC-001',
    'folio'               => 'PRUEBA-001',
    'elaboro'             => 'Prueba',
    'partida'             => 'Prueba de recordatorios',

    'analista_id'         => null,
    'dependencia_id'      => null,
    'tipo_servicio_id'    => null,

    'tiempo_cantidad'     => 1,
    'tiempo_unidad'       => 'mes',

    'fecha_contratacion'  => date('Y-m-d'),
    'inicio'              => '2026-10-01',
    'finalizacion'        => '2026-11-01',

    'adjudicado_id'       => null,

    'anio'                => 2026,

    'creado_por'          => 5,
    'actualizado_por'     => null

];


// =========================================
// CREAR SERVICIO
// =========================================

$servicioId = $servicioModelo->guardar($datos);

echo "Servicio creado.\n";
echo "ID generado: {$servicioId}\n\n";


// =========================================
// CREAR RECORDATORIOS
// =========================================

$recordatorios = [
    90,
    30,
    7,
    0
];

foreach ($recordatorios as $diasAntes) {

    $resultado = $recordatorioModelo->crear([

        'servicio_id' => $servicioId,
        'dias_antes'  => $diasAntes,
        'activo'      => 1

    ]);

    echo "Recordatorio {$diasAntes} días: ";

    echo $resultado
        ? "CREADO\n"
        : "ERROR\n";
}


// =========================================
// MOSTRAR RESULTADO
// =========================================

echo "\n";
echo "Recordatorios registrados:\n\n";

print_r(
    $recordatorioModelo->obtenerPorServicio($servicioId)
);