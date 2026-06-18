<?php
    $servicios = $servicios ?? [];
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
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        Servicios 2026
                    </h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 16rem">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>

                            <input
                                id="table-filter"
                                type="search"
                                class="form-control"
                                placeholder="Buscar..."
                            />
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <!-- NUEVO SERVICIO -->
                        <a
                            href="<?= BASE_URL ?>servicios/nueva"
                            class="btn btn-sm btn-success"
                        >
                            <i class="bi bi-plus-circle me-1"></i>
                            Nuevo servicio
                        </a>

                        <!-- EXPORTAR -->
                        <button
                            id="export-csv"
                            type="button"
                            class="btn btn-sm btn-outline-success"
                        >
                            <i class="bi bi-filetype-csv me-1"></i>
                            CSV Exportar
                        </button>

                    </div>

                    <div id="tabla-servicios"></div>

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

    const tabla = new Tabulator('#tabla-servicios', {

        layout: 'fitColumns',

        pagination: true,

        paginationSize: 10,

        columns: [

            {
                title: 'REQ',
                field: 'req'
            },

            {
                title: 'Folio',
                field: 'folio'
            },

            {
                title: 'Elaboró',
                field: 'elaboro'
            },

            {
                title: 'Partida',
                field: 'partida'
            },

            {
                title: 'Analista',
                field: 'analista'
            },

            {
                title: 'Tiempo Contratación',
                field: 'tiempo_contratacion'
            },

            {
                title: 'Fecha Contratación',
                field: 'fecha_contratacion'
            },

            {
                title: 'Inicio',
                field: 'inicio'
            },

            {
                title: 'Finalización',
                field: 'finalizacion'
            },

            {
                title: 'Dependencia',
                field: 'dependencia'
            }

        ],

        data: <?= json_encode($servicios) ?>

    });

    // FILTRO
    document
        .getElementById('table-filter')
        .addEventListener('keyup', function () {

            tabla.setFilter([
                [
                    {
                        field: 'req',
                        type: 'like',
                        value: this.value
                    }
                ],
                [
                    {
                        field: 'folio',
                        type: 'like',
                        value: this.value
                    }
                ],
                [
                    {
                        field: 'dependencia',
                        type: 'like',
                        value: this.value
                    }
                ]
            ]);

        });

    // EXPORTAR CSV
    document
        .getElementById('export-csv')
        .addEventListener('click', function () {

            tabla.download(
                'csv',
                'servicios_2026.csv'
            );

        });

});

</script>