<?php
    $analistas = $analistas ?? [];
    $encargados = $encargados ?? [];
?>
<!-- TABULATOR CSS -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
    crossorigin="anonymous"
/>

<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">

            <!-- CONTACTOS -->
            <div class="card mb-4">

                <div class="card-header">
                    <h3 class="card-title">
                        Analistas
                    </h3>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <a
                            href="#"
                            class="btn btn-sm btn-success"
                        >
                            <i class="bi bi-plus-circle me-1"></i>
                            Nuevo analista
                        </a>

                    </div>

                    <div id="tabla-analistas"></div>

                </div>

            </div>

            <!-- DEPENDENCIAS -->
            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">
                        Encargados de dependencia
                    </h3>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <a
                            href="#"
                            class="btn btn-sm btn-success"
                        >
                            <i class="bi bi-plus-circle me-1"></i>
                            Nuevo encargado
                        </a>

                    </div>

                    <div id="tabla-encargados"></div>

                </div>

            </div>

        </div>
    </div>
</main>

<!-- TABULATOR JS -->
<script
    src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/js/tabulator.min.js"
    crossorigin="anonymous"
></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    // ANALISTAS
    const tablaAnalistas = new Tabulator('#tabla-analistas', {

        layout: 'fitColumns',

        pagination: true,

        paginationSize: 10,

        columns: [

            {
                title: 'Nombre',
                field: 'nombre'
            },

            {
                title: 'Teléfono',
                field: 'telefono'
            },

            {
                title: 'Correo',
                field: 'correo'
            }

        ],

        data: <?= json_encode($analistas) ?>

    });

    // ENCARGADOS
    const tablaEncargados = new Tabulator('#tabla-encargados', {

        layout: 'fitColumns',

        pagination: true,

        paginationSize: 10,

        columns: [

            {
                title: 'Nombre',
                field: 'nombre'
            },

            {
                title: 'Teléfono',
                field: 'telefono'
            },

            {
                title: 'Dependencia',
                field: 'dependencia'
            }

        ],

        data: <?= json_encode($encargados) ?>

    });

});

</script>