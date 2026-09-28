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
    style="width:420px;"
>

    <div class="offcanvas-header border-bottom">

        <div class="w-100">

            <div class="d-flex justify-content-between align-items-start">

                <div>

                    <h5 class="offcanvas-title mb-1">

                        <i class="bi bi-gear-wide-connected text-primary me-2"></i>

                        Servicio

                        <span
                            id="det-titulo-folio"
                            class="text-primary">
                        </span>

                    </h5>

                    <small class="text-muted">

                        Información del servicio

                    </small>

                </div>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas">
                </button>

            </div>

            <div class="mt-2 d-flex gap-2">

                <button
                    type="button"
                    class="btn btn-sm btn-primary px-3"
                    id="btn-editar-servicio">

                    <i class="bi bi-pencil-square me-1"></i>

                    Editar

                </button>

            </div>

        </div>

    </div>

    <div class="offcanvas-body bg-light">

        <!-- Información General -->

        <div class="card shadow-sm border-0 mb-3">

            <div class="card-header bg-white fw-semibold">

                <i class="bi bi-info-circle me-1 text-primary"></i>

                Información general

            </div>

            <div class="card-body py-2">

                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">REQ</span>
                    <span id="det-req"></span>
                </div>

                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Folio</span>
                    <span id="det-folio"></span>
                </div>

                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Año</span>
                    <span id="det-anio"></span>
                </div>

                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Elaboró</span>
                    <span id="det-elaboro"></span>
                </div>

                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Partida</span>
                    <span id="det-partida"></span>
                </div>

                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Analista</span>
                    <span id="det-analista"></span>
                </div>

                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Tipo de servicio</span>
                    <span id="det-tipo-servicio"></span>
                </div>

                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Dependencia</span>
                    <span id="det-dependencia"></span>
                </div>

            </div>

        </div>

        <!-- Contratación -->

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white fw-semibold">

                <i class="bi bi-calendar-event text-success me-1"></i>

                Fechas

            </div>

            <div class="card-body py-2">

                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Tiempo</span>
                    <span id="det-tiempo"></span>
                </div>

                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Contratación</span>
                    <span id="det-contratacion"></span>
                </div>

                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Inicio</span>
                    <span id="det-inicio"></span>
                </div>

                <div class="d-flex justify-content-between py-1">
                    <span class="text-muted">Finalización</span>
                    <span id="det-finalizacion"></span>
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
    document.addEventListener('DOMContentLoaded',function(){
    // =====================================
    // OFFCANVAS
    // =====================================
    let servicioSeleccionado = null;
    const cantidad = document.getElementById('edit-servicio-tiempo-cantidad');
    const unidad = document.getElementById('edit-servicio-tiempo-unidad');
    const inicio = document.getElementById('edit-servicio-inicio');
    const finalizacion = document.getElementById('edit-servicio-finalizacion');

    const elementoOffcanvas =
        document.getElementById('offcanvasDetalleServicio');

    const offcanvas =
        bootstrap.Offcanvas.getOrCreateInstance(elementoOffcanvas);
    
    // =====================================
    // MOSTRAR DETALLE
    // =====================================
    function mostrarDetalle(servicio){

        servicioSeleccionado = servicio;

        document.getElementById("det-titulo-folio").textContent =
            servicio.folio ?? "";

        document.getElementById("det-req").textContent =
            servicio.req ?? "";

        document.getElementById("det-folio").textContent =
            servicio.folio ?? "";

        document.getElementById("det-elaboro").textContent =
            servicio.elaboro ?? "";

        document.getElementById("det-anio").textContent =
            servicio.anio ?? "";

        document.getElementById("det-partida").textContent =
            servicio.partida ?? "";

        document.getElementById("det-tipo-servicio").textContent =
            servicio.tipo_servicio ?? "";

        document.getElementById("det-analista").textContent =
            servicio.analista ?? "";

        document.getElementById("det-dependencia").textContent =
            servicio.dependencia ?? "";
        // ==============================
        // TIEMPO DE CONTRATACIÓN
        // ==============================
        const cantidad = servicio.tiempo_cantidad ?? '';
        const unidad = servicio.tiempo_unidad ?? '';

        document.getElementById("det-tiempo").textContent =
            cantidad && unidad
                ? `${cantidad} ${unidad}`
                : cantidad || unidad || "";

        // ==============================
        // FECHAS
        // ==============================
        
        document.getElementById("det-contratacion").textContent =
            servicio.fecha_contratacion ?? "";

        document.getElementById("det-inicio").textContent =
            servicio.inicio ?? "";

        document.getElementById("det-finalizacion").textContent =
            servicio.finalizacion ?? "";
        
        offcanvas.show();

    }
        const tabla=new Tabulator('#tabla-servicios',{

            layout:'fitColumns',

            responsiveLayout:false,

            movableColumns:true,

            pagination:true,

            paginationSize:10,

            columns:[

                {
                    title:'REQ',
                    field:'req',

                    formatter:function(cell){

                        return `
                            <span class="fw-semibold">
                                ${cell.getValue() || ''}
                            </span>
                        `;

                    }

                },

                {
                    title:'Folio',
                    field:'folio',
                    hozAlign:'center',

                    formatter:function(cell){

                        return `
                            <span class="folio-badge">
                                ${cell.getValue() || ''}
                            </span>
                        `;

                    }

                },

                {
                    title:'Elaboró',
                    field:'elaboro',

                    formatter:function(cell){

                        return `
                            <div class="erp-main-cell">
                                <div class="erp-title">
                                    ${cell.getValue() || ''}
                                </div>
                            </div>
                        `;

                    }

                },

                {
                    title:'Partida',
                    field:'partida',

                    formatter:function(cell){

                        return `
                            <span class="erp-sub">
                                ${cell.getValue() || ''}
                            </span>
                        `;

                    }

                },

                {
                    title:'Analista',
                    field:'analista',

                    formatter:function(cell){

                        return `
                            <span class="erp-sub">
                                ${cell.getValue() || ''}
                            </span>
                        `;

                    }

                },

                {
                    title:'Tiempo',
                    field:'tiempo_cantidad',

                    formatter:function(cell){

                        const servicio = cell.getRow().getData();

                        const cantidad = servicio.tiempo_cantidad ?? '';
                        const unidad = servicio.tiempo_unidad ?? '';

                        if (!cantidad && !unidad) {
                            return '';
                        }

                        return `
                            <span class="badge bg-info">
                                ${cantidad} ${unidad}
                            </span>
                        `;

                    }

                },

                {
                    title:'Contratación',
                    field:'fecha_contratacion',
                    hozAlign:'center',

                    formatter:function(cell){

                        const v=cell.getValue();

                        if(!v) return '';

                        const f=new Date(v+"T00:00:00");

                        return `
                            <div class="erp-date">
                                ${f.toLocaleDateString('es-MX',{
                                    day:'2-digit',
                                    month:'short',
                                    year:'numeric'
                                })}
                            </div>
                        `;

                    }

                },

                {
                    title:'Inicio',
                    field:'inicio',
                    hozAlign:'center',

                    formatter:function(cell){

                        const v=cell.getValue();

                        if(!v) return '';

                        const f=new Date(v+"T00:00:00");

                        return `
                            <div class="erp-date">
                                ${f.toLocaleDateString('es-MX',{
                                    day:'2-digit',
                                    month:'short'
                                })}
                            </div>
                        `;

                    }

                },

                {
                    title:'Finalización',
                    field:'finalizacion',
                    hozAlign:'center',

                    formatter:function(cell){

                        const v=cell.getValue();

                        if(!v) return '';

                        const f=new Date(v+"T00:00:00");

                        return `
                            <div class="erp-date">
                                ${f.toLocaleDateString('es-MX',{
                                    day:'2-digit',
                                    month:'short'
                                })}
                            </div>
                        `;

                    }

                },

                {
                    title:'Dependencia',
                    field:'dependencia',

                    formatter:function(cell){

                        return `
                            <div class="erp-main-cell">

                                <div class="erp-title">
                                    ${cell.getValue() || ''}
                                </div>

                            </div>
                        `;

                    }

                }

            ],
            
            data:<?= json_encode($servicios) ?>

        });

        tabla.on('rowClick', function(e, row) {

            

            const servicio = row.getData();

            

            mostrarDetalle(servicio);

        });

        document
        .getElementById('tabla-servicios')
        .addEventListener('click', function(e) {

            

        });

        //=====================================
        // FILTRO GLOBAL
        //=====================================

        document
            .getElementById('table-filter')
            .addEventListener('keyup',function(){

                tabla.setFilter(function(data){

                    const texto=this.value.toLowerCase();

                    return Object.values(data).some(valor=>

                        String(valor ?? '')
                        .toLowerCase()
                        .includes(texto)

                    );

                }.bind(this));

            });

        //=====================================
        // EXPORTAR
        //=====================================

        document
            .getElementById('export-csv')
            .addEventListener('click',function(){

                tabla.download('csv','servicios_2026.csv');

            });

    

        // =====================================
        // EDITAR SERVICIO
        // =====================================

        document
            .getElementById('btn-editar-servicio')
            .addEventListener('click', function(){

                if (!servicioSeleccionado) {
                    return;
                }

                const servicio = servicioSeleccionado;

                // ==============================
                // IDS
                // ==============================

                document.getElementById('edit-servicio-id').value =
                    servicio.id ?? '';

                document.getElementById('edit-servicio-anio').value =
                    servicio.anio ?? '';

                document.getElementById('edit-servicio-analista_id').value =
                    servicio.analista_id ?? '';

                document.getElementById('edit-servicio-dependencia_id').value =
                    servicio.dependencia_id ?? '';

                document.getElementById('edit-servicio-tipo_servicio_id').value =
                    servicio.tipo_servicio_id ?? '';

                document.getElementById('edit-servicio-adjudicado_id').value =
                    servicio.adjudicado_id ?? '';


                // ==============================
                // INFORMACIÓN GENERAL
                // ==============================

                document.getElementById('edit-servicio-req').value =
                    servicio.req ?? '';

                document.getElementById('edit-servicio-folio').value =
                    servicio.folio ?? '';

                document.getElementById('edit-servicio-elaboro').value =
                    servicio.elaboro ?? '';

                document.getElementById('edit-servicio-partida').value =
                    servicio.partida ?? '';


                // ==============================
                // INFORMACIÓN ADMINISTRATIVA
                // ==============================

                document.getElementById('edit-servicio-analista').value =
                    servicio.analista ?? '';

                document.getElementById('edit-servicio-dependencia').value =
                    servicio.dependencia ?? '';

                document.getElementById('edit-servicio-tipo').value =
                    servicio.tipo_servicio ?? '';


                // ==============================
                // CONTRATACIÓN
                // ==============================

                document.getElementById('edit-servicio-tiempo-cantidad').value =
                    servicio.tiempo_cantidad ?? '';

                document.getElementById('edit-servicio-tiempo-unidad').value =
                    servicio.tiempo_unidad ?? '';

                document.getElementById('edit-servicio-fecha-contratacion').value =
                    servicio.fecha_contratacion ?? '';

                document.getElementById('edit-servicio-inicio').value =
                    servicio.inicio ?? '';

                document.getElementById('edit-servicio-finalizacion').value =
                    servicio.finalizacion ?? '';


                // ==============================
                // MOSTRAR MODAL
                // ==============================

                const modalElemento =
                    document.getElementById('modalEditarServicio');

                const modal =
                    bootstrap.Modal.getOrCreateInstance(modalElemento);

                modal.show();

                recalcularFinalizacionVisual();

            });
        
        //==============================
        // RECALCULAR FECHA DE FINALIZACIÓN
        //==============================

        
        function recalcularFinalizacionVisual() {

        const cantidadValor = cantidad.value;
        const unidadValor = unidad.value;
        const inicioValor = inicio.value;

        if (!cantidadValor || !unidadValor || !inicioValor) {
            finalizacion.value = '';
            return;
        }

        const fechaInicio = new Date(inicioValor + 'T00:00:00');

        if (isNaN(fechaInicio.getTime())) {
            finalizacion.value = '';
            return;
        }

        const cantidadNumero = parseInt(cantidadValor, 10);

        if (cantidadNumero <= 0) {
            finalizacion.value = '';
            return;
        }

        let fechaFinal = new Date(fechaInicio);

        if (unidadValor === 'dias') {
            fechaFinal.setDate(
                fechaFinal.getDate() + cantidadNumero
            );
        }

        if (unidadValor === 'meses') {
            fechaFinal.setMonth(
                fechaFinal.getMonth() + cantidadNumero
            );
        }

        if (unidadValor === 'años') {
            fechaFinal.setFullYear(
                fechaFinal.getFullYear() + cantidadNumero
            );
        }

        const año = fechaFinal.getFullYear();
        const mes = String(
            fechaFinal.getMonth() + 1
        ).padStart(2, '0');

        const dia = String(
            fechaFinal.getDate()
        ).padStart(2, '0');

        finalizacion.value =
            `${año}-${mes}-${dia}`;
    }
    cantidad.addEventListener(
        'input',
        recalcularFinalizacionVisual
    );

    unidad.addEventListener(
        'change',
        recalcularFinalizacionVisual
    );

    inicio.addEventListener(
        'change',
        recalcularFinalizacionVisual
    );

    document
    .getElementById('formEditarServicio')
    .addEventListener('submit', function(e) {

        e.preventDefault();

        const datos = {
            id: document.getElementById('edit-servicio-id').value,
            tiempo_cantidad: document.getElementById('edit-servicio-tiempo-cantidad').value,
            tiempo_unidad: document.getElementById('edit-servicio-tiempo-unidad').value,
            fecha_contratacion: document.getElementById('edit-servicio-fecha-contratacion').value,
            inicio: document.getElementById('edit-servicio-inicio').value,
            finalizacion: document.getElementById('edit-servicio-finalizacion').value
        };

        fetch('<?= BASE_URL ?>servicios/actualizar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(datos)
        })
        .then(response => response.json())
        .then(data => {

            console.log('RESPUESTA DEL SERVIDOR:', data);

            if (!data.success) {
                alert(data.message);
                return;
            }

            alert(data.message);

        })
        .catch(error => {

            console.error('ERROR:', error);

            alert('Ocurrió un error al actualizar el servicio.');

        });
    });

    });
</script>