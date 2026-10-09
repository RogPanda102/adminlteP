<?php
    $servicios = $servicios ?? [];
?>
<!-- TABULATOR CSS -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
  crossorigin="anonymous"
/>

<style>
    #tabla-servicios .tabulator-row {
        cursor: pointer;
    }

    #tabla-servicios .tabulator-row:hover {
        background-color: rgba(13, 110, 253, 0.08) !important;
    }
    /* ===============================
    FILAS ERP STYLE
    ================================*/
    .tabulator-row {
        border-left: 4px solid transparent;
        transition: all .2s ease;
    }

    .tabulator-row:hover {
        background: #f8fafc !important;
    }

    /* estados */
    .row-pagado {
        border-left: 4px solid #16a34a;
    }

    .row-pendiente {
        border-left: 4px solid #f59e0b;
    }

    .row-cancelado {
        border-left: 4px solid #dc2626;
    }

    /* ===============================
    FOLIO BADGE (KEY ERP DATA)
    ================================*/
    .folio-badge {
        background: #1f4b99;
        color: #fff;
        padding: 3px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12px;
    }

    /* ===============================
    CELDA PRINCIPAL
    ================================*/
    .erp-main-cell {
        display: flex;
        flex-direction: column;
    }

    .erp-title {
        font-weight: 600;
        color: #111827;
    }

    .erp-sub {
        font-size: 12px;
        color: #6b7280;
    }

    /* ===============================
    FECHA
    ================================*/
    .erp-date {
        font-size: 13px;
        color: #374151;
    }

    /* ===============================
    TOTAL (IMPORTANTE VISUAL)
    ================================*/
    .erp-total {
        font-weight: 700;
        color: #0f172a;
    }


</style>

<main class="app-main">

    <div class="app-content">

        <div class="container-fluid">

            <div class="card shadow-sm border-0">

                <!-- ========================================= -->
                <!-- HEADER -->
                <!-- ========================================= -->

                <div class="card-header bg-white border-bottom">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                        <div>

                            <h3 class="card-title mb-1 fw-semibold">

                                <i class="bi bi-gear-wide-connected text-primary me-2"></i>

                                Servicios 2026

                            </h3>

                        </div>

                        <div class="card-tools">

                            <div class="input-group input-group-sm" style="width:260px;">

                                <span class="input-group-text bg-white">

                                    <i class="bi bi-search"></i>

                                </span>

                                <input
                                    id="table-filter"
                                    type="search"
                                    class="form-control"
                                    placeholder="Buscar servicio...">

                            </div>

                        </div>

                    </div>

                </div>

                <!-- ========================================= -->
                <!-- BODY -->
                <!-- ========================================= -->

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

                        <a
                            href="<?= BASE_URL ?>servicios/nueva"
                            class="btn btn-success">

                            <i class="bi bi-plus-circle me-1"></i>

                            Nuevo servicio

                        </a>

                        <button
                            id="export-csv"
                            type="button"
                            class="btn btn-outline-success">

                            <i class="bi bi-filetype-csv me-1"></i>

                            Exportar CSV

                        </button>

                    </div>

                    <div id="tabla-servicios"></div>

                </div>

            </div>

        </div>

    </div>

</main>

<!-- ================= OFFCANVAS DETALLE SERVICIO ================= -->
<div
    class="offcanvas offcanvas-end"
    tabindex="-1"
    id="offcanvasDetalleServicio"
    style="width:420px; transition:all .3s ease;"
>

    <!-- ========================================================= -->
    <!-- HEADER                                                    -->
    <!-- ========================================================= -->

    <div class="offcanvas-header border-bottom flex-column align-items-start">

        <div class="d-flex justify-content-between w-100">

            <div>

                <h5 class="offcanvas-title mb-1 fw-semibold">

                    <i class="bi bi-tools text-primary me-2"></i>

                    Servicio

                    <span
                        id="det-titulo-folio"
                        class="text-primary">
                    </span>

                </h5>

                <small class="text-muted">
                    Información y periodo del servicio
                </small>

            </div>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="offcanvas">
            </button>

        </div>


        <!-- ACCIONES -->

        <div class="mt-3 d-flex gap-2 flex-wrap">

            <button
                type="button"
                class="btn btn-sm btn-primary px-3"
                id="btn-editar-servicio">

                <i class="bi bi-pencil-square me-1"></i>

                Editar

            </button>

        </div>

    </div>


    <!-- ========================================================= -->
    <!-- BODY                                                      -->
    <!-- ========================================================= -->

    <div
        class="offcanvas-body p-0 bg-light"
        style="overflow-x:hidden; overflow-y:auto;"
    >

        <div class="p-3">

            <!-- ================================================= -->
            <!-- PERIODO DEL SERVICIO                              -->
            <!-- ================================================= -->

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-header bg-white border-bottom fw-semibold">

                    <i class="bi bi-calendar-range text-success me-1"></i>

                    Periodo del servicio

                </div>


                <div class="card-body">


                    <!-- REQUISICIÓN + DURACIÓN -->

                    <div class="text-center mb-3">

                        <div class="c-flex justify-content-between align-items-center border-bottom pb-2 mb-3">

                            <span
                                id="det-req"
                                class="fw-bold text-primary">
                            </span>

                        </div>


                        <div class="text-muted small mb-1">
                            Duración contratada
                        </div>

                        <div
                            id="det-tiempo"
                            class="fs-4 fw-bold text-primary">
                        </div>

                    </div>


                    <!-- ================================================= -->
                    <!-- LÍNEA DE TIEMPO                                    -->
                    <!-- ================================================= -->

                    <div class="position-relative px-2">


                        <!-- CONTRATACIÓN -->

                        <div class="d-flex align-items-start gap-3 mb-4">

                            <div
                                class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:38px;height:38px;"
                            >

                                <i class="bi bi-file-earmark-check"></i>

                            </div>


                            <div>

                                <div class="text-muted small">
                                    Contratación
                                </div>

                                <div
                                    id="det-contratacion"
                                    class="fw-semibold">
                                </div>

                            </div>

                        </div>


                        <!-- INICIO -->

                        <div class="d-flex align-items-start gap-3 mb-4">

                            <div
                                class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:38px;height:38px;"
                            >

                                <i class="bi bi-play-fill"></i>

                            </div>


                            <div>

                                <div class="text-muted small">
                                    Inicio del servicio
                                </div>

                                <div
                                    id="det-inicio"
                                    class="fw-bold text-success">
                                </div>

                            </div>

                        </div>


                        <!-- FINALIZACIÓN -->

                        <div class="d-flex align-items-start gap-3">

                            <div
                                class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width:38px;height:38px;"
                            >

                                <i class="bi bi-flag-fill"></i>

                            </div>


                            <div>

                                <div class="text-muted small">
                                    Finalización
                                </div>

                                <div
                                    id="det-finalizacion"
                                    class="fw-bold text-danger">
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- INFORMACIÓN GENERAL                               -->
            <!-- ================================================= -->

            <div class="card border-0 shadow-sm mb-3">

                <div class="card-header bg-white border-bottom fw-semibold">

                    <i class="bi bi-info-circle me-1 text-primary"></i>

                    Información general

                </div>


                <div class="card-body py-2">


                    <!-- FOLIO -->

                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">

                        <span class="text-muted">
                            Folio
                        </span>

                        <span
                            id="det-folio"
                            class="fw-semibold text-end">
                        </span>

                    </div>


                    <!-- ELABORÓ -->

                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">

                        <span class="text-muted">
                            Elaboró
                        </span>

                        <span
                            id="det-elaboro"
                            class="fw-semibold text-end">
                        </span>

                    </div>


                    <!-- PARTIDA -->

                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">

                        <span class="text-muted">
                            Partida
                        </span>

                        <span
                            id="det-partida"
                            class="fw-semibold text-end">
                        </span>

                    </div>


                    <!-- ANALISTA -->

                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">

                        <span class="text-muted">
                            Analista
                        </span>

                        <span
                            id="det-analista"
                            class="fw-semibold text-end">
                        </span>

                    </div>


                    <!-- TIPO DE SERVICIO -->

                    <div class="d-flex justify-content-between align-items-center py-2">

                        <span class="text-muted">
                            Tipo de servicio
                        </span>

                        <span
                            id="det-tipo-servicio"
                            class="fw-semibold text-end">
                        </span>

                    </div>

                </div>

            </div>


            <!-- ================================================= -->
            <!-- RESUMEN DEL SERVICIO                              -->
            <!-- ================================================= -->

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-bottom fw-semibold">

                    <i class="bi bi-briefcase text-primary me-1"></i>

                    Resumen del servicio

                </div>


                <div class="card-body">

                    <!-- SERVICIO -->

                    <div class="d-flex align-items-center gap-3">

                        <div
                            class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:42px;height:42px;"
                        >

                            <i class="bi bi-tools"></i>

                        </div>


                        <div class="min-width-0">

                            <div class="fw-semibold">
                                Servicio contratado
                            </div>

                            <div class="text-muted small">
                                Información detallada disponible en la sección anterior.
                            </div>

                        </div>

                    </div>


                    <hr>


                    <!-- DEPENDENCIA -->

                    <div class="d-flex align-items-start gap-3">

                        <div
                            class="rounded-circle bg-light text-primary d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width:42px;height:42px;"
                        >

                            <i class="bi bi-building"></i>

                        </div>


                        <div class="min-width-0">

                            <div class="fw-semibold">
                                Dependencia
                            </div>

                            <div
                                id="det-dependencia"
                                class="text-muted small">
                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ================= MODAL EDITAR SERVICIO ================= -->

<div
    class="modal fade"
    id="modalEditarServicio"
    tabindex="-1"
>

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content shadow-lg">

            <!-- HEADER -->

            <div class="modal-header bg-primary text-white">

                <div>

                    <h5 class="modal-title mb-1">

                        <i class="bi bi-pencil-square me-2"></i>

                        Editar servicio

                    </h5>

                    <small>
                        Modificación de información del servicio
                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <form id="formEditarServicio">

                <div class="modal-body p-2">

                    <!-- IDS -->

                    <input
                        type="hidden"
                        id="edit-servicio-id"
                    >

                    <input
                        type="hidden"
                        id="edit-servicio-anio"
                    >

                    <input
                        type="hidden"
                        id="edit-servicio-analista_id"
                    >

                    <input
                        type="hidden"
                        id="edit-servicio-dependencia_id"
                    >

                    <input
                        type="hidden"
                        id="edit-servicio-tipo_servicio_id"
                    >

                    <input
                        type="hidden"
                        id="edit-servicio-adjudicado_id"
                    >


                    <!-- ========================================= -->
                    <!-- INFORMACIÓN GENERAL -->
                    <!-- ========================================= -->

                    <div class="card mb-2">

                        <div
                            class="card-header bg-light py-2"
                        >

                            <strong>

                                <i class="bi bi-info-circle me-1"></i>

                                Información general

                            </strong>

                        </div>


                        <div class="card-body py-2">

                            <div class="row g-2">

                                <div class="col-md-3">

                                    <label class="form-label mb-0">
                                        REQ
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        id="edit-servicio-req"
                                        disabled
                                        readonly
                                    >

                                </div>


                                <div class="col-md-3">

                                    <label class="form-label mb-0">
                                        Folio
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        id="edit-servicio-folio"
                                        disabled
                                        readonly
                                    >

                                </div>


                                <div class="col-md-3">

                                    <label class="form-label mb-0">
                                        Elaboró
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        id="edit-servicio-elaboro"
                                        disabled
                                        readonly
                                    >

                                </div>


                                <div class="col-md-3">

                                    <label class="form-label mb-0">
                                        Año
                                    </label>

                                    <input
                                        type="number"
                                        class="form-control form-control-sm"
                                        id="edit-servicio-anio"
                                        disabled
                                        readonly
                                    >

                                </div>


                                <div class="col-md-12">

                                    <label class="form-label mb-0">
                                        Partida
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        id="edit-servicio-partida"
                                        disabled
                                        readonly
                                    >

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- ========================================= -->
                    <!-- INFORMACIÓN ADMINISTRATIVA -->
                    <!-- ========================================= -->

                    <div class="card mb-2">

                        <div
                            class="card-header bg-light py-2"
                        >

                            <strong>

                                <i class="bi bi-building me-1"></i>

                                Información administrativa

                            </strong>

                        </div>


                        <div class="card-body py-2">

                            <div class="row g-2">

                                <div class="col-md-4">

                                    <label class="form-label mb-0">
                                        Analista
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        id="edit-servicio-analista"
                                        disabled
                                        readonly
                                    >

                                </div>


                                <div class="col-md-4">

                                    <label class="form-label mb-0">
                                        Dependencia
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        id="edit-servicio-dependencia"
                                        disabled
                                        readonly
                                    >

                                </div>


                                <div class="col-md-4">

                                    <label class="form-label mb-0">
                                        Tipo de servicio
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        id="edit-servicio-tipo"
                                        disabled
                                        readonly
                                    >

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- ========================================= -->
                    <!-- CONTRATACIÓN -->
                    <!-- ========================================= -->

                    <div class="card mb-2">

                        <div
                            class="card-header bg-light py-2"
                        >

                            <strong>

                                <i class="bi bi-calendar-event me-1"></i>

                                Contratación

                            </strong>

                        </div>


                        <div class="card-body py-2">

                            <div class="row g-2">

                                <div class="col-md-4">

                                    <label class="form-label mb-0">
                                        Cantidad
                                    </label>

                                    <input
                                        type="number"
                                        min="1"
                                        class="form-control form-control-sm"
                                        id="edit-servicio-tiempo-cantidad"
                                    >

                                </div>


                                <div class="col-md-4">

                                    <label class="form-label mb-0">
                                        Unidad
                                    </label>

                                    <select
                                        class="form-select form-select-sm"
                                        id="edit-servicio-tiempo-unidad"
                                    >

                                        <option value="">
                                            Seleccionar
                                        </option>

                                        <option value="dias">
                                            Días
                                        </option>

                                        <option value="meses">
                                            Meses
                                        </option>

                                        <option value="años">
                                            Años
                                        </option>

                                    </select>

                                </div>


                                <div class="col-md-4">

                                    <label class="form-label mb-0">
                                        Fecha de contratación
                                    </label>

                                    <input
                                        type="text"
                                        class="form-control form-control-sm"
                                        id="edit-servicio-fecha-contratacion"
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label mb-0">
                                        Inicio
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        id="edit-servicio-inicio"
                                    >

                                </div>


                                <div class="col-md-6">

                                    <label class="form-label mb-0">
                                        Finalización
                                    </label>

                                    <input
                                        type="date"
                                        class="form-control form-control-sm"
                                        id="edit-servicio-finalizacion"
                                        disabled
                                        readonly
                                    >

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                <!-- FOOTER -->

                <div class="modal-footer py-2">

                    <button
                        type="button"
                        class="btn btn-sm btn-secondary"
                        data-bs-dismiss="modal"
                    >

                        Cancelar

                    </button>


                    <button
                        type="submit"
                        class="btn btn-sm btn-primary"
                    >

                        <i class="bi bi-save me-1"></i>

                        Guardar cambios

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<!-- TABULATOR JS -->
<script
  src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/js/tabulator.min.js"
  
></script>

<script>
    window.servicios = <?= json_encode($servicios) ?>;
    window.BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/especificos/servicios/index.js"></script>