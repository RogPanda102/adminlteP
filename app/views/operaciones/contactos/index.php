<?php
$analistas = $analistas ?? [];
$encargados = $encargados ?? [];
?>

<!-- ========================================================= -->
<!-- TABULATOR CSS -->
<!-- ========================================================= -->

<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
    crossorigin="anonymous"
/>


<main class="app-main">

    <div class="app-content">

        <div class="container-fluid">


            <!-- ===================================================== -->
            <!-- ANALISTAS -->
            <!-- ===================================================== -->

            <div class="card shadow-sm border-0 mb-4">


                <!-- ================= HEADER ================= -->

                <div class="card-header bg-white border-bottom">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h3 class="card-title mb-1 fw-bold">

                                <i class="bi bi-person-vcard text-primary me-2"></i>

                                Analistas

                            </h3>

                            <small class="text-muted">

                                Administración y consulta de analistas registrados.

                            </small>

                        </div>


                        <button
                            type="button"
                            class="btn btn-primary px-3"
                            data-bs-toggle="modal"
                            data-bs-target="#modalAnalista">

                            <i class="bi bi-person-plus me-1"></i>

                            Nuevo analista

                        </button>

                    </div>

                </div>


                <!-- ================= BODY ================= -->

                <div class="card-body">

                    <div
                        id="tabla-analistas"
                        class="table-responsive">
                    </div>

                </div>


            </div>



            <!-- ===================================================== -->
            <!-- ENCARGADOS -->
            <!-- ===================================================== -->

            <div class="card shadow-sm border-0">


                <!-- ================= HEADER ================= -->

                <div class="card-header bg-white border-bottom">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <h3 class="card-title mb-1 fw-bold">

                                <i class="bi bi-building-check text-success me-2"></i>

                                Encargados de dependencia

                            </h3>

                            <small class="text-muted">

                                Administración y consulta de encargados registrados.

                            </small>

                        </div>


                        <button
                            type="button"
                            class="btn btn-success px-3"
                            data-bs-toggle="modal"
                            data-bs-target="#modalEncargado">

                            <i class="bi bi-person-plus me-1"></i>

                            Nuevo encargado

                        </button>

                    </div>

                </div>


                <!-- ================= BODY ================= -->

                <div class="card-body">

                    <div
                        id="tabla-encargados"
                        class="table-responsive">
                    </div>

                </div>


            </div>


        </div>

    </div>

</main>



<!-- ========================================================= -->
<!-- MODAL ANALISTA -->
<!-- ========================================================= -->

<div
    class="modal fade"
    id="modalAnalista"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">


            <!-- HEADER -->

            <div class="modal-header bg-primary text-white">

                <div class="d-flex align-items-center">

                    <div
                        class="d-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-3 me-3"
                        style="width:42px;height:42px;">

                        <i class="bi bi-person-plus fs-4"></i>

                    </div>

                    <div>

                        <h5 class="modal-title fw-bold mb-0">
                            Nuevo analista
                        </h5>

                        <small class="opacity-75">
                            Registrar un nuevo analista
                        </small>

                    </div>

                </div>


                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <!-- FORMULARIO -->

            <form
                action="<?= BASE_URL ?>contactos/guardarAnalista"
                method="POST">


                <div class="modal-body p-4">


                    <!-- NOMBRE -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Nombre

                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light">

                                <i class="bi bi-person text-primary"></i>

                            </span>

                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                placeholder="Nombre del analista"
                                required>

                        </div>

                    </div>


                    <!-- TELEFONO -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Teléfono

                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light">

                                <i class="bi bi-telephone text-primary"></i>

                            </span>

                            <input
                                type="text"
                                name="telefono"
                                class="form-control"
                                placeholder="Número telefónico">

                        </div>

                    </div>


                    <!-- CORREO -->

                    <div class="mb-2">

                        <label class="form-label fw-semibold">

                            Correo electrónico

                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light">

                                <i class="bi bi-envelope text-primary"></i>

                            </span>

                            <input
                                type="email"
                                name="correo"
                                class="form-control"
                                placeholder="correo@ejemplo.com">

                        </div>

                    </div>


                </div>


                <!-- FOOTER -->

                <div class="modal-footer bg-light border-top">

                    <button
                        type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="modal">

                        <i class="bi bi-x-circle me-1"></i>

                        Cancelar

                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary px-4">

                        <i class="bi bi-check-circle me-1"></i>

                        Guardar analista

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>



<!-- ========================================================= -->
<!-- MODAL ENCARGADO -->
<!-- ========================================================= -->

<div
    class="modal fade"
    id="modalEncargado"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">


            <!-- HEADER -->

            <div class="modal-header bg-success text-white">

                <div class="d-flex align-items-center">

                    <div
                        class="d-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-3 me-3"
                        style="width:42px;height:42px;">

                        <i class="bi bi-person-plus fs-4"></i>

                    </div>

                    <div>

                        <h5 class="modal-title fw-bold mb-0">

                            Nuevo encargado

                        </h5>

                        <small class="opacity-75">

                            Registrar un nuevo encargado

                        </small>

                    </div>

                </div>


                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <!-- FORMULARIO -->

            <form
                action="<?= BASE_URL ?>contactos/guardarEncargado"
                method="POST">


                <div class="modal-body p-4">


                    <!-- NOMBRE -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Nombre

                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light">

                                <i class="bi bi-person text-success"></i>

                            </span>

                            <input
                                type="text"
                                name="nombre"
                                class="form-control"
                                placeholder="Nombre del encargado"
                                required>

                        </div>

                    </div>


                    <!-- TELEFONO -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">

                            Teléfono

                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light">

                                <i class="bi bi-telephone text-success"></i>

                            </span>

                            <input
                                type="text"
                                name="telefono"
                                class="form-control"
                                placeholder="Número telefónico">

                        </div>

                    </div>


                    <!-- DEPENDENCIA -->

                    <div class="mb-2">

                        <label class="form-label fw-semibold">

                            Dependencia

                        </label>

                        <div class="input-group">

                            <span class="input-group-text bg-light">

                                <i class="bi bi-building text-success"></i>

                            </span>

                            <input
                                type="text"
                                name="dependencia"
                                class="form-control"
                                placeholder="Dependencia"
                                required>

                        </div>

                    </div>


                </div>


                <!-- FOOTER -->

                <div class="modal-footer bg-light border-top">

                    <button
                        type="button"
                        class="btn btn-light border"
                        data-bs-dismiss="modal">

                        <i class="bi bi-x-circle me-1"></i>

                        Cancelar

                    </button>


                    <button
                        type="submit"
                        class="btn btn-success px-4">

                        <i class="bi bi-check-circle me-1"></i>

                        Guardar encargado

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>



<!-- ========================================================= -->
<!-- TABULATOR JS -->
<!-- ========================================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/js/tabulator.min.js"
    crossorigin="anonymous">
</script>



<script>

document.addEventListener('DOMContentLoaded', function () {


    // =====================================================
    // TABLA ANALISTAS ERP PRO
    // =====================================================

    const tablaAnalistas = new Tabulator('#tabla-analistas', {

        layout: 'fitColumns',

        pagination: true,

        paginationSize: 10,

        movableColumns: true,

        responsiveLayout: "collapse",


        // =================================================
        // COLUMNAS
        // =================================================

        columns: [


            // ==============================
            // NOMBRE
            // ==============================

            {
                title: "NOMBRE",

                field: "nombre",

                minWidth: 220,

                formatter: function (cell) {

                    return `

                        <div class="erp-main-cell">

                            <div class="erp-title">

                                <i class="bi bi-person-circle text-primary me-2"></i>

                                ${cell.getValue() || ''}

                            </div>

                        </div>

                    `;

                }

            },


            // ==============================
            // TELÉFONO
            // ==============================

            {
                title: "TELÉFONO",

                field: "telefono",

                minWidth: 180,

                formatter: function (cell) {

                    const valor = cell.getValue();

                    if (!valor) {

                        return `
                            <span class="text-muted">
                                No registrado
                            </span>
                        `;

                    }

                    return `

                        <div class="erp-sub">

                            <i class="bi bi-telephone me-2"></i>

                            ${valor}

                        </div>

                    `;

                }

            },


            // ==============================
            // CORREO
            // ==============================

            {
                title: "CORREO",

                field: "correo",

                minWidth: 260,

                formatter: function (cell) {

                    const valor = cell.getValue();

                    if (!valor) {

                        return `
                            <span class="text-muted">
                                No registrado
                            </span>
                        `;

                    }

                    return `

                        <div class="erp-sub">

                            <i class="bi bi-envelope me-2"></i>

                            ${valor}

                        </div>

                    `;

                }

            }


        ],


        data: <?= json_encode($analistas, JSON_UNESCAPED_UNICODE) ?>

    });



    // =====================================================
    // TABLA ENCARGADOS ERP PRO
    // =====================================================

    const tablaEncargados = new Tabulator('#tabla-encargados', {

        layout: 'fitColumns',

        pagination: true,

        paginationSize: 10,

        movableColumns: true,

        responsiveLayout: "collapse",


        // =================================================
        // COLUMNAS
        // =================================================

        columns: [


            // ==============================
            // NOMBRE
            // ==============================

            {
                title: "NOMBRE",

                field: "nombre",

                minWidth: 220,

                formatter: function (cell) {

                    return `

                        <div class="erp-main-cell">

                            <div class="erp-title">

                                <i class="bi bi-person-circle text-success me-2"></i>

                                ${cell.getValue() || ''}

                            </div>

                        </div>

                    `;

                }

            },


            // ==============================
            // TELÉFONO
            // ==============================

            {
                title: "TELÉFONO",

                field: "telefono",

                minWidth: 180,

                formatter: function (cell) {

                    const valor = cell.getValue();

                    if (!valor) {

                        return `
                            <span class="text-muted">
                                No registrado
                            </span>
                        `;

                    }

                    return `

                        <div class="erp-sub">

                            <i class="bi bi-telephone me-2"></i>

                            ${valor}

                        </div>

                    `;

                }

            },


            // ==============================
            // DEPENDENCIA
            // ==============================

            {
                title: "DEPENDENCIA",

                field: "dependencia",

                minWidth: 260,

                formatter: function (cell) {

                    const valor = cell.getValue();

                    if (!valor) {

                        return `
                            <span class="text-muted">
                                No registrada
                            </span>
                        `;

                    }

                    return `

                        <span class="folio-badge">

                            <i class="bi bi-building me-1"></i>

                            ${valor}

                        </span>

                    `;

                }

            }


        ],


        data: <?= json_encode($encargados, JSON_UNESCAPED_UNICODE) ?>

    });


});

</script>