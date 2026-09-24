<?php

require_once __DIR__ . '/../models/EventoNotificacion.php';

$modelo = new EventoNotificacion();

echo "==============================\n";
echo "PRUEBA DEL METODO existe()\n";
echo "==============================\n\n";

// =========================================
// PRUEBA 1: EVENTO QUE YA EXISTE
// =========================================

$existe = $modelo->existe(
    1,
    '2026-09-18 09:00:00'
);

echo "Evento existente: ";

if ($existe) {
    echo "SI EXISTE\n";
} else {
    echo "NO EXISTE\n";
}


// =========================================
// PRUEBA 2: EVENTO QUE NO EXISTE
// =========================================

$existe = $modelo->existe(
    1,
    '2026-09-18 15:00:00'
);

echo "Evento inexistente: ";

if ($existe) {
    echo "SI EXISTE\n";
} else {
    echo "NO EXISTE\n";
}