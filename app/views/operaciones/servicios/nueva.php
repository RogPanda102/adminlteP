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
                            Buscar adjudicación
                        </h6>

                        <small class="text-muted">
                            Carga automáticamente los datos de una adjudicación existente.
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
                            placeholder="Buscar adjudicación por REQ o Folio..."
                            autocomplete="on">

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
                                class="btn btn-outline-secondary"
                                type="button"
                                id="btnAnalista">
                                <i class="bi bi-chevron-down"></i>

                            </button>

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

                    <!-- TIPO DE SERVICIO -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Tipo de servicio
                        </label>

                        <div class="position-relative">

                            <div class="input-group">

                                <span class="input-group-text">
                                    <i class="bi bi-tags"></i>
                                </span>

                                <input
                                    type="text"
                                    name="tipo_servicio"
                                    id="tipo_servicio"
                                    class="form-control"
                                    autocomplete="off">
                                <button
                                    class="btn btn-outline-secondary"
                                    type="button"
                                    id="btn-tipo-servicio">
                                    <i class="bi bi-chevron-down"></i>
                                </button>

                                <input
                                    type="hidden"
                                    name="tipo_servicio_id"
                                    id="tipo_servicio_id">

                                <input
                                    type="hidden"
                                    name="adjudicado_id"
                                    id="adjudicado_id">

                            </div>

                            <div
                                id="resultados-tipo-servicio"
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
                            Cantidad de tiempo 
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-hourglass-split"></i>
                            </span>

                            <input
                                type="number"
                                name="tiempo_cantidad"
                                id="tiempo_cantidad"
                                class="form-control"
                                placeholder="Cantidad"
                                min="1"
                            >

                            <select
                                name="tiempo_unidad"
                                id="tiempo_unidad"
                                class="form-select"
                            >
                                <option value="">Unidad</option>
                                <option value="dias">Días</option>
                                <option value="meses">Meses</option>
                                <option value="años">Años</option>
                            </select>

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
                                id="fecha_contratacion"
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
                                id="inicio"
                                class="form-control">

                        </div>

                    </div>

                    <!-- FINALIZACIÓN -->
                    <div class="col-md-6">

                        <label class="form-label fw-semibold">
                            Finalización
                            <span class="text-muted fw-normal">
                                (calculada automáticamente)
                            </span>
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-stop-circle"></i>
                            </span>

                            <input
                                type="date"
                                name="finalizacion"
                                id="finalizacion"
                                class="form-control"
                                readonly
                                disabled>

                        </div>

                        <small class="text-muted">
                            Se calcula a partir del inicio y la duración del servicio.
                        </small>

                    </div>

                </div>

            </div>

            <!-- ========================================================= -->
            <!-- DEPENDENCIA -->
            <!-- ========================================================= -->

            <!-- <div class="border rounded-3 p-3 mb-4">

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

            </div> -->
            <!-- DEPENDENCIA -->
            <div class="col-md-12 position-relative">
                <label class="form-label fw-semibold">
                    Dependencia
                </label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-building"></i>
                    </span>
                    <input
                        type="text"
                        id="dependencia"
                        name="dependencia"
                        class="form-control"
                        autocomplete="off"
                        placeholder="Buscar dependencia...">
                    <input
                        type="hidden"
                        name="dependencia_id"
                        id="dependencia_id">
                    <button
                        class="btn btn-outline-secondary"
                        type="button"
                        id="btnDependencia">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                </div>
                <div
                            id="lista-dependencia"
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

<!-- ========================================================= -->
<!-- MODAL NUEVA DEPENDENCIA -->
<!-- ========================================================= -->

<?= modalDependencia() ?>

<!-- ========================================================= -->
<!-- MODAL NUEVA ANALISTA -->
<!-- ========================================================= -->

<?= modalAnalista() ?>

<script>
const BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/helpers/autocomplete.js"></script>
<script src="<?= BASE_URL ?>assets/js/helpers/catalogo.js"></script>
<script src="<?= BASE_URL ?>assets/js/especificos/servicios/nuevo.js"></script>