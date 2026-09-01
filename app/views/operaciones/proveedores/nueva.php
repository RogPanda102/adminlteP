<div class="card shadow-sm border-0">

    <!-- ================= HEADER ================= -->
    <div class="card-header bg-white border-bottom py-3">

        <div class="d-flex justify-content-between align-items-center">

            <div class="d-flex align-items-center">

                <div
                    class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-3 me-3"
                    style="width:48px;height:48px;">

                    <i class="bi bi-building-add fs-4"></i>

                </div>

                <div>

                    <h3 class="card-title mb-1 fw-bold">
                        Nuevo Proveedor
                    </h3>

                    <small class="text-muted">
                        Registra la información comercial y de contacto del proveedor.
                    </small>

                </div>

            </div>

        </div>

    </div>


    <!-- ================= FORMULARIO ================= -->

    <form
        action="<?= BASE_URL ?>proveedores/guardar"
        method="POST">

        <div class="card-body p-4">


            <!-- ================================================= -->
            <!-- INFORMACIÓN GENERAL -->
            <!-- ================================================= -->

            <div class="border rounded-3 p-3 mb-4 bg-light">

                <h6 class="fw-bold text-primary mb-3">

                    <i class="bi bi-info-circle me-2"></i>

                    Información General

                </h6>


                <div class="row g-3">


                    <!-- PROVEEDOR -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Proveedor

                            <span class="text-danger">*</span>

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-building"></i>

                            </span>

                            <input
                                type="text"
                                name="proveedor"
                                class="form-control"
                                maxlength="255"
                                placeholder="Nombre del proveedor"
                                required>

                        </div>

                        <div class="form-text">

                            Nombre comercial o razón social.

                        </div>

                    </div>


                    <!-- SERVICIOS -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Servicios

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-box-seam"></i>

                            </span>

                            <input
                                type="text"
                                name="servicios"
                                class="form-control"
                                maxlength="255"
                                placeholder="Servicios o productos que ofrece">

                        </div>

                    </div>


                </div>

            </div>



            <!-- ================================================= -->
            <!-- DATOS DE CONTACTO -->
            <!-- ================================================= -->

            <div class="border rounded-3 p-3 mb-4">

                <h6 class="fw-bold text-success mb-3">

                    <i class="bi bi-person-lines-fill me-2"></i>

                    Datos de Contacto

                </h6>


                <div class="row g-3">


                    <!-- CONTACTO -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Persona de contacto

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-person"></i>

                            </span>

                            <input
                                type="text"
                                name="contacto"
                                class="form-control"
                                maxlength="255"
                                placeholder="Nombre del contacto">

                        </div>

                    </div>


                    <!-- TELEFONO -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Teléfono

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-telephone"></i>

                            </span>

                            <input
                                type="text"
                                name="telefono"
                                class="form-control"
                                maxlength="50"
                                placeholder="Número telefónico">

                        </div>

                    </div>


                    <!-- EMAIL -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Correo electrónico

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-envelope"></i>

                            </span>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                maxlength="255"
                                placeholder="correo@ejemplo.com">

                        </div>

                    </div>


                    <!-- UBICACION -->

                    <div class="col-md-6">

                        <label class="form-label fw-semibold">

                            Ubicación

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-geo-alt"></i>

                            </span>

                            <input
                                type="text"
                                name="ubicacion"
                                class="form-control"
                                maxlength="255"
                                placeholder="Ciudad, estado o dirección">

                        </div>

                    </div>


                </div>

            </div>



            <!-- ================================================= -->
            <!-- INFORMACIÓN DIGITAL -->
            <!-- ================================================= -->

            <div class="border rounded-3 p-3">

                <h6 class="fw-bold text-warning mb-3">

                    <i class="bi bi-globe2 me-2"></i>

                    Información Digital

                </h6>


                <div class="row g-3">


                    <!-- ENLACE -->

                    <div class="col-md-12">

                        <label class="form-label fw-semibold">

                            Sitio web / Enlace

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">

                                <i class="bi bi-link-45deg"></i>

                            </span>

                            <input
                                type="url"
                                name="enlace"
                                class="form-control"
                                maxlength="500"
                                placeholder="https://ejemplo.com">

                        </div>

                        <div class="form-text">

                            Puedes registrar el sitio web, catálogo o página del proveedor.

                        </div>

                    </div>


                </div>

            </div>


        </div>



        <!-- ================================================= -->
        <!-- FOOTER -->
        <!-- ================================================= -->

        <div class="card-footer bg-white border-top p-3">

            <div class="d-flex justify-content-between align-items-center">


                <!-- REGRESAR -->

                <a
                    href="<?= BASE_URL ?>proveedores"
                    class="btn btn-outline-secondary">

                    <i class="bi bi-arrow-left me-1"></i>

                    Regresar

                </a>


                <!-- GUARDAR -->

                <button
                    type="submit"
                    class="btn btn-success px-4">

                    <i class="bi bi-check-circle me-1"></i>

                    Guardar proveedor

                </button>


            </div>

        </div>

    </form>

</div>