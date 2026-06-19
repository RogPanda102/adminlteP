<?php
$adjudicados = $adjudicados ?? [];
?>
<!-- TABULATOR CSS -->
<link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
    crossorigin="anonymous" />

<style>
    #tabla-adjudicados .tabulator-row {
        cursor: pointer;
    }

    #tabla-adjudicados .tabulator-row:hover {
        background-color: rgba(13, 110, 253, 0.08);
    }
</style>

<main class="app-main">
    <div class="app-content">
        <div class="container-fluid">

            <div class="card">

                <div class="card-header">
                    <h3 class="card-title">
                        Adjudicados 2026
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
                                placeholder="Buscar..." />
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <a
                            href="<?= BASE_URL ?>adjudicados/nueva"
                            class="btn btn-sm btn-success">
                            <i class="bi bi-plus-circle me-1"></i>
                            Nueva adjudicación
                        </a>

                        <button
                            id="export-csv"
                            type="button"
                            class="btn btn-sm btn-outline-success">
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

<!-- ================= OFFCANVAS DETALLE ERP ================= -->
<div class="offcanvas offcanvas-end" tabindex="-1"
    id="offcanvasDetalleAdjudicacion"
    style="width: 500px;">

    <!-- HEADER ERP -->
    <div class="offcanvas-header border-bottom flex-column align-items-start">

        <div class="d-flex justify-content-between w-100">

            <h5 class="offcanvas-title mb-0">
                Adjudicación <span id="erp-folio-title"></span>
            </h5>

            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>

        </div>

        <div class="mt-2 d-flex align-items-center gap-2">

            <!-- ESTADO -->
            <span id="erp-status" class="badge bg-secondary">
                Pendiente
            </span>

        </div>

        <!-- ACCIONES -->
        <div class="mt-2 d-flex gap-2">

            <button class="btn btn-sm btn-primary" id="btn-editar">
                ✏️ Editar
            </button>

            <button class="btn btn-sm btn-outline-dark" id="btn-historial">
                🕓 Historial
            </button>

        </div>

    </div>

    <!-- BODY -->
    <div class="offcanvas-body p-0">

        <!-- TABS -->
        <ul class="nav nav-tabs px-3 pt-2">

            <li class="nav-item">
                <button class="nav-link active"
                    data-bs-toggle="tab"
                    data-bs-target="#tab-general">
                    General
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#tab-pagos">
                    Pagos
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#tab-dependencia">
                    Dependencia
                </button>
            </li>

            <li class="nav-item">
                <button class="nav-link"
                    data-bs-toggle="tab"
                    data-bs-target="#tab-fechas">
                    Fechas
                </button>
            </li>

        </ul>

        <div class="tab-content p-3">

            <!-- GENERAL -->
            <div class="tab-pane fade show active" id="tab-general">

                <div class="mb-2"><strong>REQ:</strong> <span id="det-req"></span></div>
                <div class="mb-2"><strong>Folio:</strong> <span id="det-folio"></span></div>
                <div class="mb-2"><strong>Elaboró:</strong> <span id="det-elaboro"></span></div>
                <div class="mb-2"><strong>Partida:</strong> <span id="det-partida"></span></div>
                <div class="mb-2"><strong>Analista:</strong> <span id="det-analista"></span></div>

            </div>

            <!-- PAGOS -->
            <div class="tab-pane fade" id="tab-pagos">

                <div class="mb-2"><strong>Total:</strong> <span id="det-total"></span></div>
                <div class="mb-2"><strong>Pago:</strong> <span id="det-pago"></span></div>
                <div class="mb-2"><strong>Día pago:</strong> <span id="det-dia-pago"></span></div>

            </div>

            <!-- DEPENDENCIA -->
            <div class="tab-pane fade" id="tab-dependencia">

                <div class="mb-2"><strong>Dependencia:</strong> <span id="det-dependencia"></span></div>

            </div>

            <!-- FECHAS -->
            <div class="tab-pane fade" id="tab-fechas">

                <div class="mb-2"><strong>Elaboración:</strong> <span id="det-fecha-elaboracion"></span></div>
                <div class="mb-2"><strong>Inicio entrega:</strong> <span id="det-fecha-inicio"></span></div>
                <div class="mb-2"><strong>Fin entrega:</strong> <span id="det-fecha-fin"></span></div>

            </div>

        </div>

    </div>
</div>
<!-- ================= MODAL EDITAR ADJUDICACION ================= -->

<div class="modal fade" id="modalEditarAdjudicacion" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg">

            <div class="modal-header bg-primary text-white">
                <div>
                    <h5 class="modal-title mb-1">
                        <i class="bi bi-pencil-square me-2"></i>Editar adjudicación
                    </h5>
                    <small>Modificación de información administrativa</small>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="formEditarAdjudicacion">

                <div class="modal-body p-2">

                    <input type="hidden" id="edit-id">
                    <input type="hidden" id="edit-cotizacion_id">
                    <input type="hidden" id="edit-anio">

                    <!-- ================= GENERAL ================= -->
                    <div class="card mb-1">
                        <div class="card-header bg-light py-2 section-toggle" data-target="#sec-general">
                            <strong><i class="bi bi-info-circle me-1"></i>Información general</strong>
                        </div>
                        <div class="collapse show" id="sec-general">
                            <div class="card-body py-2">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">REQ</label>
                                        <input type="text" class="form-control form-control-sm" id="edit-req">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Folio</label>
                                        <input type="text" class="form-control form-control-sm" id="edit-folio">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Elaboró</label>
                                        <input type="text" class="form-control form-control-sm" id="edit-elaboro">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mb-0">Partida</label>
                                        <input type="text" class="form-control form-control-sm" id="edit-partida">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mb-0">Analista</label>
                                        <input type="text" class="form-control form-control-sm" id="edit-analista">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= FECHAS ================= -->
                    <div class="card mb-1">
                        <div class="card-header bg-light py-2 section-toggle" data-target="#sec-fechas">
                            <strong><i class="bi bi-calendar me-1"></i>Fechas</strong>
                        </div>
                        <div class="collapse" id="sec-fechas">
                            <div class="card-body py-2">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Elaboración</label>
                                        <input type="date" class="form-control form-control-sm" id="edit-fecha_elaboracion">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Inicio</label>
                                        <input type="date" class="form-control form-control-sm" id="edit-fecha_inicio_entrega">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Fin</label>
                                        <input type="date" class="form-control form-control-sm" id="edit-fecha_fin_entrega">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= PAGOS ================= -->
                    <div class="card mb-1">
                        <div class="card-header bg-light py-2 section-toggle" data-target="#sec-pago">
                            <strong><i class="bi bi-cash-coin me-1"></i>Pago</strong>
                        </div>
                        <div class="collapse" id="sec-pago">
                            <div class="card-body py-2">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Total</label>
                                        <input type="number" step="0.01" class="form-control form-control-sm" id="edit-total">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Día pago</label>
                                        <input type="date" class="form-control form-control-sm" id="edit-dia_pago">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Pago</label>
                                        <select class="form-select form-select-sm" id="edit-pago">
                                            <option value="pendiente">Pendiente</option>
                                            <option value="pagado">Pagado</option>
                                            <option value="cancelado">Cancelado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ================= DEPENDENCIA ================= -->
                    <div class="card mb-1">
                        <div class="card-header bg-light py-2 section-toggle" data-target="#sec-dep">
                            <strong><i class="bi bi-building me-1"></i>Dependencia</strong>
                        </div>
                        <div class="collapse" id="sec-dep">
                            <div class="card-body py-2">
                                <input type="text" class="form-control form-control-sm" id="edit-dependencia">
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="bi bi-save me-1"></i>Guardar
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
    window.adjudicados = <?= json_encode($adjudicados) ?>;
</script>
<script>
    const BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/helpers/pagoToggle.js"></script>
<script src="<?= BASE_URL ?>assets/js/especificos/adjudicados/2026.js"></script>