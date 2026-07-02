<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            Nueva Cotización
        </h3>
    </div>

    <form action="<?= BASE_URL ?>cotizaciones/guardar" method="POST">

        <div class="card-body">

            <div class="row">

                <!-- FECHA -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha" class="form-control"
                        value="<?= date('Y-m-d') ?>" required>
                </div>

                <!-- AÑO -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Año</label>
                    <input type="number" name="anio" class="form-control"
                        value="2026" required>
                </div>

                <!-- REQ -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">REQ</label>
                    <input type="text" name="req" class="form-control" maxlength="150" required>
                </div>

                <!-- FOLIO -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">Folio</label>
                    <input type="text" name="folio" class="form-control" maxlength="4" required>
                </div>

            </div>

            <div class="row">

                <!-- ELABORÓ -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Elaboró</label>
                    <input type="text" name="elaboro"
                        value="<?= $_SESSION['usuario'] ?>"
                        class="form-control" maxlength="100">
                </div>

                <!-- PARTIDA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">Partida</label>
                    <input type="text" name="partida" class="form-control" maxlength="150">
                </div>

            </div>

            <div class="row">

                <!-- PROVEEDOR (AUTOCOMPLETE) -->
                <div class="col-md-6 mb-3 position-relative">
                    <label class="form-label">Proveedor</label>

                    <input
                        type="text"
                        name="proveedor"
                        id="proveedor"
                        class="form-control"
                        maxlength="150"
                        autocomplete="off"
                    >

                    <div id="lista-proveedor"
                        class="list-group position-absolute w-100"
                        style="z-index: 1000; display:none;">
                    </div>
                </div>

                <!-- ANALISTA (AUTOCOMPLETE) -->
                <div class="col-md-6 mb-3 position-relative">
                    <label class="form-label">Analista</label>

                    <input
                        type="text"
                        name="analista"
                        id="analista"
                        class="form-control"
                        maxlength="150"
                        autocomplete="off"
                    >

                    <div id="lista-analista"
                        class="list-group position-absolute w-100"
                        style="z-index: 1000; display:none;">
                    </div>
                </div>

            </div>

            <div class="row">

                <!-- ESTATUS -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">Estatus</label>

                    <select name="estatus" class="form-select">
                        <option value="enviado">Enviado</option>
                        <option value="respaldo">Respaldo</option>
                        <option value="n/a">N/A</option>
                        <option value="no se cotiza">No se cotiza</option>
                    </select>
                </div>

                <!-- REENVIAR -->
                <div class="col-md-4 mb-3">
                    <label class="form-label d-block">Reenviar</label>

                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="reenviar" value="1">
                        <label class="form-check-label">Sí</label>
                    </div>
                </div>

            </div>

        </div>

        <div class="card-footer d-flex justify-content-between">

            <a href="<?= BASE_URL ?>cotizaciones/2026"
                class="btn btn-secondary">
                Regresar
            </a>

            <button type="submit" class="btn btn-success">
                Guardar cotización
            </button>

        </div>

    </form>
</div>

<script>
    const BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/helpers/autocomplete.js"></script>
<script src="<?= BASE_URL ?>assets/js/especificos/cotizaciones/nuevo.js"></script>