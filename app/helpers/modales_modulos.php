<?php

    function modalAnalista()
    {
        return '
        <!-- ========================================================= -->
        <!-- MODAL NUEVO ANALISTA -->
        <!-- ========================================================= -->

        <div
            class="modal fade"
            id="modalNuevoAnalista"
            tabindex="-1"
            aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-lg">

                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    <!-- ================= HEADER ================= -->

                    <div class="modal-header border-0 bg-primary text-white px-4 py-3">

                        <div class="d-flex align-items-center">

                            <div class="bg-white bg-opacity-25 rounded-3 p-2 me-3">
                                <i class="bi bi-person-plus fs-4"></i>
                            </div>

                            <div>

                                <h5 class="modal-title fw-bold mb-0">
                                    Nuevo Analista
                                </h5>

                                <small class="opacity-75">
                                    Registra la información del analista
                                </small>

                            </div>

                        </div>

                        <button
                            type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                        </button>

                    </div>

                    <!-- ================= FORMULARIO ================= -->

                    <form id="formNuevoAnalista">

                        <div class="modal-body p-4">

                            <!-- DATOS PERSONALES -->

                            <div class="mb-4">

                                <div class="d-flex align-items-center mb-3">

                                    <div class="text-primary me-2">
                                        <i class="bi bi-person-vcard fs-5"></i>
                                    </div>

                                    <div>
                                        <h6 class="fw-bold mb-0">
                                            Datos personales
                                        </h6>

                                        <small class="text-muted">
                                            Información básica del analista
                                        </small>
                                    </div>

                                </div>

                                <div class="row g-3">

                                    <!-- NOMBRE -->

                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Nombre
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-person text-primary"></i>
                                            </span>

                                            <input
                                                type="text"
                                                id="nuevo_nombre"
                                                class="form-control border-start-0"
                                                placeholder="Nombre"
                                                required>

                                        </div>

                                    </div>

                                    <!-- APELLIDO PATERNO -->

                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Apellido paterno
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-person text-primary"></i>
                                            </span>

                                            <input
                                                type="text"
                                                id="nuevo_apellido_paterno"
                                                class="form-control border-start-0"
                                                placeholder="Apellido paterno">

                                        </div>

                                    </div>

                                    <!-- APELLIDO MATERNO -->

                                    <div class="col-md-4">

                                        <label class="form-label fw-semibold">
                                            Apellido materno
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-person text-primary"></i>
                                            </span>

                                            <input
                                                type="text"
                                                id="nuevo_apellido_materno"
                                                class="form-control border-start-0"
                                                placeholder="Apellido materno">

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <hr class="my-4">

                            <!-- DATOS DE CONTACTO -->

                            <div>

                                <div class="d-flex align-items-center mb-3">

                                    <div class="text-primary me-2">
                                        <i class="bi bi-telephone fs-5"></i>
                                    </div>

                                    <div>
                                        <h6 class="fw-bold mb-0">
                                            Datos de contacto
                                        </h6>

                                        <small class="text-muted">
                                            Medios de contacto del analista
                                        </small>
                                    </div>

                                </div>

                                <div class="row g-3">

                                    <!-- TELEFONO -->

                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            Teléfono
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-telephone text-primary"></i>
                                            </span>

                                            <input
                                                type="tel"
                                                id="nuevo_telefono"
                                                class="form-control border-start-0"
                                                placeholder="Número telefónico">

                                        </div>

                                    </div>

                                    <!-- CORREO -->

                                    <div class="col-md-6">

                                        <label class="form-label fw-semibold">
                                            Correo electrónico
                                        </label>

                                        <div class="input-group">

                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="bi bi-envelope text-primary"></i>
                                            </span>

                                            <input
                                                type="email"
                                                id="nuevo_correo"
                                                class="form-control border-start-0"
                                                placeholder="correo@ejemplo.com">

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- ================= FOOTER ================= -->

                        <div class="modal-footer bg-light border-top px-4 py-3">

                            <button
                                type="button"
                                class="btn btn-outline-secondary"
                                data-bs-dismiss="modal">

                                <i class="bi bi-x-lg me-1"></i>
                                Cancelar

                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary px-4">

                                <i class="bi bi-check-circle me-1"></i>
                                Guardar analista

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
        ';
    }

    function modalProveedor()
    {
        return 
        '
            <!-- ========================================================= -->
            <!-- MODAL NUEVO PROVEEDOR -->
            <!-- ========================================================= -->

            <div
                class="modal fade"
                id="modalNuevoProveedor"
                tabindex="-1"
                aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered modal-lg">

                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                        <!-- ================= HEADER ================= -->

                        <div class="modal-header bg-light border-0 px-4 py-3">

                            <div class="d-flex align-items-center">

                                <div class="rounded-3 bg-primary bg-opacity-10 text-primary
                                            d-flex align-items-center justify-content-center me-3"
                                    style="width:48px;height:48px;">

                                    <i class="bi bi-building-add fs-4"></i>

                                </div>

                                <div>

                                    <h5 class="modal-title fw-bold mb-1">
                                        Nuevo Proveedor
                                    </h5>

                                    <small class="text-muted">
                                        Registra la información del proveedor.
                                    </small>

                                </div>

                            </div>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>

                        </div>


                        <!-- ================= FORMULARIO ================= -->

                        <form id="formNuevoProveedor">

                            <div class="modal-body px-4 py-4">

                                <!-- INFORMACIÓN PRINCIPAL -->

                                <div class="mb-4">

                                    <div class="d-flex align-items-center mb-3">

                                        <i class="bi bi-info-circle text-primary me-2"></i>

                                        <h6 class="fw-bold mb-0">
                                            Información del proveedor
                                        </h6>

                                    </div>

                                    <div class="row g-3">

                                        <!-- PROVEEDOR -->

                                        <div class="col-md-12">

                                            <label class="form-label fw-semibold">
                                                Proveedor
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-building text-muted"></i>
                                                </span>

                                                <input
                                                    type="text"
                                                    id="nuevo_proveedor"
                                                    class="form-control border-start-0"
                                                    placeholder="Nombre del proveedor"
                                                    required>

                                            </div>

                                        </div>


                                        <!-- SERVICIOS -->

                                        <div class="col-md-12">

                                            <label class="form-label fw-semibold">
                                                Servicios
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-box-seam text-muted"></i>
                                                </span>

                                                <input
                                                    type="text"
                                                    id="nuevo_servicios"
                                                    class="form-control border-start-0"
                                                    placeholder="Servicios que ofrece">

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                <!-- DATOS DE CONTACTO -->

                                <div class="mb-2">

                                    <div class="d-flex align-items-center mb-3">

                                        <i class="bi bi-person-lines-fill text-primary me-2"></i>

                                        <h6 class="fw-bold mb-0">
                                            Datos de contacto
                                        </h6>

                                    </div>

                                    <div class="row g-3">

                                        <!-- UBICACIÓN -->

                                        <div class="col-md-6">

                                            <label class="form-label fw-semibold">
                                                Ubicación
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-geo-alt text-muted"></i>
                                                </span>

                                                <input
                                                    type="text"
                                                    id="nuevo_ubicacion"
                                                    class="form-control border-start-0"
                                                    placeholder="Ciudad o dirección">

                                            </div>

                                        </div>


                                        <!-- CONTACTO -->

                                        <div class="col-md-6">

                                            <label class="form-label fw-semibold">
                                                Contacto
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-person text-muted"></i>
                                                </span>

                                                <input
                                                    type="text"
                                                    id="nuevo_contacto"
                                                    class="form-control border-start-0"
                                                    placeholder="Nombre del contacto">

                                            </div>

                                        </div>


                                        <!-- TELÉFONO -->

                                        <div class="col-md-6">

                                            <label class="form-label fw-semibold">
                                                Teléfono
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-telephone text-muted"></i>
                                                </span>

                                                <input
                                                    type="text"
                                                    id="nuevo_telefono_proveedor"
                                                    class="form-control border-start-0"
                                                    placeholder="Número telefónico">

                                            </div>

                                        </div>


                                        <!-- EMAIL -->

                                        <div class="col-md-6">

                                            <label class="form-label fw-semibold">
                                                Email
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-envelope text-muted"></i>
                                                </span>

                                                <input
                                                    type="email"
                                                    id="nuevo_email"
                                                    class="form-control border-start-0"
                                                    placeholder="correo@ejemplo.com">

                                            </div>

                                        </div>


                                        <!-- ENLACE -->

                                        <div class="col-md-12">

                                            <label class="form-label fw-semibold">
                                                Enlace
                                            </label>

                                            <div class="input-group">

                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="bi bi-link-45deg text-muted"></i>
                                                </span>

                                                <input
                                                    type="text"
                                                    id="nuevo_enlace"
                                                    class="form-control border-start-0"
                                                    placeholder="Sitio web o enlace">

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            <!-- ================= FOOTER ================= -->

                            <div class="modal-footer bg-light border-0 px-4 py-3">

                                <button
                                    type="button"
                                    class="btn btn-light border px-4"
                                    data-bs-dismiss="modal">

                                    <i class="bi bi-x-lg me-1"></i>
                                    Cancelar

                                </button>

                                <button
                                    type="submit"
                                    class="btn btn-primary px-4">

                                    <i class="bi bi-check-circle me-1"></i>
                                    Guardar proveedor

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>
        ';
    }