<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Nuevo Adjudicado
        </h3>
    </div>

    <form action="<?= BASE_URL ?>adjudicados/guardar" method="POST">

        <!-- RELACIÓN CON COTIZACIÓN -->
        <input type="hidden" name="cotizacion_id" id="cotizacion_id">
        <div class="card-body">

            <!-- ========================================================= -->
            <!-- BUSCAR COTIZACIÓN -->
            <!-- ========================================================= -->

            <div class="border rounded-3 p-3 mb-4 bg-light">

                <div class="d-flex align-items-center mb-3">

                    <i class="bi bi-search text-primary fs-4 me-2"></i>

                    <div>

                        <h6 class="fw-bold text-primary mb-0">
                            Buscar Cotización
                        </h6>

                        <small class="text-muted">
                            Carga automáticamente una cotización existente.
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
                            id="buscar-cotizacion"
                            class="form-control"
                            placeholder="Buscar por REQ o Folio..."
                            autocomplete="off">

                    </div>

                    <div
                        id="resultados-cotizacion"
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

                    <!-- FECHA ELABORACIÓN -->
                    <div class="col-md-3">

                        <label class="form-label fw-semibold">
                            Fecha elaboración
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-calendar3"></i>
                            </span>

                            <input
                                type="date"
                                name="fecha_elaboracion"
                                id="fecha_elaboracion"
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
                                <i class="bi bi-file-earmark-text"></i>
                            </span>

                            <input
                                type="text"
                                name="folio"
                                id="folio"
                                class="form-control"
                                maxlength="4">

                        </div>

                    </div>

                </div>

            </div>

            <!-- ========================================================= -->
            <!-- RESPONSABLES -->
            <!-- ========================================================= -->

            <div class="border rounded-3 p-3 mb-4">

                <h6 class="fw-bold text-success mb-3">
                    <i class="bi bi-people me-2"></i>
                    Responsables
                </h6>

                <div class="row g-3">

                    <!-- ELABORÓ -->
                    <div class="col-md-4">

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
                                class="form-control"
                                maxlength="100">

                        </div>

                    </div>

                    <!-- PARTIDA -->
                    <div class="col-md-4">

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
                                class="form-control"
                                maxlength="150">

                        </div>

                    </div>

                    <!-- ANALISTA -->
                    <div class="col-md-4 position-relative">

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
                                class="form-control"
                                maxlength="150"
                                autocomplete="off">

                        </div>

                        <input
                            type="hidden"
                            name="analista_id"
                            id="analista_id">

                        <div
                            id="lista-analista"
                            class="list-group position-absolute w-100 shadow"
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

            <!-- ========================================================= -->
            <!-- FECHAS E IMPORTE -->
            <!-- ========================================================= -->

            <div class="border rounded-3 p-3 mb-4 bg-light">

                <h6 class="fw-bold text-warning mb-3">
                    <i class="bi bi-calendar-check me-2"></i>
                    Fechas e Importe
                </h6>

                <div class="row g-3">

                    <!-- FECHA INICIO ENTREGA -->
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Fecha inicio entrega
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-calendar-event"></i>
                            </span>

                            <input
                                type="date"
                                name="fecha_inicio_entrega"
                                id="fecha_inicio_entrega"
                                class="form-control">

                        </div>

                    </div>
                    
                    <!-- FECHA FIN ENTREGA -->
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Fecha fin entrega
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-calendar2-check"></i>
                            </span>

                            <input
                                type="date"
                                name="fecha_fin_entrega"
                                id="fecha_fin_entrega"
                                class="form-control">

                        </div>

                    </div>

                    <!-- TOTAL -->
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Total adjudicado
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-currency-dollar"></i>
                            </span>

                            <input
                                type="number"
                                name="total"
                                id="total"
                                class="form-control"
                                step="0.01"
                                min="0"
                                placeholder="0.00">

                        </div>

                    </div>

                    <!-- DÍA DE PAGO -->
                    <div class="col-lg-3 col-md-6">

                        <label class="form-label fw-semibold">
                            Día de pago
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-credit-card-2-front"></i>
                            </span>

                            <input
                                type="date"
                                name="dia_pago"
                                id="dia_pago"
                                class="form-control">

                        </div>

                    </div>

                </div>

            </div>

            <!-- ========================================================= -->
            <!-- PAGO Y DEPENDENCIA -->
            <!-- ========================================================= -->

            <div class="border rounded-3 p-3 mb-4">

                <h6 class="fw-bold text-danger mb-3">
                    <i class="bi bi-wallet2 me-2"></i>
                    Pago y Dependencia
                </h6>

                <div class="row g-3">

                    <!-- ESTATUS DE PAGO -->
                    <div class="col-lg-4">

                        <label class="form-label fw-semibold">
                            Estatus de pago
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-cash-stack"></i>
                            </span>

                            <select
                                name="pago"
                                id="pago"
                                class="form-select">

                                <option value="pendiente">
                                    Pendiente
                                </option>

                                <option value="pagado">
                                    Pagado
                                </option>

                                <option value="cancelado">
                                    Cancelado
                                </option>

                            </select>

                        </div>

                    </div>

                    <!-- DEPENDENCIA -->
                    <div class="col-lg-8 position-relative">

                        <label class="form-label fw-semibold">
                            Dependencia
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                <i class="bi bi-building"></i>
                            </span>

                            <input
                                type="text"
                                name="dependencia"
                                id="dependencia"
                                class="form-control"
                                maxlength="150"
                                autocomplete="off"
                                placeholder="Buscar dependencia...">

                        </div>

                        <div
                            id="lista-dependencia"
                            class="list-group position-absolute w-100 shadow"
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

        <!------------------------INICIO FOOTER------------------------->
        <div class="card-footer d-flex justify-content-between">

            <a
                href="<?= BASE_URL ?>adjudicados/2026"
                class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i>
                Regresar
            </a>

            <button
                type="submit"
                class="btn btn-success">
                <i class="bi bi-save"></i>
                Guardar adjudicado
            </button>

        </div>
        <!------------------------FIN FOOTER------------------------->

    </form>

</div>

<script>
    const BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/helpers/pagoToggle.js"></script>
<script src="<?= BASE_URL ?>assets/js/helpers/autocomplete.js"></script>
<script src="<?= BASE_URL ?>assets/js/especificos/adjudicados/nuevo.js"></script>