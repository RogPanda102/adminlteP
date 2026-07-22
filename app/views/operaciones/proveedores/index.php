<?php
    $proveedores = $proveedores ?? [];
?>
<!-- TABULATOR CSS -->
<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
  crossorigin="anonymous"
/>
<style>
    #tabla-adjudicados .tabulator-row {
        cursor: pointer;
    }

    #tabla-adjudicados .tabulator-row:hover {
        background-color: rgba(13, 110, 253, 0.08);
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

                                <i class="bi bi-truck text-primary me-2"></i>

                                Proveedores

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
                                    placeholder="Buscar proveedor...">

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
                            href="<?= BASE_URL ?>proveedores/nueva"
                            class="btn btn-success">

                            <i class="bi bi-plus-circle me-1"></i>

                            Nuevo proveedor

                        </a>

                        <button
                            id="export-csv"
                            type="button"
                            class="btn btn-outline-success">

                            <i class="bi bi-filetype-csv me-1"></i>

                            Exportar CSV

                        </button>

                    </div>

                    <div id="tabla-proveedores"></div>

                </div>

            </div>

        </div>

    </div>

</main>

<!-- TABULATOR JS -->
<script
  src="https://cdn.jsdelivr.net/npm/tabulator-tables@6.4.0/dist/js/tabulator.min.js"
  crossorigin="anonymous"
></script>

<script src="<?= BASE_URL ?>assets/js/especificos/proveedores/proveedores_index.js"></script> <!-- aqui esta  -->


<script>

    document.addEventListener('DOMContentLoaded',function(){

        const tabla=new Tabulator('#tabla-proveedores',{

            layout:'fitColumns',

            responsiveLayout:"collapse",

            movableColumns:true,

            pagination:true,

            paginationSize:10,

            columns:[

                {
                    title:'Proveedor',
                    field:'proveedor',

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
                    title:'Servicios',
                    field:'servicios',

                    formatter:function(cell){

                        return `
                            <span class="badge bg-info">
                                ${cell.getValue() || ''}
                            </span>
                        `;

                    }

                },

                {
                    title:'Ubicación',
                    field:'ubicacion',

                    formatter:function(cell){

                        return `
                            <span class="erp-sub">
                                <i class="bi bi-geo-alt me-1"></i>
                                ${cell.getValue() || ''}
                            </span>
                        `;

                    }

                },

                {
                    title:'Contacto',
                    field:'contacto',

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
                    title:'Teléfono',
                    field:'telefono',
                    hozAlign:'center',

                    formatter:function(cell){

                        const telefono=cell.getValue();

                        if(!telefono) return '';

                        return `
                            <span class="erp-sub">
                                <i class="bi bi-telephone me-1"></i>
                                ${telefono}
                            </span>
                        `;

                    }

                },

                {
                    title:'Email',
                    field:'email',

                    formatter:function(cell){

                        const email=cell.getValue();

                        if(!email) return '';

                        return `
                            <a
                                href="mailto:${email}"
                                class="text-decoration-none">

                                <i class="bi bi-envelope me-1"></i>

                                ${email}

                            </a>
                        `;

                    }

                },

                {
                    title:'Sitio Web',
                    field:'enlace',
                    hozAlign:'center',

                    formatter:function(cell){

                        const url=cell.getValue();

                        if(!url){

                            return '<span class="text-muted">—</span>';

                        }

                        return `
                            <a
                                href="${url}"
                                target="_blank"
                                class="btn btn-sm btn-outline-primary">

                                <i class="bi bi-box-arrow-up-right"></i>

                            </a>
                        `;

                    }

                }

            ],

            data:<?= json_encode($proveedores) ?>

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

                tabla.download('csv','proveedores.csv');

            });

    });

</script>