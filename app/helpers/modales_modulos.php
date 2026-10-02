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

    function modalDependencia()
    {
        return '
        <!-- ========================================================= -->
        <!-- MODAL NUEVA DEPENDENCIA -->
        <!-- ========================================================= -->

        <div
            class="modal fade"
            id="modalNuevaDependencia"
            tabindex="-1"
            aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content border-0 shadow-lg">

                    <!-- HEADER -->
                    <div class="modal-header bg-primary text-white">

                        <div class="d-flex align-items-center">

                            <div
                                class="d-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-3 me-3"
                                style="width:42px;height:42px;">

                                <i class="bi bi-building-add fs-4"></i>

                            </div>

                            <div>

                                <h5 class="modal-title fw-bold mb-0">
                                    Nueva Dependencia
                                </h5>

                                <small class="opacity-75">
                                    Registrar una nueva dependencia
                                </small>

                            </div>

                        </div>

                        <button
                            type="button"
                            class="btn-close btn-close-white"
                            data-bs-dismiss="modal">
                        </button>

                    </div>

                    <!-- FORMULARIO -->
                    <form id="formNuevaDependencia">

                        <div class="modal-body p-4">

                            <!-- NOMBRE -->
                            <div class="mb-3">

                                <label
                                    for="nuevo_dependencia"
                                    class="form-label fw-semibold">

                                    Nombre de la dependencia

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-building text-primary"></i>
                                    </span>

                                    <input
                                        type="text"
                                        id="nuevo_dependencia"
                                        name="nombre"
                                        class="form-control"
                                        placeholder="Ej. Secretaría de Finanzas"
                                        maxlength="255"
                                        required>

                                </div>

                            </div>

                            <!-- DESCRIPCIÓN -->
                            <div class="mb-3">

                                <label
                                    for="nuevo_descripcion_dependencia"
                                    class="form-label fw-semibold">

                                    Descripción

                                </label>

                                <textarea
                                    id="nuevo_descripcion_dependencia"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Descripción de la dependencia..."
                                    maxlength="1000"></textarea>

                            </div>

                            <!-- UBICACIÓN -->
                            <div class="mb-2">

                                <label
                                    for="nuevo_ubicacion_dependencia"
                                    class="form-label fw-semibold">

                                    Ubicación

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text bg-light">
                                        <i class="bi bi-geo-alt text-primary"></i>
                                    </span>

                                    <input
                                        type="text"
                                        id="nuevo_ubicacion_dependencia"
                                        class="form-control"
                                        placeholder="Ej. Tlaxcala, Tlax."
                                        maxlength="255">

                                </div>

                            </div>

                        </div>

                        <!-- FOOTER -->
                        <div class="modal-footer bg-light border-top">

                            <button
                                type="button"
                                class="btn btn-light border"
                                data-bs-dismiss="modal">

                                <i class="bi bi-x-circle me-1"></i>
                                Cancelar

                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary px-4">

                                <i class="bi bi-check-circle me-1"></i>
                                Guardar dependencia

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>
        ';
    }

    function documentos($modulo, $registro_id)
    {
        return '

        <!-- ========================================================= -->
        <!-- PANEL DOCUMENTOS -->
        <!-- ========================================================= -->

        <div
            id="erp-panel-documentos"
            class="p-3"
            style="
                width:50%;
                flex:0 0 50%;
            ">

            <!-- ================= VOLVER ================= -->

            <div class="mb-3">

                <button
                    type="button"
                    class="btn btn-sm btn-outline-secondary"
                    id="btn-volver-cotizacion">

                    <i class="bi bi-arrow-left me-1"></i>

                    Volver a cotización

                </button>

            </div>


            <!-- ================= ENCABEZADO ================= -->

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="fw-semibold fs-6">

                                <i class="bi bi-paperclip text-primary me-1"></i>

                                Documentos

                            </div>

                            <div class="text-muted small mt-1">

                                Archivos asociados a este registro

                            </div>

                        </div>

                        <button
                            type="button"
                            class="btn btn-sm btn-primary"
                            id="btn-subir-documento">

                            <i class="bi bi-cloud-arrow-up me-1"></i>

                            Subir

                        </button>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- FORMULARIO DE CARGA -->
            <!-- ================================================= -->

            <div
                id="panel-carga-documento"
                class="card border-0 shadow-sm mb-3 d-none">

                <div class="card-header bg-white border-bottom">

                    <div class="fw-semibold">

                        <i class="bi bi-cloud-arrow-up text-primary me-1"></i>

                        Subir documento

                    </div>

                    <div class="text-muted small mt-1">

                        Selecciona el tipo de documento y el archivo.

                    </div>

                </div>


                <div class="card-body">

                    <!-- ================= TIPO ================= -->

                    <div class="mb-3">

                        <label
                            for="documento-tipo"
                            class="form-label fw-semibold">

                            Tipo de documento

                        </label>

                        <select
                            id="documento-tipo"
                            class="form-select">

                            <option value="">

                                Seleccionar...

                            </option>

                            <option value="solicitud">

                                Solicitud recibida

                            </option>

                            <option value="cotizacion">

                                Cotización enviada

                            </option>

                            <option value="evidencia">

                                Evidencia

                            </option>

                            <option value="factura">

                                Factura

                            </option>

                            <option value="entrega">

                                Entrega

                            </option>

                            <option value="otro">

                                Otros documentos

                            </option>

                        </select>

                    </div>


                    <!-- ================= ARCHIVO ================= -->

                    <div class="mb-3">

                        <label
                            for="documento-archivo"
                            class="form-label fw-semibold">

                            Archivo

                        </label>

                        <input
                            type="file"
                            id="documento-archivo"
                            class="form-control">

                        <div class="form-text">

                            Selecciona el archivo que deseas asociar a esta cotización.

                        </div>

                        <div

                            id="info-documento-archivo"
                            class="mt-2 d-none">

                        </div>

                    </div>


                    <!-- ================= BOTONES ================= -->

                    <div class="d-flex justify-content-end gap-2">

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-secondary"
                            id="btn-cancelar-documento">

                            Cancelar

                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-primary"
                            id="btn-confirmar-documento">

                            <i class="bi bi-upload me-1"></i>

                            Continuar

                        </button>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- SOLICITUD RECIBIDA                                -->
            <!-- ================================================= -->

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-header bg-white border-bottom">

                    <div class="d-flex align-items-center gap-2">

                        <span
                            class="d-flex align-items-center justify-content-center rounded-circle bg-primary-subtle text-primary"
                            style="width:32px;height:32px;">

                            <i class="bi bi-download"></i>

                        </span>

                        <div>

                            <div class="fw-semibold">
                                Solicitud recibida
                            </div>

                            <div class="text-muted small">
                                Documento enviado para realizar la cotización
                            </div>

                        </div>

                    </div>

                </div>

                <div
                    class="card-body"
                    id="documentos-solicitud">

                    <div class="text-center py-3">

                        <i class="bi bi-file-earmark-x text-muted fs-3"></i>

                        <div class="text-muted small mt-2">

                            No hay documentos registrados.

                        </div>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- COTIZACIÓN ENVIADA                                -->
            <!-- ================================================= -->

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-header bg-white border-bottom">

                    <div class="d-flex align-items-center gap-2">

                        <span
                            class="d-flex align-items-center justify-content-center rounded-circle bg-success-subtle text-success"
                            style="width:32px;height:32px;">

                            <i class="bi bi-send"></i>

                        </span>

                        <div>

                            <div class="fw-semibold">
                                Cotización enviada
                            </div>

                            <div class="text-muted small">
                                Cotización enviada por el proveedor
                            </div>

                        </div>

                    </div>

                </div>

                <div
                    class="card-body"
                    id="documentos-cotizacion">

                    <div class="text-center py-3">

                        <i class="bi bi-file-earmark-x text-muted fs-3"></i>

                        <div class="text-muted small">

                            No hay documentos registrados.

                        </div>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- EVIDENCIAS                                        -->
            <!-- ================================================= -->

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-header bg-white border-bottom">

                    <div class="d-flex align-items-center gap-2">

                        <span
                            class="d-flex align-items-center justify-content-center rounded-circle bg-warning-subtle text-warning"
                            style="width:32px;height:32px;">

                            <i class="bi bi-paperclip"></i>

                        </span>

                        <div>

                            <div class="fw-semibold">
                                Evidencias
                            </div>

                            <div class="text-muted small">
                                Evidencias relacionadas con el registro
                            </div>

                        </div>

                    </div>

                </div>

                <div
                    class="card-body"
                    id="documentos-evidencia">

                    <div class="text-center py-3">

                        <i class="bi bi-file-earmark-x text-muted fs-3"></i>

                        <div class="text-muted small">

                            No hay documentos registrados.

                        </div>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- FACTURAS                                          -->
            <!-- ================================================= -->

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-header bg-white border-bottom">

                    <div class="d-flex align-items-center gap-2">

                        <span
                            class="d-flex align-items-center justify-content-center rounded-circle bg-danger-subtle text-danger"
                            style="width:32px;height:32px;">

                            <i class="bi bi-receipt"></i>

                        </span>

                        <div>

                            <div class="fw-semibold">
                                Facturas
                            </div>

                            <div class="text-muted small">
                                Documentos fiscales
                            </div>

                        </div>

                    </div>

                </div>

                <div
                    class="card-body"
                    id="documentos-factura">

                    <div class="text-center py-3">

                        <i class="bi bi-file-earmark-x text-muted fs-3"></i>

                        <div class="text-muted small">

                            No hay documentos registrados.

                        </div>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- ENTREGA                                           -->
            <!-- ================================================= -->

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-header bg-white border-bottom">

                    <div class="d-flex align-items-center gap-2">

                        <span
                            class="d-flex align-items-center justify-content-center rounded-circle bg-info-subtle text-info"
                            style="width:32px;height:32px;">

                            <i class="bi bi-box-seam"></i>

                        </span>

                        <div>

                            <div class="fw-semibold">
                                Entrega
                            </div>

                            <div class="text-muted small">
                                Documentación de entrega
                            </div>

                        </div>

                    </div>

                </div>

                <div
                    class="card-body"
                    id="documentos-entrega">

                    <div class="text-center py-3">

                        <i class="bi bi-file-earmark-x text-muted fs-3"></i>

                        <div class="text-muted small">

                            No hay documentos registrados.

                        </div>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- OTROS                                             -->
            <!-- ================================================= -->

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-header bg-white border-bottom">

                    <div class="d-flex align-items-center gap-2">

                        <span
                            class="d-flex align-items-center justify-content-center rounded-circle bg-secondary-subtle text-secondary"
                            style="width:32px;height:32px;">

                            <i class="bi bi-folder"></i>

                        </span>

                        <div>

                            <div class="fw-semibold">
                                Otros documentos
                            </div>

                            <div class="text-muted small">
                                Archivos adicionales
                            </div>

                        </div>

                    </div>

                </div>

                <div
                    class="card-body"
                    id="documentos-otro">

                    <div class="text-center py-3">

                        <i class="bi bi-file-earmark-x text-muted fs-3"></i>

                        <div class="text-muted small">

                            No hay documentos registrados.

                        </div>

                    </div>

                </div>

            </div>


        </div>

        ';
    }