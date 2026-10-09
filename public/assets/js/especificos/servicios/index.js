document.addEventListener('DOMContentLoaded', function () {

    // =====================================
    // VARIABLES
    // =====================================

    let servicioSeleccionado = null;

    const cantidad =
        document.getElementById('edit-servicio-tiempo-cantidad');

    const unidad =
        document.getElementById('edit-servicio-tiempo-unidad');

    const inicio =
        document.getElementById('edit-servicio-inicio');

    const finalizacion =
        document.getElementById('edit-servicio-finalizacion');


    // =====================================
    // OFFCANVAS
    // =====================================

    const elementoOffcanvas =
        document.getElementById('offcanvasDetalleServicio');

    const offcanvas =
        bootstrap.Offcanvas.getOrCreateInstance(
            elementoOffcanvas
        );


    // =====================================
    // MOSTRAR DETALLE
    // =====================================

    function mostrarDetalle(servicio) {

        servicioSeleccionado = servicio;

        document.getElementById('det-titulo-folio').textContent =
            servicio.folio ?? '';

        document.getElementById('det-req').textContent =
            servicio.req ?? '';

        document.getElementById('det-folio').textContent =
            servicio.folio ?? '';

        document.getElementById('det-elaboro').textContent =
            servicio.elaboro ?? '';

        document.getElementById('det-partida').textContent =
            servicio.partida ?? '';

        document.getElementById('det-tipo-servicio').textContent =
            servicio.tipo_servicio ?? '';

        document.getElementById('det-analista').textContent =
            servicio.analista ?? '';

        // ==============================
        // TIEMPO DE CONTRATACIÓN
        // ==============================

        const cantidadTiempo =
            servicio.tiempo_cantidad ?? '';

        const unidadTiempo =
            servicio.tiempo_unidad ?? '';

        document.getElementById('det-tiempo').textContent =
            cantidadTiempo && unidadTiempo
                ? `${cantidadTiempo} ${unidadTiempo}`
                : cantidadTiempo || unidadTiempo || '';


        // ==============================
        // FECHAS
        // ==============================

        document.getElementById('det-contratacion').textContent =
            servicio.fecha_contratacion ?? '';

        document.getElementById('det-inicio').textContent =
            servicio.inicio ?? '';

        document.getElementById('det-finalizacion').textContent =
            servicio.finalizacion ?? '';


        offcanvas.show();
    }


    // =====================================
    // TABLA SERVICIOS
    // =====================================

    const tabla = new Tabulator(
        '#tabla-servicios',
        {

            layout: 'fitColumns',

            responsiveLayout: false,

            movableColumns: true,

            pagination: true,

            paginationSize: 10,

                // =================================
                // FILAS RESPONSIVE
                // =================================

                rowFormatter: function (row) {

                    const data = row.getData();
                    const el = row.getElement();

                    // Limpiar clase anterior
                    el.classList.remove(
                        'servicio-mobile-card'
                    );


                    // =================================
                    // VISTA MÓVIL
                    // =================================

                    if (window.innerWidth <= 767) {

                        el.classList.add(
                            'servicio-mobile-card'
                        );


                        // =================================
                        // TIEMPO
                        // =================================

                        const cantidad =
                            data.tiempo_cantidad ?? '';

                        const unidad =
                            data.tiempo_unidad ?? '';

                        const tiempo =
                            cantidad && unidad
                                ? `${cantidad} ${unidad}`
                                : cantidad || unidad || '—';


                        // =================================
                        // FECHA CONTRATACIÓN
                        // =================================

                        let fechaContratacion = '';

                        if (data.fecha_contratacion) {

                            const f =
                                new Date(
                                    data.fecha_contratacion +
                                    'T00:00:00'
                                );

                            fechaContratacion =
                                f.toLocaleDateString(
                                    'es-MX',
                                    {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    }
                                );
                        }


                        // =================================
                        // FECHA INICIO
                        // =================================

                        let fechaInicio = '';

                        if (data.inicio) {

                            const f =
                                new Date(
                                    data.inicio +
                                    'T00:00:00'
                                );

                            fechaInicio =
                                f.toLocaleDateString(
                                    'es-MX',
                                    {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    }
                                );
                        }


                        // =================================
                        // FECHA FINALIZACIÓN
                        // =================================

                        let fechaFinalizacion = '';

                        if (data.finalizacion) {

                            const f =
                                new Date(
                                    data.finalizacion +
                                    'T00:00:00'
                                );

                            fechaFinalizacion =
                                f.toLocaleDateString(
                                    'es-MX',
                                    {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    }
                                );
                        }


                        // =================================
                        // TARJETA MÓVIL
                        // =================================

                        el.innerHTML = `

                            <div class="servicio-mobile-header">

                                <div class="servicio-mobile-title">

                                    <div class="servicio-mobile-icon">
                                        <i class="bi bi-tools"></i>
                                    </div>

                                    <div>

                                        <div class="servicio-mobile-label">
                                            SERVICIO
                                        </div>

                                        <div class="servicio-mobile-folio">
                                            ${data.folio || 'Sin folio'}
                                        </div>

                                    </div>

                                </div>

                                <span class="badge bg-info text-dark rounded-pill px-3 py-2">
                                    ${data.tipo_servicio || 'Servicio'}
                                </span>

                            </div>


                            <div class="servicio-mobile-body">


                                <div class="servicio-mobile-row">

                                    <span class="servicio-mobile-label-data">

                                        <i class="bi bi-hash"></i>

                                        REQ

                                    </span>

                                    <span class="servicio-mobile-value">

                                        ${data.req || '—'}

                                    </span>

                                </div>


                                <div class="servicio-mobile-row">

                                    <span class="servicio-mobile-label-data">

                                        <i class="bi bi-person"></i>

                                        Elaboró

                                    </span>

                                    <span class="servicio-mobile-value">

                                        ${data.elaboro || '—'}

                                    </span>

                                </div>


                                <div class="servicio-mobile-row">

                                    <span class="servicio-mobile-label-data">

                                        <i class="bi bi-box-seam"></i>

                                        Partida

                                    </span>

                                    <span class="servicio-mobile-value">

                                        ${data.partida || '—'}

                                    </span>

                                </div>


                                <div class="servicio-mobile-row">

                                    <span class="servicio-mobile-label-data">

                                        <i class="bi bi-person-badge"></i>

                                        Analista

                                    </span>

                                    <span class="servicio-mobile-value servicio-mobile-long">

                                        ${data.analista || '—'}

                                    </span>

                                </div>


                                <div class="servicio-mobile-row">

                                    <span class="servicio-mobile-label-data">

                                        <i class="bi bi-clock"></i>

                                        Tiempo

                                    </span>

                                    <span class="servicio-mobile-value">

                                        ${tiempo}

                                    </span>

                                </div>


                                <div class="servicio-mobile-row">

                                    <span class="servicio-mobile-label-data">

                                        <i class="bi bi-calendar-check"></i>

                                        Contratación

                                    </span>

                                    <span class="servicio-mobile-value">

                                        ${fechaContratacion || '—'}

                                    </span>

                                </div>


                                <div class="servicio-mobile-row">

                                    <span class="servicio-mobile-label-data">

                                        <i class="bi bi-calendar-event"></i>

                                        Inicio

                                    </span>

                                    <span class="servicio-mobile-value">

                                        ${fechaInicio || '—'}

                                    </span>

                                </div>


                                <div class="servicio-mobile-row">

                                    <span class="servicio-mobile-label-data">

                                        <i class="bi bi-calendar-x"></i>

                                        Finalización

                                    </span>

                                    <span class="servicio-mobile-value">

                                        ${fechaFinalizacion || '—'}

                                    </span>

                                </div>


                                <div class="servicio-mobile-row">

                                    <span class="servicio-mobile-label-data">

                                        <i class="bi bi-building"></i>

                                        Dependencia

                                    </span>

                                    <span class="servicio-mobile-value servicio-mobile-long">

                                        ${data.dependencia || '—'}

                                    </span>

                                </div>


                            </div>


                            <div class="servicio-mobile-footer">

                                <span>

                                    <i class="bi bi-hand-index-thumb me-1"></i>

                                    Toca para ver el detalle

                                </span>

                                <i class="bi bi-chevron-right"></i>

                            </div>

                        `;

                    }

                },


            // =================================
            // COLUMNAS
            // =================================

            columns: [

                {
                    title: 'REQ',
                    field: 'req',

                    formatter: function (cell) {

                        return `
                            <span class="fw-semibold">
                                ${cell.getValue() || ''}
                            </span>
                        `;
                    }
                },


                {
                    title: 'Folio',
                    field: 'folio',
                    hozAlign: 'center',

                    formatter: function (cell) {

                        return `
                            <span class="folio-badge">
                                ${cell.getValue() || ''}
                            </span>
                        `;
                    }
                },


                {
                    title: 'Elaboró',
                    field: 'elaboro',

                    formatter: function (cell) {

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
                    title: 'Partida',
                    field: 'partida',

                    formatter: function (cell) {

                        return `
                            <span class="erp-sub">
                                ${cell.getValue() || ''}
                            </span>
                        `;
                    }
                },


                {
                    title: 'Analista',
                    field: 'analista',

                    formatter: function (cell) {

                        return `
                            <span class="erp-sub">
                                ${cell.getValue() || ''}
                            </span>
                        `;
                    }
                },


                {
                    title: 'Tiempo',
                    field: 'tiempo_cantidad',

                    formatter: function (cell) {

                        const servicio =
                            cell.getRow().getData();

                        const cantidadTiempo =
                            servicio.tiempo_cantidad ?? '';

                        const unidadTiempo =
                            servicio.tiempo_unidad ?? '';

                        if (!cantidadTiempo && !unidadTiempo) {
                            return '';
                        }

                        return `
                            <span class="badge bg-info">
                                ${cantidadTiempo} ${unidadTiempo}
                            </span>
                        `;
                    }
                },


                {
                    title: 'Contratación',
                    field: 'fecha_contratacion',
                    hozAlign: 'center',

                    formatter: function (cell) {

                        const v = cell.getValue();

                        if (!v) {
                            return '';
                        }

                        const f =
                            new Date(v + 'T00:00:00');

                        return `
                            <div class="erp-date">
                                ${f.toLocaleDateString(
                                    'es-MX',
                                    {
                                        day: '2-digit',
                                        month: 'short',
                                        year: 'numeric'
                                    }
                                )}
                            </div>
                        `;
                    }
                },


                {
                    title: 'Inicio',
                    field: 'inicio',
                    hozAlign: 'center',

                    formatter: function (cell) {

                        const v = cell.getValue();

                        if (!v) {
                            return '';
                        }

                        const f =
                            new Date(v + 'T00:00:00');

                        return `
                            <div class="erp-date">
                                ${f.toLocaleDateString(
                                    'es-MX',
                                    {
                                        day: '2-digit',
                                        month: 'short'
                                    }
                                )}
                            </div>
                        `;
                    }
                },


                {
                    title: 'Finalización',
                    field: 'finalizacion',
                    hozAlign: 'center',

                    formatter: function (cell) {

                        const v = cell.getValue();

                        if (!v) {
                            return '';
                        }

                        const f =
                            new Date(v + 'T00:00:00');

                        return `
                            <div class="erp-date">
                                ${f.toLocaleDateString(
                                    'es-MX',
                                    {
                                        day: '2-digit',
                                        month: 'short'
                                    }
                                )}
                            </div>
                        `;
                    }
                },


                {
                    title: 'Dependencia',
                    field: 'dependencia',

                    formatter: function (cell) {

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


            // =================================
            // DATOS
            // =================================

            data: window.servicios || []

        }
    );
    // =====================================
    // REDIBUJAR SERVICIOS AL CAMBIAR
    // ENTRE MÓVIL Y PC
    // =====================================

    let vistaMovilAnterior =
        window.innerWidth <= 767;

    window.addEventListener(
        'resize',
        function () {

            const vistaMovilActual =
                window.innerWidth <= 767;

            // Solo redibuja cuando realmente
            // cambia entre móvil y escritorio
            if (
                vistaMovilActual !==
                vistaMovilAnterior
            ) {

                vistaMovilAnterior =
                    vistaMovilActual;

                tabla.redraw(true);
            }

        }
    );


    // =====================================
    // ROW CLICK
    // =====================================

    tabla.on('rowClick', function (e, row) {

        const servicio =
            row.getData();

        mostrarDetalle(servicio);

    });


    // =====================================
    // EVENTO CLICK TABLA
    // =====================================

    document
        .getElementById('tabla-servicios')
        ?.addEventListener('click', function (e) {

        });


    // =====================================
    // FILTRO GLOBAL
    // =====================================

    document
        .getElementById('table-filter')
        ?.addEventListener(
            'keyup',
            function () {

                tabla.setFilter(
                    function (data) {

                        const texto =
                            this.value.toLowerCase();

                        return Object
                            .values(data)
                            .some(valor =>

                                String(valor ?? '')
                                    .toLowerCase()
                                    .includes(texto)

                            );

                    }.bind(this)
                );

            }
        );


    // =====================================
    // EXPORTAR CSV
    // =====================================

    document
        .getElementById('export-csv')
        ?.addEventListener(
            'click',
            function () {

                tabla.download(
                    'csv',
                    'servicios_2026.csv'
                );

            }
        );


    // =====================================
    // EDITAR SERVICIO
    // =====================================

    document
        .getElementById('btn-editar-servicio')
        ?.addEventListener(
            'click',
            function () {

                if (!servicioSeleccionado) {
                    return;
                }

                const servicio =
                    servicioSeleccionado;


                // ==============================
                // IDS
                // ==============================

                document
                    .getElementById('edit-servicio-id')
                    .value =
                    servicio.id ?? '';

                document
                    .getElementById('edit-servicio-anio')
                    .value =
                    servicio.anio ?? '';

                document
                    .getElementById('edit-servicio-analista_id')
                    .value =
                    servicio.analista_id ?? '';

                document
                    .getElementById('edit-servicio-dependencia_id')
                    .value =
                    servicio.dependencia_id ?? '';

                document
                    .getElementById('edit-servicio-tipo_servicio_id')
                    .value =
                    servicio.tipo_servicio_id ?? '';

                document
                    .getElementById('edit-servicio-adjudicado_id')
                    .value =
                    servicio.adjudicado_id ?? '';


                // ==============================
                // INFORMACIÓN GENERAL
                // ==============================

                document
                    .getElementById('edit-servicio-req')
                    .value =
                    servicio.req ?? '';

                document
                    .getElementById('edit-servicio-folio')
                    .value =
                    servicio.folio ?? '';

                document
                    .getElementById('edit-servicio-elaboro')
                    .value =
                    servicio.elaboro ?? '';

                document
                    .getElementById('edit-servicio-partida')
                    .value =
                    servicio.partida ?? '';


                // ==============================
                // INFORMACIÓN ADMINISTRATIVA
                // ==============================

                document
                    .getElementById('edit-servicio-analista')
                    .value =
                    servicio.analista ?? '';

                document
                    .getElementById('edit-servicio-dependencia')
                    .value =
                    servicio.dependencia ?? '';

                document
                    .getElementById('edit-servicio-tipo')
                    .value =
                    servicio.tipo_servicio ?? '';


                // ==============================
                // CONTRATACIÓN
                // ==============================

                document
                    .getElementById('edit-servicio-tiempo-cantidad')
                    .value =
                    servicio.tiempo_cantidad ?? '';

                document
                    .getElementById('edit-servicio-tiempo-unidad')
                    .value =
                    servicio.tiempo_unidad ?? '';

                document
                    .getElementById('edit-servicio-fecha-contratacion')
                    .value =
                    servicio.fecha_contratacion ?? '';

                document
                    .getElementById('edit-servicio-inicio')
                    .value =
                    servicio.inicio ?? '';

                document
                    .getElementById('edit-servicio-finalizacion')
                    .value =
                    servicio.finalizacion ?? '';


                // ==============================
                // MOSTRAR MODAL
                // ==============================

                const modalElemento =
                    document.getElementById(
                        'modalEditarServicio'
                    );

                const modal =
                    bootstrap.Modal.getOrCreateInstance(
                        modalElemento
                    );

                modal.show();

                recalcularFinalizacionVisual();

            }
        );


    // =====================================
    // RECALCULAR FECHA DE FINALIZACIÓN
    // =====================================

    function recalcularFinalizacionVisual() {

        const cantidadValor =
            cantidad.value;

        const unidadValor =
            unidad.value;

        const inicioValor =
            inicio.value;


        if (
            !cantidadValor ||
            !unidadValor ||
            !inicioValor
        ) {

            finalizacion.value = '';

            return;
        }


        const fechaInicio =
            new Date(
                inicioValor + 'T00:00:00'
            );


        if (isNaN(fechaInicio.getTime())) {

            finalizacion.value = '';

            return;
        }


        const cantidadNumero =
            parseInt(
                cantidadValor,
                10
            );


        if (cantidadNumero <= 0) {

            finalizacion.value = '';

            return;
        }


        let fechaFinal =
            new Date(fechaInicio);


        if (unidadValor === 'dias') {

            fechaFinal.setDate(
                fechaFinal.getDate() +
                cantidadNumero
            );
        }


        if (unidadValor === 'meses') {

            fechaFinal.setMonth(
                fechaFinal.getMonth() +
                cantidadNumero
            );
        }


        if (unidadValor === 'años') {

            fechaFinal.setFullYear(
                fechaFinal.getFullYear() +
                cantidadNumero
            );
        }


        const año =
            fechaFinal.getFullYear();


        const mes =
            String(
                fechaFinal.getMonth() + 1
            ).padStart(2, '0');


        const dia =
            String(
                fechaFinal.getDate()
            ).padStart(2, '0');


        finalizacion.value =
            `${año}-${mes}-${dia}`;
    }


    // =====================================
    // EVENTOS FECHA FINALIZACIÓN
    // =====================================

    cantidad?.addEventListener(
        'input',
        recalcularFinalizacionVisual
    );

    unidad?.addEventListener(
        'change',
        recalcularFinalizacionVisual
    );

    inicio?.addEventListener(
        'change',
        recalcularFinalizacionVisual
    );


    // =====================================
    // ACTUALIZAR SERVICIO
    // =====================================

    document
        .getElementById('formEditarServicio')
        ?.addEventListener(
            'submit',
            function (e) {

                e.preventDefault();


                const datos = {

                    id:
                        document
                            .getElementById(
                                'edit-servicio-id'
                            )
                            .value,

                    tiempo_cantidad:
                        document
                            .getElementById(
                                'edit-servicio-tiempo-cantidad'
                            )
                            .value,

                    tiempo_unidad:
                        document
                            .getElementById(
                                'edit-servicio-tiempo-unidad'
                            )
                            .value,

                    fecha_contratacion:
                        document
                            .getElementById(
                                'edit-servicio-fecha-contratacion'
                            )
                            .value,

                    inicio:
                        document
                            .getElementById(
                                'edit-servicio-inicio'
                            )
                            .value,

                    finalizacion:
                        document
                            .getElementById(
                                'edit-servicio-finalizacion'
                            )
                            .value
                };


                fetch(
                    window.BASE_URL +
                    'servicios/actualizar',
                    {
                        method: 'POST',

                        headers: {
                            'Content-Type':
                                'application/json'
                        },

                        body:
                            JSON.stringify(datos)
                    }
                )
                .then(response =>
                    response.json()
                )
                .then(data => {

                    console.log(
                        'RESPUESTA DEL SERVIDOR:',
                        data
                    );


                    if (!data.success) {

                        alert(
                            data.message
                        );

                        return;
                    }


                    alert(
                        data.message
                    );

                })
                .catch(error => {

                    console.error(
                        'ERROR:',
                        error
                    );

                    alert(
                        'Ocurrió un error al actualizar el servicio.'
                    );

                });

            }
        );

});