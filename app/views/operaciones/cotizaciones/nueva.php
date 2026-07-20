<div class="card shadow-sm border-0">

    <!-- ================= HEADER ================= -->
    <div class="card-header bg-white border-bottom">

        <div class="d-flex justify-content-between align-items-center">

            <div>
                <h3 class="card-title mb-0 fw-bold">
                    <i class="bi bi-file-earmark-text text-primary me-2"></i>
                    Nueva Cotización
                </h3>

                <small class="text-muted">
                    Captura la información general de la cotización.
                </small>
            </div>

        </div>

    </div>

    <form action="<?= BASE_URL ?>cotizaciones/guardar" method="POST">

        <div class="card-body">

            <!-- ========================================= -->
            <!-- INFORMACIÓN GENERAL -->
            <!-- ========================================= -->

            <div class="border rounded-3 p-3 mb-4 bg-light">

                <h6 class="fw-bold text-primary mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    Información General
                </h6>

                <div class="row g-3">

                    <!-- FECHA -->
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Fecha
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-calendar3"></i>
                            </span>

                            <input
                                type="date"
                                name="fecha"
                                class="form-control"
                                value="<?= date('Y-m-d') ?>"
                                required>

                        </div>

                    </div>

                    <!-- AÑO -->

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Año
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-calendar-range"></i>
                            </span>

                            <input
                                type="number"
                                name="anio"
                                class="form-control"
                                value="2026"
                                required>

                        </div>

                    </div>

                    <!-- REQ -->

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            REQ
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-hash"></i>
                            </span>

                            <input
                                type="text"
                                name="req"
                                class="form-control"
                                maxlength="150"
                                required>

                        </div>

                    </div>

                    <!-- FOLIO -->

                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Folio
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-file-earmark"></i>
                            </span>

                            <input
                                type="text"
                                name="folio"
                                class="form-control"
                                maxlength="4"
                                required>

                        </div>

                    </div>

                </div>

            </div>

            <!-- ========================================= -->
            <!-- RESPONSABLES -->
            <!-- ========================================= -->

            <div class="border rounded-3 p-3 mb-4">

                <h6 class="fw-bold text-success mb-3">

                    <i class="bi bi-people me-2"></i>

                    Responsables

                </h6>

                <div class="row g-3">

                    <!-- ELABORÓ -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Elaboró
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-person"></i>
                            </span>

                            <input
                                type="text"
                                name="elaboro"
                                value="<?= $_SESSION['usuario'] ?>"
                                class="form-control"
                                maxlength="100">

                        </div>

                    </div>

                    <!-- ANALISTA -->

                    <div class="col-md-6 position-relative">

                        <label class="form-label fw-semibold">
                            Analista
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-person-badge"></i>
                            </span>

                            <input
                                type="text"
                                id="analista"
                                name="analista"
                                class="form-control"
                                autocomplete="off"
                                placeholder="Buscar analista...">

                            <button
                                class="btn btn-outline-primary"
                                type="button"
                                id="btnNuevoAnalista"
                                data-bs-toggle="tooltip"
                                data-bs-placement="top"
                                title="Crear nuevo analista">

                                <i class="bi bi-plus-lg"></i>

                            </button>

                        </div>

                        <input
                            type="hidden"
                            name="analista_id"
                            id="analista_id">

                        <div
                            id="lista-analista"
                            class="list-group position-absolute w-100 shadow rounded-3 mt-1"
                            style="
                                display:none;
                                z-index:1050;
                                max-height:260px;
                                overflow-y:auto;
                            ">
                        </div>

                    </div>

                </div>

            </div>

            <!-- ========================================= -->
            <!-- INFORMACIÓN DE LA COTIZACIÓN -->
            <!-- ========================================= -->

            <div class="border rounded-3 p-3 mb-4 bg-light">

                <h6 class="fw-bold text-warning mb-3">

                    <i class="bi bi-box-seam me-2"></i>

                    Información de la Cotización

                </h6>

                <div class="row g-3">

                    <!-- PROVEEDOR -->

                    <div class="col-md-6 position-relative">

                        <label class="form-label fw-semibold">
                            Proveedor
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-building"></i>
                            </span>

                            <input
                                type="text"
                                id="proveedor"
                                name="proveedor"
                                class="form-control"
                                autocomplete="off">

                        </div>

                        <div
                            id="lista-proveedor"
                            class="list-group position-absolute w-100 shadow"
                            style="display:none;z-index:1050;">
                        </div>

                    </div>

                    <!-- PARTIDA -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Partida
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-box"></i>
                            </span>

                            <input
                                type="text"
                                name="partida"
                                class="form-control"
                                maxlength="150">

                        </div>

                    </div>

                </div>

            </div>

            <!-- ========================================= -->
            <!-- SEGUIMIENTO -->
            <!-- ========================================= -->

            <div class="border rounded-3 p-3">

                <h6 class="fw-bold text-danger mb-3">

                    <i class="bi bi-clipboard-check me-2"></i>

                    Seguimiento

                </h6>

                <div class="row g-3">

                    <!-- ESTATUS -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Estatus
                        </label>

                        <select
                            name="estatus"
                            class="form-select">

                            <option value="enviado">Enviado</option>
                            <option value="respaldo">Respaldo</option>
                            <option value="n/a">N/A</option>
                            <option value="no se cotiza">No se cotiza</option>

                        </select>

                    </div>

                    <!-- REENVIAR -->

                    <div class="col-md-6 d-flex align-items-end">

                        <div class="form-check form-switch fs-5">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="reenviar"
                                value="1">

                            <label class="form-check-label ms-2">

                                Reenviar cotización

                            </label>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ================= FOOTER ================= -->

        <div class="card-footer bg-white border-top">

            <div class="d-flex justify-content-between">

                <a
                    href="<?= BASE_URL ?>cotizaciones/2026"
                    class="btn btn-outline-secondary">

                    <i class="bi bi-arrow-left"></i>

                    Regresar

                </a>

                <button
                    type="submit"
                    class="btn btn-success px-4">

                    <i class="bi bi-check-circle me-1"></i>

                    Guardar Cotización

                </button>

            </div>

        </div>

    </form>

</div>


<!-- ========================================================= -->
<!-- MODAL NUEVO ANALISTA -->
<!-- ========================================================= -->

<div
    class="modal fade"
    id="modalNuevoAnalista"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="bi bi-person-plus me-2"></i>
                    Nuevo Analista
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <form id="formNuevoAnalista">

                <div class="modal-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Nombre
                        </label>

                        <input
                            type="text"
                            id="nuevo_nombre"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Apellido paterno
                        </label>

                        <input
                            type="text"
                            id="nuevo_apellido_paterno"
                            class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Apellido materno
                        </label>

                        <input
                            type="text"
                            id="nuevo_apellido_materno"
                            class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            Teléfono
                        </label>

                        <input
                            type="text"
                            id="nuevo_telefono"
                            class="form-control">

                    </div>

                    <div class="mb-3">

                        <label class="form-label">
                            correo
                        </label>

                        <input
                            type="text"
                            id="nuevo_telefono"
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

                        <i class="bi bi-check-circle me-1"></i>
                        Guardar

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
    const BASE_URL = '<?= BASE_URL ?>';
</script>

<script src="<?= BASE_URL ?>assets/js/helpers/autocomplete.js"></script>
<script src="<?= BASE_URL ?>assets/js/especificos/cotizaciones/nuevo.js"></script>