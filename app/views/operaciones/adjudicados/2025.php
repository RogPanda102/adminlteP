<?php
    $adjudicados = $adjudicados ?? [];
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
                        Adjudicados 2025
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
                        
                        <button
                            id="export-csv"
                            type="button"
                            class="btn btn-sm btn-outline-success"
                        >
                            <i class="bi bi-filetype-csv me-1"></i>
                            CSV Exportar
                        </button>
                    </div>
                    <div id="tabla-adjudicados"></div>
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

        const tabla = new Tabulator('#tabla-adjudicados', {

            layout: 'fitColumns',

            pagination: true,

            paginationSize: 10,

            columns:
            [
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
                    title: 'Fecha Elaboración',
                    field: 'fecha_elaboracion',
                    formatter: function(cell) {

                        const valor = cell.getValue();

                        if (!valor) return '';

                        const fecha = new Date(valor + 'T00:00:00');

                        return fecha.toLocaleDateString('es-MX', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        });

                    }
                },

                {
                    title: 'Inicio Entrega',
                    field: 'fecha_inicio_entrega',
                    formatter: function(cell) {

                        const valor = cell.getValue();

                        if (!valor) return '';

                        const fecha = new Date(valor + 'T00:00:00');

                        return fecha.toLocaleDateString('es-MX', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        });

                    }
                },

                {
                    title: 'Fin Entrega',
                    field: 'fecha_fin_entrega',
                    formatter: function(cell) {

                        const valor = cell.getValue();

                        if (!valor) return '';

                        const fecha = new Date(valor + 'T00:00:00');

                        return fecha.toLocaleDateString('es-MX', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        });

                    }
                },

                {
                    title: 'Total',
                    field: 'total',
                    hozAlign: 'right',
                    formatter: function(cell) {

                        const valor = parseFloat(cell.getValue() || 0);

                        return valor.toLocaleString('es-MX', {
                            style: 'currency',
                            currency: 'MXN'
                        });

                    }
                },

                {
                    title: 'Día Pago',
                    field: 'dia_pago',
                    formatter: function(cell) {

                        const valor = cell.getValue();

                        if (!valor) return '';

                        const fecha = new Date(valor + 'T00:00:00');

                        return fecha.toLocaleDateString('es-MX', {
                            day: '2-digit',
                            month: 'long',
                            year: 'numeric'
                        });

                    }
                },

                {
                    title: 'Pago',
                    field: 'pago'
                },

                {
                    title: 'Dependencia',
                    field: 'dependencia'
                }
            ],

            data: <?= json_encode($adjudicados) ?>

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