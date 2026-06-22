<div class="card shadow-sm">

    <div class="card-header bg-primary text-white">

        <h3 class="card-title mb-0">
            <i class="bi bi-gear-wide-connected me-2"></i>
            Nuevo Servicio
        </h3>

    </div>


    <form action="<?= BASE_URL ?>servicios/guardar" method="POST">


        <div class="card-body">


            <!-- =========================
                 BUSCADOR PREDICTIVO REQ
            ========================== -->

            <div class="row mb-3">

                <div class="col-md-12 position-relative">


                    <label class="form-label fw-bold">
                        Buscar requisición existente
                    </label>


                    <input
                        type="text"
                        id="buscar-servicio"
                        class="form-control"
                        placeholder="Buscar por REQ o folio..."
                        autocomplete="off"
                    >


                    <div
                        id="resultados-servicio"
                        class="list-group position-absolute w-100 shadow"
                        style="
                            z-index:1000;
                            display:none;
                            max-height:250px;
                            overflow-y:auto;
                        "
                    ></div>


                    <small class="text-muted">
                        Escribe una requisición para autocompletar información.
                    </small>


                </div>


            </div>


            <hr>


            <!-- =========================
                 DATOS GENERALES
            ========================== -->


            <div class="card mb-3">


                <div class="card-header bg-light">

                    <i class="bi bi-info-circle me-1"></i>

                    Información general

                </div>


                <div class="card-body">


                    <div class="row">


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



                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                REQ
                            </label>

                            <input
                                type="text"
                                name="req"
                                id="req"
                                class="form-control"
                            >

                        </div>



                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Folio
                            </label>

                            <input
                                type="text"
                                name="folio"
                                id="folio"
                                class="form-control"
                            >

                        </div>



                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Elaboró
                            </label>

                            <input
                                type="text"
                                name="elaboro"
                                id="elaboro"
                                value="<?= $_SESSION['usuario'] ?>"
                                class="form-control"
                            >

                        </div>


                    </div>



                    <div class="row">


                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Partida
                            </label>

                            <input
                                type="text"
                                name="partida"
                                id="partida"
                                class="form-control"
                            >

                        </div>



                        <div class="col-md-6 mb-3">

                            <label class="form-label">
                                Analista
                            </label>

                            <input
                                type="text"
                                name="analista"
                                id="analista"
                                class="form-control"
                            >

                        </div>


                    </div>


                </div>


            </div>




            <!-- =========================
                 CONTRATACIÓN
            ========================== -->


            <div class="card mb-3">


                <div class="card-header bg-light">

                    <i class="bi bi-calendar-event me-1"></i>

                    Datos de contratación

                </div>


                <div class="card-body">


                    <div class="row">


                        <div class="col-md-6 mb-3">


                            <label class="form-label">
                                Tiempo contratación
                            </label>


                            <input
                                type="text"
                                name="tiempo_contratacion"
                                class="form-control"
                            >


                        </div>



                        <div class="col-md-6 mb-3">


                            <label class="form-label">
                                Fecha contratación
                            </label>


                            <input
                                type="date"
                                name="fecha_contratacion"
                                class="form-control"
                            >


                        </div>


                    </div>



                    <div class="row">


                        <div class="col-md-6 mb-3">


                            <label class="form-label">
                                Inicio
                            </label>


                            <input
                                type="date"
                                name="inicio"
                                class="form-control"
                            >


                        </div>



                        <div class="col-md-6 mb-3">


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


                </div>


            </div>




            <!-- =========================
                 DEPENDENCIA
            ========================== -->


            <div class="card">


                <div class="card-header bg-light">

                    <i class="bi bi-building me-1"></i>

                    Dependencia

                </div>


                <div class="card-body">


                    <div class="position-relative">


                        <input
                            type="text"
                            name="dependencia"
                            id="dependencia"
                            class="form-control"
                            autocomplete="off"
                        >


                        <div
                            id="resultados-dependencia"
                            class="list-group position-absolute w-100 shadow"
                            style="
                                z-index:1000;
                                display:none;
                            "
                        ></div>


                    </div>


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