<?php
    $adjudicados = $adjudicados ?? [];
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
                                placeholder="Buscar..."
                            />
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-3">

                        <a
                            href="<?= BASE_URL ?>adjudicados/nueva"
                            class="btn btn-sm btn-success"
                        >
                            <i class="bi bi-plus-circle me-1"></i>
                            Nueva adjudicación
                        </a>

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
<!-- MODAL PARA EDITAR -->
<div class="modal fade" id="modalEditarAdjudicacion" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Editar adjudicación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="formEditarAdjudicacion">

                <div class="modal-body">

                    <input type="hidden" id="edit-id">

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>REQ</label>
                            <input type="text" class="form-control" id="edit-req">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Folio</label>
                            <input type="text" class="form-control" id="edit-folio">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Elaboró</label>
                            <input type="text" class="form-control" id="edit-elaboro">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Partida</label>
                            <input type="text" class="form-control" id="edit-partida">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Analista</label>
                            <input type="text" class="form-control" id="edit-analista">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Total</label>
                            <input type="number" class="form-control" id="edit-total" step="0.01" min="0">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Pago</label>
                            <select class="form-control" id="edit-pago">
                                <option value="pendiente">Pendiente</option>
                                <option value="pagado">Pagado</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Dependencia</label>
                            <input type="text" class="form-control" id="edit-dependencia">
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancelar
                    </button>

                    <button type="submit" class="btn btn-primary">
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
    window.adjudicados = <?= json_encode($adjudicados) ?>;
</script>
<script>
    const BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/especificos/adjudicados/2026.js"></script>