<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Nuevo Adjudicado
        </h3>
    </div>

    <form action="<?= BASE_URL ?>adjudicados/guardar" method="POST">

        <!-- RELACIÓN CON COTIZACIÓN -->
        <input
            type="hidden"
            name="cotizacion_id"
            id="cotizacion_id"
        >

        <div class="card-body">

            <!-- BUSCADOR DE COTIZACIÓN -->
            <div class="row">

                <div class="col-md-12 mb-4 position-relative">

                    <label class="form-label fw-bold">
                        Buscar cotización existente
                    </label>

                    <input
                        type="text"
                        id="buscar-cotizacion"
                        class="form-control"
                        placeholder="Buscar por REQ o Número..."
                        autocomplete="off"
                    >

                    <div
                        id="resultados-cotizacion"
                        class="list-group position-absolute w-100 shadow-sm"
                        style="
                            z-index:1000;
                            display:none;
                            max-height:250px;
                            overflow-y:auto;
                        "
                    ></div>

                    <small class="text-muted">
                        Escribe una REQ o Número para autocompletar los datos de una cotización existente.
                    </small>

                </div>

            </div>

            <hr>

            <div class="row">

                <!-- FECHA ELABORACIÓN -->
                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Fecha elaboración
                    </label>

                    <input
                        type="date"
                        name="fecha_elaboracion"
                        id="fecha_elaboracion"
                        class="form-control"
                        value="<?= date('Y-m-d') ?>"
                        required
                    >

                </div>

                <!-- AÑO -->
                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Año
                    </label>

                    <input
                        type="number"
                        name="anio"
                        id="anio"
                        class="form-control"
                        value="<?= date('Y') ?>"
                        required
                    >

                </div>

                <!-- REQ -->
                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        REQ
                    </label>

                    <input
                        type="text"
                        name="req"
                        id="req"
                        class="form-control"
                        maxlength="150"
                        required
                    >

                </div>

                <!-- NÚMERO -->
                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Número
                    </label>

                    <input
                        type="text"
                        name="numero"
                        id="numero"
                        class="form-control"
                        maxlength="50"
                    >

                </div>

            </div>

            <div class="row">

                <!-- ELABORÓ -->
                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Elaboró
                    </label>

                    <input
                        type="text"
                        name="elaboro"
                        id="elaboro"
                        value="<?= $_SESSION['usuario'] ?>"
                        class="form-control"
                        maxlength="100"
                    >

                </div>

                <!-- PARTIDA -->
                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Partida
                    </label>

                    <input
                        type="text"
                        name="partida"
                        id="partida"
                        class="form-control"
                        maxlength="150"
                    >

                </div>

                <!-- ANALISTA -->
                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Analista
                    </label>

                    <input
                        type="text"
                        name="analista"
                        id="analista"
                        class="form-control"
                        maxlength="150"
                    >

                </div>

            </div>

            <div class="row">

                <!-- FECHA INICIO ENTREGA -->
                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Fecha inicio entrega
                    </label>

                    <input
                        type="date"
                        name="fecha_inicio_entrega"
                        id="fecha_inicio_entrega"
                        class="form-control"
                    >

                </div>

                <!-- FECHA FIN ENTREGA -->
                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Fecha fin entrega
                    </label>

                    <input
                        type="date"
                        name="fecha_fin_entrega"
                        id="fecha_fin_entrega"
                        class="form-control"
                    >

                </div>

                <!-- TOTAL -->
                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Total
                    </label>

                    <input
                        type="number"
                        name="total"
                        id="total"
                        class="form-control"
                        step="0.01"
                        min="0"
                    >

                </div>

                <!-- DÍA DE PAGO -->
                <div class="col-md-3 mb-3">

                    <label class="form-label">
                        Día de pago
                    </label>

                    <input
                        type="date"
                        name="dia_pago"
                        id="dia_pago"
                        class="form-control"
                    >

                </div>

            </div>

            <div class="row">

                <!-- PAGO -->
                <div class="col-md-4 mb-3">

                    <label class="form-label">
                        Estatus de pago
                    </label>

                    <select
                        name="pago"
                        id="pago"
                        class="form-select"
                    >
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

                <!-- DEPENDENCIA -->
                <div class="col-md-8 mb-3 position-relative">

                    <label class="form-label">
                        Dependencia
                    </label>

                    <input
                        type="text"
                        name="dependencia"
                        id="dependencia"
                        class="form-control"
                        maxlength="150"
                        autocomplete="off"
                    >

                    <!-- FUTURO AUTOCOMPLETE -->
                    <div
                        id="resultados-dependencia"
                        class="list-group position-absolute w-100 shadow-sm"
                        style="
                            z-index:1000;
                            display:none;
                            max-height:250px;
                            overflow-y:auto;
                        "
                    ></div>

                </div>

            </div>

        </div>

        <div class="card-footer d-flex justify-content-between">

            <a
                href="<?= BASE_URL ?>adjudicados/2026"
                class="btn btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Regresar
            </a>

            <button
                type="submit"
                class="btn btn-success"
            >
                <i class="bi bi-save"></i>
                Guardar adjudicado
            </button>

        </div>

    </form>

</div>

<script>
const BASE_URL = '<?= BASE_URL ?>';
</script>

<script src="<?= BASE_URL ?>assets/js/helpers/autocomplete.js"></script>
<script src="<?= BASE_URL ?>assets/js/especificos/adjudicados/nuevo.js"></script>