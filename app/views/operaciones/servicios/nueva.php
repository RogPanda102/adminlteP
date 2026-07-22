<div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
        <h3 class="card-title mb-0">
            <i class="bi bi-gear-wide-connected me-2"></i>
            Nuevo Servicio
        </h3>
    </div>
    <form action="<?= BASE_URL ?>servicios/guardar" method="POST">
        <div class="card-body">
            <!-- ========================================================= -->
            <!-- BUSCAR REQUISICIÓN -->
            <!-- ========================================================= -->

            <div class="border rounded-3 p-3 mb-4 bg-light">

                <div class="d-flex align-items-center mb-3">

                    <i class="bi bi-search text-primary fs-4 me-2"></i>

                    <div>

                        <h6 class="fw-bold text-primary mb-0">
                            Buscar requisición
                        </h6>

                        <small class="text-muted">
                            Carga automáticamente una requisición existente.
                        </small>

                    </div>

                </div>

                <div class="position-relative">

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>

                        <input
                            type="text"
                            id="buscar-servicio"
                            class="form-control"
                            placeholder="Buscar por REQ o Folio..."
                            autocomplete="off">

                    </div>

                    <div
                        id="resultados-servicio"
                        class="list-group position-absolute w-100 shadow rounded-3 mt-1"
                        style="
                            z-index:1050;
                            display:none;
                            max-height:250px;
                            overflow-y:auto;
                        ">
                    </div>

                </div>

            </div>

            <hr>

            <!-- ========================================================= -->
            <!-- INFORMACIÓN GENERAL -->
            <!-- ========================================================= -->

            <div class="border rounded-3 p-3 mb-4 bg-light">

                <h6 class="fw-bold text-primary mb-3">

                    <i class="bi bi-info-circle me-2"></i>

                    Información General

                </h6>

                <div class="row g-3">

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
                                id="anio"
                                class="form-control"
                                value="<?= date('Y') ?>"
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
                                id="req"
                                class="form-control">

                        </div>

                    </div>

                    <!-- FOLIO -->
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Folio
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-file-earmark-text"></i>
                            </span>

                            <input
                                type="text"
                                name="folio"
                                id="folio"
                                class="form-control">

                        </div>

                    </div>

                    <!-- ELABORÓ -->
                    <div class="col-md-3">

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
                                id="elaboro"
                                value="<?= $_SESSION['usuario'] ?>"
                                class="form-control">

                        </div>

                    </div>

                    <!-- PARTIDA -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Partida
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-box-seam"></i>
                            </span>

                            <input
                                type="text"
                                name="partida"
                                id="partida"
                                class="form-control">

                        </div>

                    </div>

                    <!-- ANALISTA -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Analista
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-person-badge"></i>
                            </span>

                            <input
                                type="text"
                                name="analista"
                                id="analista"
                                class="form-control">

                        </div>

                    </div>

                </div>

            </div>

            <!-- ========================================================= -->
            <!-- DATOS DE CONTRATACIÓN -->
            <!-- ========================================================= -->

            <div class="border rounded-3 p-3 mb-4 bg-light">

                <h6 class="fw-bold text-warning mb-3">

                    <i class="bi bi-calendar-event me-2"></i>

                    Datos de Contratación

                </h6>

                <div class="row g-3">

                    <!-- TIEMPO CONTRATACIÓN -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Tiempo de contratación
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-hourglass-split"></i>
                            </span>

                            <input
                                type="text"
                                name="tiempo_contratacion"
                                class="form-control">

                        </div>

                    </div>

                    <!-- FECHA CONTRATACIÓN -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Fecha de contratación
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-calendar-check"></i>
                            </span>

                            <input
                                type="date"
                                name="fecha_contratacion"
                                class="form-control">

                        </div>

                    </div>

                    <!-- INICIO -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Inicio
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-play-circle"></i>
                            </span>

                            <input
                                type="date"
                                name="inicio"
                                class="form-control">

                        </div>

                    </div>

                    <!-- FINALIZACIÓN -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Finalización
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-stop-circle"></i>
                            </span>

                            <input
                                type="date"
                                name="finalizacion"
                                class="form-control">

                        </div>

                    </div>

                </div>

            </div>

            <!-- ========================================================= -->
            <!-- DEPENDENCIA -->
            <!-- ========================================================= -->

            <div class="border rounded-3 p-3 mb-4">

                <h6 class="fw-bold text-danger mb-3">

                    <i class="bi bi-building me-2"></i>

                    Dependencia

                </h6>

                <div class="position-relative">

                    <div class="input-group">

                        <span class="input-group-text">
                            <i class="bi bi-building"></i>
                        </span>

                        <input
                            type="text"
                            name="dependencia"
                            id="dependencia"
                            class="form-control"
                            autocomplete="off"
                            placeholder="Buscar dependencia...">

                    </div>

                    <div
                        id="resultados-dependencia"
                        class="list-group position-absolute w-100 shadow rounded-3 mt-1"
                        style="
                            z-index:1050;
                            display:none;
                            max-height:250px;
                            overflow-y:auto;
                        ">
                    </div>

                </div>

            </div>
        </div>
        <!-- =========================
                    FOOTER
        ========================== -->
        <div class="card-footer d-flex justify-content-between">
            <a
                href="<?= BASE_URL ?>servicios/2026"
                class="btn btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Regresar
            </a>
            <button
                class="btn btn-success"
            >
                <i class="bi bi-save"></i>
                Guardar servicio
            </button>
        </div>
    </form>
</div>
<script>
const BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/helpers/autocomplete.js"></script>
<script src="<?= BASE_URL ?>assets/js/especificos/servicios/nuevo.js"></script>