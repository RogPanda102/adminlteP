<?php
    $cotizaciones = $cotizaciones ?? '';
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
                        Cotizaciones 2026
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
                        <!-- IZQUIERDA: NUEVA COTIZACIÓN -->
                        <a
                            href="<?= BASE_URL ?>cotizaciones/nueva"
                            class="btn btn-sm btn-success"
                        >
                            <i class="bi bi-plus-circle me-1"></i>
                            Nueva cotización
                        </a>
                        <!-- DERECHA: CSV -->
                        <button
                            id="export-csv"
                            type="button"
                            class="btn btn-sm btn-outline-success"
                        >
                            <i class="bi bi-filetype-csv me-1"></i>
                            CSV Exportar
                        </button>
                    </div>
                    <div id="tabla-cotizaciones"></div>
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
<script src="<?= BASE_URL ?>assets/js/especificos/cotizaciones/cotizaciones_index.js"></script> <!-- aqui esta  -->


<script>

    document.addEventListener('DOMContentLoaded', function () {

        const tabla = new Tabulator('#tabla-cotizaciones', {

            layout: 'fitColumns',

            pagination: true,

            paginationSize: 10,

            columns: 
            [

                {
                    title: 'Fecha',
                    field: 'fecha'
                },

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
                    title: 'Proveedor',
                    field: 'proveedor'
                },

                {
                    title: 'Analista',
                    field: 'analista'
                },

                {
                    title: 'Estatus',
                    field: 'estatus'
                }

            ],

            data: <?= json_encode($cotizaciones) ?>

        });

        // FILTRO
        document
            .getElementById('table-filter')
            .addEventListener('keyup', function () {

                tabla.setFilter([
                    [
                        {
                            field: 'empresa',
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

                tabla.download('csv', 'cotizaciones_2026.csv');

            });

    });

</script>