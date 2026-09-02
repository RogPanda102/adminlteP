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

<!-- TABULATOR JS -->
<script
  src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/js/tabulator.min.js"
  
></script>

<script>
    document.addEventListener('DOMContentLoaded',function(){
    // =====================================
    // OFFCANVAS
    // =====================================

    const elementoOffcanvas =
        document.getElementById('offcanvasDetalleServicio');

    const offcanvas =
        bootstrap.Offcanvas.getOrCreateInstance(elementoOffcanvas);
    
    // =====================================
    // MOSTRAR DETALLE
    // =====================================
    function mostrarDetalle(servicio){
        console.log('Servicio seleccionado:', servicio);

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

    });
</script>