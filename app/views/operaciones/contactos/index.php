<?php
$analistas = $analistas ?? [];
$encargados = $encargados ?? [];
?>
<!-- TABULATOR CSS -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
    crossorigin="anonymous" />

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

                        <button
                            type="button"
                            class="btn btn-sm btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#modalAnalista">
                            <i class="bi bi-plus-circle me-1"></i>
                            Nuevo analista
                        </button>

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

                        <button
                            type="button"
                            class="btn btn-sm btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEncargado">
                            <i class="bi bi-plus-circle me-1"></i>
                            Nuevo encargado
                        </button>

                    </div>

                    <div id="tabla-encargados"></div>

                </div>

            </div>



        </div>
    </div>
</main>

<!-- MODAL ANALISTA -->
<div
    class="modal fade"
    id="modalAnalista"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                action="<?= BASE_URL ?>contactos/guardarAnalista"
                method="POST">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Nuevo Analista
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Teléfono
                        </label>

                        <input
                            type="text"
                            name="telefono"
                            class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Correo
                        </label>

                        <input
                            type="email"
                            name="correo"
                            class="form-control">

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Guardar
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- MODAL ENCARGADO -->
<div
    class="modal fade"
    id="modalEncargado"
    tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form
                action="<?= BASE_URL ?>contactos/guardarEncargado"
                method="POST">

                <div class="modal-header">

                    <h5 class="modal-title">
                        Nuevo Encargado
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="nombre"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Teléfono
                        </label>

                        <input
                            type="text"
                            name="telefono"
                            class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Dependencia
                        </label>

                        <input
                            type="text"
                            name="dependencia"
                            class="form-control"
                            required>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        Guardar
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- TABULATOR JS -->
<script
    src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/js/tabulator.min.js"
    crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

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