<?php
    $cotizaciones = $cotizaciones ?? '';
?>
<!-- TABULATOR CSS -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
  crossorigin="anonymous"
/>

<style>
    #tabla-adjudicados .tabulator-row {
        cursor: pointer;
    }

    #tabla-adjudicados .tabulator-row:hover {
        background-color: rgba(13, 110, 253, 0.08);
    }
    /* ===============================
    FILAS ERP STYLE
    ================================*/
    .tabulator-row {
        border-left: 4px solid transparent;
        transition: all .2s ease;
    }

    .tabulator-row:hover {
        background: #f8fafc !important;
    }

    /* estados */
    .row-pagado {
        border-left: 4px solid #16a34a;
    }

    .row-pendiente {
        border-left: 4px solid #f59e0b;
    }

    .row-cancelado {
        border-left: 4px solid #dc2626;
    }

    /* ===============================
    FOLIO BADGE (KEY ERP DATA)
    ================================*/
    .folio-badge {
        background: #1f4b99;
        color: #fff;
        padding: 3px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }

    /* ===============================
    CELDA PRINCIPAL
    ================================*/
    .erp-main-cell {
        display: flex;
        flex-direction: column;
    }

    .erp-title {
        font-weight: 600;
        color: #111827;
    }

    .erp-sub {
        font-size: 12px;
        color: #6b7280;
    }

    /* ===============================
    FECHA
    ================================*/
    .erp-date {
        font-size: 13px;
        color: #374151;
    }

    /* ===============================
    TOTAL (IMPORTANTE VISUAL)
    ================================*/
    .erp-total {
        font-weight: 700;
        color: #0f172a;
    }


</style>

<main class="app-main">

    <div class="app-content">

        <div class="container-fluid">

            <div class="card shadow-sm border-0">

                <!-- ========================================= -->
                <!-- HEADER -->
                <!-- ========================================= -->

                <div class="card-header bg-white border-bottom">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <div>

                            <h3 class="card-title mb-1 fw-semibold">

                                <i class="bi bi-file-earmark-text text-primary me-2"></i>

                                Cotizaciones 2026

                            </h3>
                        </div>

                        <div class="card-tools">

                            <div class="input-group input-group-sm" style="width:260px;">

                                <span class="input-group-text bg-white">

                                    <i class="bi bi-search"></i>

                                </span>

                                <input
                                    id="table-filter"
                                    type="search"
                                    class="form-control"
                                    placeholder="Buscar cotización...">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ========================================= -->
                <!-- BODY -->
                <!-- ========================================= -->

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

                        <a
                            href="<?= BASE_URL ?>cotizaciones/nueva"
                            class="btn btn-success">

                            <i class="bi bi-plus-circle me-1"></i>

                            Nueva cotización

                        </a>

                        <button
                            id="export-csv"
                            class="btn btn-outline-success">

                            <i class="bi bi-filetype-csv me-1"></i>

                            Exportar CSV

                        </button>

                    </div>

                    <div id="tabla-cotizaciones"></div>

                </div>

            </div>

        </div>

    </div>

</main>

<!-- ================= OFFCANVAS DETALLE COTIZACIÓN ================= -->
<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasDetalleCotizacion"
    style="width:420px; transition:all .3s ease;"
>

    <!-- HEADER -->
    <div class="offcanvas-header border-bottom flex-column align-items-start">

        <div class="d-flex justify-content-between w-100">

            <h5 class="offcanvas-title mb-0 fw-semibold">
                Cotización
                <span id="erp-folio-title" class="text-primary"></span>
            </h5>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas">
            </button>

        </div>

        <div class="mt-2 d-flex align-items-center gap-2">

            <span
                id="erp-status"
                class="badge bg-secondary px-3 py-2 rounded-pill">

                Pendiente

            </span>

        </div>

        <div class="mt-2 d-flex gap-2 flex-wrap">

            <button
                class="btn btn-sm btn-primary px-3"
                id="btn-editar">

                ✏️ Editar

            </button>

            <button
                class="btn btn-sm btn-outline-dark px-3"
                id="btn-historial">

                🕓 Historial

            </button>

        </div>

    </div>

    <!-- BODY -->
    <div
        class="offcanvas-body p-0 bg-light d-flex"
        id="erp-wrapper">

        <div
            id="erp-panel-detalle"
            class="p-3"
            style="width:100%; transition:all .3s ease;">

            <!-- ================= GENERAL ================= -->

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-header bg-white border-bottom fw-semibold">

                    <i class="bi bi-info-circle me-1 text-primary"></i>

                    Información general

                </div>

                <div class="card-body py-2">

                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Fecha</span>
                        <span id="det-fecha" class="fw-semibold"></span>
                    </div>

                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">REQ</span>
                        <span id="det-req" class="fw-semibold"></span>
                    </div>

                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Folio</span>
                        <span id="det-folio" class="fw-semibold"></span>
                    </div>

                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Elaboró</span>
                        <span id="det-elaboro" class="fw-semibold"></span>
                    </div>

                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Partida</span>
                        <span id="det-partida" class="fw-semibold"></span>
                    </div>

                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Analista</span>
                        <span id="det-analista" class="fw-semibold"></span>
                    </div>

                </div>

            </div>

            <!-- ================= PROVEEDOR ================= -->

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-header bg-white border-bottom fw-semibold">

                    <i class="bi bi-truck me-1 text-success"></i>

                    Proveedor

                </div>

                <div class="card-body py-2">

                    <div class="d-flex justify-content-between py-1">

                        <span class="text-muted">
                            Proveedor
                        </span>

                        <span
                            id="det-proveedor"
                            class="fw-semibold">
                        </span>

                    </div>

                </div>

            </div>

            <!-- ================= ESTATUS ================= -->

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-header bg-white border-bottom fw-semibold">

                    <i class="bi bi-check2-circle me-1 text-warning"></i>

                    Estatus

                </div>

                <div class="card-body py-2">

                    <div class="d-flex justify-content-between py-1">

                        <span class="text-muted">
                            Estado
                        </span>

                        <span
                            id="det-estatus"
                            class="badge bg-light text-dark">
                        </span>

                    </div>

                </div>

            </div>

            <!-- ================= HISTORIAL ================= -->

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-header bg-white border-bottom fw-semibold">

                    <i class="bi bi-clock-history text-primary me-1"></i>

                    Auditoría del registro

                </div>

                <div class="card-body">

                    <div id="historial-items">

                        <div class="text-muted small">

                            Presiona historial para cargar cambios

                        </div>

                    </div>

                    <div
                        id="historial-detalle"
                        class="mt-3">

                        <div class="text-muted small">

                            Selecciona un cambio para ver el detalle

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ================= MODAL EDITAR COTIZACIÓN ================= -->
<div class="modal fade" id="modalEditarCotizacion" tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content shadow-lg">

            <!-- HEADER -->

            <div class="modal-header bg-primary text-white">

                <div>

                    <h5 class="modal-title mb-1">
                        <i class="bi bi-pencil-square me-2"></i>
                        Editar cotización
                    </h5>

                    <small>
                        Modificación de información administrativa
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form id="formEditarCotizacion">

                <div class="modal-body p-2">

                    <!-- IDS -->

                    <input type="hidden" id="edit-id">
                    <input type="hidden" id="edit-anio">
                    <input type="hidden" id="edit-analista_id">

                    <!-- ===================================================== -->
                    <!-- INFORMACIÓN GENERAL -->
                    <!-- ===================================================== -->

                    <div class="card mb-2">

                        <div
                            class="card-header bg-light py-2 section-toggle"
                            data-target="#sec-general">

                            <strong>

                                <i class="bi bi-info-circle me-1"></i>

                                Información general

                            </strong>

                        </div>

                        <div class="collapse show" id="sec-general">

                            <div class="card-body py-2">

                                <div class="row g-2">

                                    <div class="col-md-3">

                                        <label class="form-label mb-0">
                                            Fecha
                                        </label>

                                        <input
                                            type="date"
                                            class="form-control form-control-sm"
                                            id="edit-fecha">

                                    </div>

                                    <div class="col-md-3">

                                        <label class="form-label mb-0">
                                            REQ
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control form-control-sm"
                                            id="edit-req">

                                    </div>

                                    <div class="col-md-3">

                                        <label class="form-label mb-0">
                                            Folio
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control form-control-sm"
                                            id="edit-folio">

                                    </div>

                                    <div class="col-md-3">

                                        <label class="form-label mb-0">
                                            Elaboró
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control form-control-sm"
                                            id="edit-elaboro">

                                    </div>

                                    <div class="col-md-6">

                                        <label class="form-label mb-0">
                                            Partida
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control form-control-sm"
                                            id="edit-partida">

                                    </div>

                                    <div class="col-md-6">

                                        <label class="form-label mb-0">
                                            Proveedor
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control form-control-sm"
                                            id="edit-proveedor">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- ===================================================== -->
                    <!-- INFORMACIÓN ADMINISTRATIVA -->
                    <!-- ===================================================== -->

                    <div class="card mb-2">

                        <div
                            class="card-header bg-light py-2 section-toggle"
                            data-target="#sec-admin">

                            <strong>

                                <i class="bi bi-building me-1"></i>

                                Información administrativa

                            </strong>

                        </div>

                        <div class="collapse" id="sec-admin">

                            <div class="card-body py-2">

                                <div class="row g-2">

                                    <div class="col-md-6">

                                        <label class="form-label mb-0">
                                            Dependencia
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control form-control-sm"
                                            id="edit-dependencia">

                                    </div>

                                    <!-- ANALISTA -->

                                    <div class="col-md-6 position-relative">

                                        <label class="form-label mb-0">
                                            Analista
                                        </label>

                                        <input
                                            type="text"
                                            class="form-control form-control-sm"
                                            id="edit-analista"
                                            autocomplete="off">

                                        <input
                                            type="hidden"
                                            id="edit-analista_id">

                                        <div
                                            id="lista-edit-analista"
                                            class="list-group position-absolute w-100 shadow"
                                            style="
                                                display:none;
                                                z-index:1065;
                                                max-height:220px;
                                                overflow-y:auto;
                                            ">
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- ===================================================== -->
                    <!-- OPCIONES -->
                    <!-- ===================================================== -->

                    <div class="card mb-2">

                        <div
                            class="card-header bg-light py-2 section-toggle"
                            data-target="#sec-opciones">

                            <strong>

                                <i class="bi bi-sliders me-1"></i>

                                Opciones

                            </strong>

                        </div>

                        <div class="collapse" id="sec-opciones">

                            <div class="card-body py-2">

                                <div class="row g-2 align-items-end">

                                    <div class="col-md-6">

                                        <label class="form-label mb-0">
                                            Estatus
                                        </label>

                                        <select
                                            id="edit-estatus"
                                            class="form-select form-select-sm">

                                            <option value="enviado">
                                                Enviado
                                            </option>

                                            <option value="respaldo">
                                                Respaldo
                                            </option>

                                            <option value="n/a">
                                                N/A
                                            </option>

                                            <option value="no se cotiza">
                                                No se cotiza
                                            </option>

                                        </select>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="form-check form-switch mt-4">

                                            <input
                                                class="form-check-input"
                                                type="checkbox"
                                                id="edit-reenviar">

                                            <label
                                                class="form-check-label"
                                                for="edit-reenviar">

                                                Marcar para reenviar

                                            </label>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- FOOTER -->

                <div class="modal-footer py-2">

                    <button
                        type="button"
                        class="btn btn-sm btn-secondary"
                        data-bs-dismiss="modal">

                        Cancelar

                    </button>

                    <button
                        type="submit"
                        class="btn btn-sm btn-primary">

                        <i class="bi bi-save me-1"></i>

                        Guardar cambios

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- TABULATOR JS -->
<script
  src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/js/tabulator.min.js"
  crossorigin="anonymous"
></script>


<script>
    window.cotizaciones = <?= json_encode($cotizaciones) ?>;
</script>

<script>
    const BASE_URL = '<?= BASE_URL ?>';
</script>

<script src="<?= BASE_URL ?>assets/js/especificos/cotizaciones/offcanvas.js"></script>