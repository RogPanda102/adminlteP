<?php
    $proveedores = $proveedores ?? [];
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
                        Proveedores
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
                        <!-- IZQUIERDA: NUEVO PROVEEDOR -->
                        <a
                            href="<?= BASE_URL ?>proveedores/nueva"
                            class="btn btn-sm btn-success"
                        >
                            <i class="bi bi-plus-circle me-1"></i>
                            Nuevo proveedor
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
                    <div id="tabla-proveedores"></div>
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
<script src="<?= BASE_URL ?>assets/js/especificos/proveedores/proveedores_index.js"></script> <!-- aqui esta  -->


<script>

    document.addEventListener('DOMContentLoaded', function () {

        const tabla = new Tabulator('#tabla-proveedores', {

            layout: 'fitColumns',

            pagination: true,

            paginationSize: 10,

            columns: [

                {
                    title: 'Proveedor',
                    field: 'proveedor'
                },

                {
                    title: 'Servicios',
                    field: 'servicios'
                },

                {
                    title: 'Ubicación',
                    field: 'ubicacion'
                },

                {
                    title: 'Contacto',
                    field: 'contacto'
                },

                {
                    title: 'Teléfono',
                    field: 'telefono'
                },

                {
                    title: 'Email',
                    field: 'email'
                },

                {
                    title: 'Enlace',
                    field: 'enlace'
                }

            ],

            data: <?= json_encode($proveedores) ?>

        });

        // FILTRO
        document
            .getElementById('table-filter')
            .addEventListener('keyup', function () {

                tabla.setFilter([
                    [
                        {
                            field: 'proveedor',
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

                tabla.download('csv', 'proveedores.csv');

            });

    });

</script>