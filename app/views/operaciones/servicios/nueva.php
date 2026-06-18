<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Nuevo Servicio
        </h3>
    </div>

    <form action="<?= BASE_URL ?>servicios/guardar" method="POST">

        <div class="card-body">

            <div class="row">

                <!-- AÑO -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        Año
                    </label>
                    <input
                        type="number"
                        name="anio"
                        class="form-control"
                        value="2026"
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
                        class="form-control"
                    >
                </div>

                <!-- FOLIO -->
                <div class="col-md-3 mb-3">
                    <label class="form-label">
                        Folio
                    </label>
                    <input
                        type="text"
                        name="folio"
                        class="form-control"
                    >
                </div>

            </div>

            <div class="row">

                <!-- ELABORÓ -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Elaboró
                    </label>
                    <input
                        type="text"
                        name="elaboro"
                        value="<?= $_SESSION['usuario'] ?>"
                        class="form-control"
                    >
                </div>

                <!-- PARTIDA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Partida
                    </label>
                    <input
                        type="text"
                        name="partida"
                        class="form-control"
                    >
                </div>

            </div>

            <div class="row">

                <!-- ANALISTA -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Analista
                    </label>
                    <input
                        type="text"
                        name="analista"
                        class="form-control"
                    >
                </div>

                <!-- TIEMPO CONTRATACIÓN -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Tiempo de contratación
                    </label>
                    <input
                        type="text"
                        name="tiempo_contratacion"
                        class="form-control"
                    >
                </div>

            </div>

            <div class="row">

                <!-- FECHA CONTRATACIÓN -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Fecha contratación
                    </label>
                    <input
                        type="date"
                        name="fecha_contratacion"
                        class="form-control"
                    >
                </div>

                <!-- INICIO -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Inicio
                    </label>
                    <input
                        type="date"
                        name="inicio"
                        class="form-control"
                    >
                </div>

                <!-- FINALIZACIÓN -->
                <div class="col-md-4 mb-3">
                    <label class="form-label">
                        Finalización
                    </label>
                    <input
                        type="date"
                        name="finalizacion"
                        class="form-control"
                    >
                </div>

            </div>

            <div class="row">

                <!-- DEPENDENCIA -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">
                        Dependencia
                    </label>
                    <input
                        type="text"
                        name="dependencia"
                        class="form-control"
                    >
                </div>

            </div>

        </div>

        <div class="card-footer d-flex justify-content-between">

            <a
                href="<?= BASE_URL ?>servicios/2026"
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
                Guardar servicio
            </button>

        </div>

    </form>

</div>