document.addEventListener('DOMContentLoaded', function () {
    // =====================================================
    // CATÁLOGO ANALISTA - EDITAR
    // =====================================================
    const catalogoEditAnalista = crearCatalogo({
        campo: 'analista',
        input: '#edit-analista',
        resultados: '#lista-edit-analista',
        idInput: '#edit-analista_id'
    });

    // =====================================================
    // AUTOCOMPLETE DEPENDENCIA
    // =====================================================

    const catalogoEditDependencia = crearCatalogo({
        campo: 'dependencia',
        input: '#edit-dependencia',
        resultados: '#lista-edit-dependencia',
        idInput: '#edit-dependencia_id',
    });

    // =====================================================
    // CATÁLOGO PROVEEDOR - EDITAR
    // =====================================================

    const catalogoEditProveedor = crearCatalogo({
        campo: 'proveedor',
        input: '#edit-proveedor',
        resultados: '#lista-edit-proveedor',
        idInput: '#edit-proveedor_id'
    });

    // =====================================================
    // BOTONES ANALISTA - EDITAR
    // =====================================================

    document.getElementById('btnEditAnalista')?.addEventListener('click', function () {

        catalogoEditAnalista.mostrarTodos();

    });

    // =====================================================
    // BOTÓN MOSTRAR TODAS LAS DEPENDENCIAS - EDITAR
    // =====================================================

    document.getElementById('btnEditDependencia')?.addEventListener('click', function () {

        catalogoEditDependencia.mostrarTodos();

    });

    // =====================================================
    // BOTÓN MOSTRAR TODOS LOS PROVEEDORES - EDITAR
    // =====================================================

    document.getElementById('btnEditProveedor')?.addEventListener('click', function () {

        catalogoEditProveedor.mostrarTodos();

    });


    const tabla = new Tabulator('#tabla-cotizaciones', {
        layout: 'fitColumns',
        responsiveLayout: "collapse",
        movableColumns: true,
        pagination: true,
        paginationSize: 10,
        columns: [
            // ======================================
            // FECHA
            // ======================================
            {
                title: 'Fecha',
                field: 'fecha',
                hozAlign: 'center',
                formatter: function (cell) {
                    const v = cell.getValue();
                    if (!v) return "";
                    const f = new Date(v + "T00:00:00");
                    return `
                            <div class="erp-date">
                                ${f.toLocaleDateString('es-MX', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    })}
                            </div>
                        `;
                }
            },
            // ======================================
            // REQ
            // ======================================
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
            // ======================================
            // FOLIO
            // ======================================
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
            // ======================================
            // ELABORÓ
            // ======================================
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
            // ======================================
            // PARTIDA
            // ======================================
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
            // ======================================
            // PROVEEDOR
            // ======================================
            {
                title: 'Proveedor',
                field: 'proveedor',
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
            // ======================================
            // ANALISTA
            // ======================================
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
            // ======================================
            // ESTATUS
            // ======================================
            {
                title: 'Estatus',
                field: 'estatus',
                hozAlign: 'center',
                formatter: function (cell) {
                    const estado = (cell.getValue() || '').toLowerCase();
                    let clase = "bg-secondary";
                    switch (estado) {
                        case "enviado":
                            clase = "bg-success";
                            break;
                        case "respaldo":
                            clase = "bg-warning text-dark";
                            break;
                        case "no se cotiza":
                            clase = "bg-danger";
                            break;
                        case "pendiente":
                            clase = "bg-secondary";
                            break;
                    }
                    return `
                                    <span class="badge ${clase} rounded-pill px-3 py-2 fw-semibold">
                                        ${cell.getValue() || ''}
                                    </span>
                                `;
                }
            }
        ],
        data: window.cotizaciones || []
    });

    // =====================================================
    // ROW CLICK
    // =====================================================

    tabla.on('rowClick', function (e, row) {

        const data = row.getData();

        window.currentCotizacion = data;

        // ============================
        // HEADER
        // ============================

        document.getElementById('erp-folio-title').textContent =
            data.folio || '';

        const status = document.getElementById('erp-status');

        const estado = (data.estatus || 'enviado').toLowerCase();

        status.className = 'badge px-3 py-2 rounded-pill';

        switch (estado) {

            case 'enviado':
                status.classList.add('bg-success');
                break;

            case 'respaldo':
                status.classList.add('bg-warning', 'text-dark');
                break;

            case 'no se cotiza':
                status.classList.add('bg-danger');
                break;

            case 'n/a':
                status.classList.add('bg-info', 'text-dark');
                break;

            default:
                status.classList.add('bg-secondary');

        }

        status.textContent = data.estatus || '';

        // ============================
        // INFORMACIÓN GENERAL
        // ============================

        document.getElementById('det-fecha').textContent =
            data.fecha || '';

        document.getElementById('det-req').textContent =
            data.req || '';

        document.getElementById('det-folio').textContent =
            data.folio || '';

        document.getElementById('det-elaboro').textContent =
            data.elaboro || '';

        document.getElementById('det-partida').textContent =
            data.partida || '';

        document.getElementById('det-analista').textContent =
            data.analista || '';

        // ============================
        // PROVEEDOR
        // ============================

        document.getElementById('det-proveedor').textContent =
            data.proveedor || '';

        // ============================
        // ESTATUS
        // ============================

        document.getElementById('det-estatus').textContent =
            data.estatus || '';

        // ============================
        // OFFCANVAS
        // ============================

        bootstrap.Offcanvas.getOrCreateInstance(
            document.getElementById('offcanvasDetalleCotizacion')
        ).show();

        // ============================
        // LIMPIAR HISTORIAL
        // ============================

        if (typeof cerrarHistorial === 'function') {
            cerrarHistorial();
        }

    });


    // =====================================================
    // EDITAR MODAL
    // =====================================================

    document.getElementById('btn-editar')?.addEventListener('click', function () {

        const data = window.currentCotizacion;

        if (!data) return;

        // ==========================
        // IDS
        // ==========================

        document.getElementById('edit-id').value =
            data.id || '';

        document.getElementById('edit-anio').value =
            data.anio || '';

        // ==========================
        // INFORMACIÓN GENERAL
        // ==========================

        document.getElementById('edit-fecha').value =
            data.fecha || '';

        document.getElementById('edit-req').value =
            data.req || '';

        document.getElementById('edit-folio').value =
            data.folio || '';

        document.getElementById('edit-elaboro').value =
            data.elaboro || '';

        document.getElementById('edit-partida').value =
            data.partida || '';

        document.getElementById('edit-proveedor').value =
            data.proveedor || '';

        // ==========================
        // ADMINISTRACIÓN
        // ==========================

        document.getElementById('edit-dependencia').value =
            data.dependencia || '';

        catalogoEditAnalista.seleccionar({
            id: data.analista_id || '',
            nombre: data.analista || ''
        });

        // ==========================
        // OPCIONES
        // ==========================

        document.getElementById('edit-estatus').value =
            data.estatus || 'enviado';

        document.getElementById('edit-reenviar').checked =
            Number(data.reenviar) === 1;

        // ==========================
        // ABRIR MODAL
        // ==========================

        bootstrap.Modal
            .getOrCreateInstance(
                document.getElementById('modalEditarCotizacion')
            )
            .show();


    });

    // =====================================================
    // COLLAPSE MODAL COTIZACIÓN
    // =====================================================

    document.addEventListener('click', (e) => {

        const header = e.target.closest('.section-toggle');
        if (!header) return;

        const modal = document.getElementById('modalEditarCotizacion');

        if (!modal.contains(header)) return;

        const target = modal.querySelector(header.dataset.target);
        if (!target) return;

        modal.querySelectorAll('.collapse').forEach(el => {

            if (el !== target) {
                bootstrap.Collapse.getOrCreateInstance(el).hide();
            }

        });

        bootstrap.Collapse.getOrCreateInstance(target).toggle();

    });

    // =====================================================
    // GUARDAR AJAX
    // =====================================================

    document.getElementById('formEditarCotizacion')?.addEventListener('submit', function (e) {

        e.preventDefault();

        const folioInput = document.getElementById('edit-folio');

        let folio = folioInput.value.trim();

        // Solo números (máx. 4)
        folio = folio.replace(/\D/g, '').slice(0, 4);

        folioInput.value = folio;

        const payload = {

            id: document.getElementById('edit-id').value,

            fecha: document.getElementById('edit-fecha').value,

            req: document.getElementById('edit-req').value,

            folio: folio,

            elaboro: document.getElementById('edit-elaboro').value,

            partida: document.getElementById('edit-partida').value,

            proveedor: document.getElementById('edit-proveedor').value,

            analista_id: document.getElementById('edit-analista_id').value,

            dependencia_id: document.getElementById('edit-dependencia_id').value,

            estatus: document.getElementById('edit-estatus').value,

            reenviar: document.getElementById('edit-reenviar').checked ? 1 : 0,

            anio: document.getElementById('edit-anio').value

        };

        fetch(BASE_URL + 'cotizaciones/update', {

            method: 'POST',

            headers: {
                'Content-Type': 'application/json'
            },

            body: JSON.stringify(payload)

        })
            .then(r => r.json())
            .then(data => {

                if (data.success) {

                    const fila = tabla.getRow(payload.id);

                    if (fila) {

                        fila.update(data.row ?? payload);

                    }

                    window.currentCotizacion = {

                        ...window.currentCotizacion,
                        ...(data.row ?? payload)

                    };

                    bootstrap.Modal.getInstance(
                        document.getElementById('modalEditarCotizacion')
                    )?.hide();

                    toastr.success(data.message);

                } else {

                    toastr.error(data.message);

                }

            })
            .catch(() => {

                toastr.error('Error al actualizar');

            });

    });
    // =====================================================
    // HISTORIAL COTIZACIONES
    // =====================================================
    const btnHistorial = document.getElementById('btn-historial');
    btnHistorial?.addEventListener('click', async function () {

        const id = window.currentCotizacion?.id;

        if (!id) return;

        const itemsContainer = document.getElementById('historial-items');
        const detalleContainer = document.getElementById('historial-detalle');

        if (!itemsContainer || !detalleContainer) return;

        itemsContainer.innerHTML = `
                <div class="text-muted small">
                    Cargando historial...
                </div>
            `;

        detalleContainer.innerHTML = `
                <div class="text-muted small">
                    Selecciona un cambio para ver el detalle
                </div>
            `;

        try {

            const res = await fetch(
                `${BASE_URL}historial/cotizaciones&id=${id}`
            );

            const json = await res.json();

            if (!json.success) {
                throw new Error(json.message);
            }

            const data = json.data || [];

            window.historialData = data;

            if (!data.length) {

                itemsContainer.innerHTML = `
                        <div class="text-muted">
                            Sin cambios registrados
                        </div>
                    `;

                return;
            }

            itemsContainer.innerHTML = data.map((item, index) => {

                const fecha = item.fecha
                    ? new Date(item.fecha).toLocaleString('es-MX')
                    : '';

                let badge = 'bg-primary';

                if (item.accion === 'CREATE')
                    badge = 'bg-success';

                if (item.accion === 'DELETE')
                    badge = 'bg-danger';

                return `
                        <div
                            class="hist-item p-3 mb-2 border rounded shadow-sm"
                            data-index="${index}"
                            style="cursor:pointer">

                            <div class="d-flex justify-content-between align-items-center">

                                <span class="badge ${badge}">
                                    ${item.accion}
                                </span>

                                <small class="text-muted">
                                    ${fecha}
                                </small>

                            </div>

                        </div>
                    `;

            }).join('');

            document.querySelectorAll('.hist-item').forEach(item => {

                item.addEventListener('click', function () {

                    document.querySelectorAll('.hist-item').forEach(el => {
                        el.classList.remove('border-primary');
                    });

                    this.classList.add('border-primary');

                    renderHistorialDetalle(
                        window.historialData[this.dataset.index]
                    );

                });

            });

        } catch (error) {

            console.error(error);

            itemsContainer.innerHTML = `
                    <div class="text-danger">
                        Error cargando historial
                    </div>
                `;

        }

    });

    // inicia aqui
    // =====================================================
    // PANEL DOCUMENTOS
    // =====================================================

    const btnDocumentos = document.getElementById('btn-documentos');
    const btnVolverCotizacion = document.getElementById('btn-volver-cotizacion');
    const erpPanels = document.getElementById('erp-panels');

    const btnSubirDocumento = document.getElementById('btn-subir-documento');
    const btnCancelarDocumento = document.getElementById('btn-cancelar-documento');
    const panelCargaDocumento = document.getElementById('panel-carga-documento');

    const inputDocumentoArchivo = document.getElementById('documento-archivo');
    const infoDocumentoArchivo = document.getElementById('info-documento-archivo');

    const selectDocumentoTipo = document.getElementById('documento-tipo');
    const btnConfirmarDocumento = document.getElementById('btn-confirmar-documento');


    // ============================
    // ABRIR DOCUMENTOS
    // ============================

    btnDocumentos?.addEventListener('click', async function () {

        if (!erpPanels) return;

        const id = window.currentCotizacion?.id;

        if (!id) {
            toastr.error('No se encontró la cotización');
            return;
        }

        // ----------------------------------
        // Mostrar panel documentos
        // ----------------------------------

        erpPanels.style.transform = 'translateX(-50%)';

        // ----------------------------------
        // Limpiar mientras carga
        // ----------------------------------

        const contenedores = [
            'solicitud',
            'cotizacion',
            'evidencia',
            'factura',
            'entrega',
            'otro'
        ];

        contenedores.forEach(tipo => {

            const container = document.getElementById(
                `documentos-${tipo}`
            );

            if (container) {

                container.innerHTML = `
                    <div class="text-muted small text-center py-3">
                        Cargando documentos...
                    </div>
                `;

            }

        });

        // ----------------------------------
        // AJAX
        // ----------------------------------

        try {

            const url =
                `${BASE_URL}cotizaciones/documentos?modulo=cotizaciones&registro_id=${id}`;

            const res = await fetch(url);

            const json = await res.json();

            if (!json.success) {
                throw new Error(
                    json.message || 'Error al cargar documentos'
                );
            }

            const documentos = json.data || [];

            // ----------------------------------
            // Limpiar contenedores
            // ----------------------------------

            contenedores.forEach(tipo => {

                const container = document.getElementById(
                    `documentos-${tipo}`
                );

                if (!container) return;

                container.innerHTML = '';

            });

            // ----------------------------------
            // Sin documentos
            // ----------------------------------

            if (!documentos.length) {

                contenedores.forEach(tipo => {

                    const container = document.getElementById(
                        `documentos-${tipo}`
                    );

                    if (!container) return;

                    container.innerHTML = `
                        <div class="text-muted small text-center py-3">
                            No hay documentos registrados.
                        </div>
                    `;

                });

                return;
            }

            // ----------------------------------
            // Mostrar documentos
            // ----------------------------------

            documentos.forEach(doc => {

                const container = document.getElementById(
                    `documentos-${doc.tipo}`
                );

                if (!container) return;

                const fecha = doc.fecha_creacion
                    ? new Date(doc.fecha_creacion).toLocaleString('es-MX')
                    : '';

                const tamano = doc.tamano
                    ? formatearTamanoDocumento(doc.tamano)
                    : '';

                container.insertAdjacentHTML(
                    'beforeend',
                    `
                        <div class="border rounded p-2 mb-2 bg-white">

                            <div class="d-flex align-items-start gap-2">

                                <div class="fs-4 text-primary">
                                    📄
                                </div>

                                <div class="flex-grow-1">

                                    <div class="fw-semibold">
                                        ${doc.nombre_original || ''}
                                    </div>

                                    <div class="text-muted small">
                                        ${doc.extension || ''}
                                        ${tamano ? ' · ' + tamano : ''}
                                    </div>

                                    <div class="text-muted small">
                                        ${fecha}
                                        ${doc.usuario_nombre
                                            ? ' · ' + doc.usuario_nombre
                                            : ''}
                                    </div>

                                </div>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary">
                                    Ver
                                </button>

                            </div>

                        </div>
                    `
                );

            });

        } catch (error) {

            console.error(error);

            contenedores.forEach(tipo => {

                const container = document.getElementById(
                    `documentos-${tipo}`
                );

                if (!container) return;

                container.innerHTML = `
                    <div class="text-danger small text-center py-3">
                        Error al cargar los documentos.
                    </div>
                `;

            });

        }

    });


    // ============================
    // VOLVER A COTIZACIÓN
    // ============================

    btnVolverCotizacion?.addEventListener('click', function () {

        if (!erpPanels) return;

        erpPanels.style.transform = 'translateX(0)';

    });


    // =====================================================
    // FORMATEAR TAMAÑO
    // =====================================================

    function formatearTamanoDocumento(bytes) {

        bytes = Number(bytes);

        if (!bytes || bytes <= 0) {
            return '0 B';
        }

        const unidades = [
            'B',
            'KB',
            'MB',
            'GB'
        ];

        const indice = Math.floor(
            Math.log(bytes) / Math.log(1024)
        );

        const posicion = Math.min(
            indice,
            unidades.length - 1
        );

        return (
            bytes / Math.pow(1024, posicion)
        ).toFixed(posicion === 0 ? 0 : 2)
            + ' '
            + unidades[posicion];

    }

    // ============================
    // MOSTRAR CARGA DE DOCUMENTO
    // ============================

    btnSubirDocumento?.addEventListener('click', function () {

        if (!panelCargaDocumento) return;

        panelCargaDocumento.classList.remove('d-none');

    });

    // ============================
    // CANCELAR CARGA DE DOCUMENTO
    // ============================

    btnCancelarDocumento?.addEventListener('click', function () {

        if (!panelCargaDocumento) return;

        // Limpiar archivo seleccionado
        if (inputDocumentoArchivo) {
            inputDocumentoArchivo.value = '';
        }

        // Limpiar información del archivo
        if (infoDocumentoArchivo) {

            infoDocumentoArchivo.innerHTML = '';

            infoDocumentoArchivo.classList.add('d-none');

        }

        // Ocultar panel
        panelCargaDocumento.classList.add('d-none');

    });

    // ============================
    // SELECCIONAR ARCHIVO
    // ============================

    inputDocumentoArchivo?.addEventListener('change', function () {

        if (!infoDocumentoArchivo) return;

        const archivo = this.files?.[0];

        // ----------------------------------
        // Sin archivo
        // ----------------------------------

        if (!archivo) {

            infoDocumentoArchivo.classList.add('d-none');
            infoDocumentoArchivo.innerHTML = '';

            return;
        }

        // ----------------------------------
        // Datos del archivo
        // ----------------------------------

        const nombre = archivo.name;
        const tamano = formatearTamanoDocumento(archivo.size);

        // ----------------------------------
        // Mostrar información
        // ----------------------------------

        infoDocumentoArchivo.innerHTML = `
            <div class="border rounded bg-light p-2">

                <div class="d-flex align-items-center gap-2">

                    <i class="bi bi-file-earmark text-primary fs-4"></i>

                    <div class="flex-grow-1">

                        <div class="fw-semibold text-break">
                            ${nombre}
                        </div>

                        <div class="text-muted small">
                            ${tamano}
                        </div>

                    </div>

                </div>

            </div>
        `;

        infoDocumentoArchivo.classList.remove('d-none');

    });


    // ============================
    // ENVIAR DOCUMENTO AJAX
    // ============================

    btnConfirmarDocumento?.addEventListener('click', async function () {

        const tipo = selectDocumentoTipo?.value || '';
        const archivo = inputDocumentoArchivo?.files?.[0];

        // ----------------------------------
        // VALIDAR TIPO
        // ----------------------------------

        if (!tipo) {

            toastr.warning('Selecciona el tipo de documento.');

            selectDocumentoTipo?.focus();

            return;
        }

        // ----------------------------------
        // VALIDAR ARCHIVO
        // ----------------------------------

        if (!archivo) {

            toastr.warning('Selecciona un archivo.');

            inputDocumentoArchivo?.focus();

            return;
        }

        // ----------------------------------
        // OBTENER COTIZACIÓN
        // ----------------------------------

        const registroId = window.currentCotizacion?.id;

        if (!registroId) {

            toastr.error('No se encontró la cotización.');

            return;
        }

        // ----------------------------------
        // FORM DATA
        // ----------------------------------

        const formData = new FormData();

        formData.append('modulo', 'cotizaciones');
        formData.append('registro_id', registroId);
        formData.append('tipo', tipo);
        formData.append('archivo', archivo);

        // ----------------------------------
        // ESTADO BOTÓN
        // ----------------------------------

        const textoOriginal = btnConfirmarDocumento.innerHTML;

        btnConfirmarDocumento.disabled = true;

        btnConfirmarDocumento.innerHTML = `
            <span
                class="spinner-border spinner-border-sm me-1"
                role="status">
            </span>
            Enviando...
        `;

        // ----------------------------------
        // AJAX
        // ----------------------------------

        try {

            const url = `${BASE_URL}cotizaciones/documentos/subir`;

            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });

            const json = await response.json();

            // ----------------------------------
            // RESPUESTA ERROR
            // ----------------------------------

            if (!json.success) {

                throw new Error(
                    json.message || 'No fue posible subir el documento.'
                );

            }

            // ----------------------------------
            // ÉXITO
            // ----------------------------------

            toastr.success(
                json.message || 'Documento enviado correctamente.'
            );

            // ----------------------------------
            // LIMPIAR FORMULARIO
            // ----------------------------------

            if (selectDocumentoTipo) {
                selectDocumentoTipo.value = '';
            }

            if (inputDocumentoArchivo) {
                inputDocumentoArchivo.value = '';
            }

            if (infoDocumentoArchivo) {

                infoDocumentoArchivo.innerHTML = '';

                infoDocumentoArchivo.classList.add('d-none');

            }

            // ----------------------------------
            // OCULTAR PANEL DE CARGA
            // ----------------------------------

            panelCargaDocumento?.classList.add('d-none');

        } catch (error) {

            console.error(error);

            toastr.error(
                error.message || 'Error al enviar el documento.'
            );

        } finally {

            // ----------------------------------
            // RESTAURAR BOTÓN
            // ----------------------------------

            btnConfirmarDocumento.disabled = false;

            btnConfirmarDocumento.innerHTML = textoOriginal;

        }

    });


    // termina aqui






    // ======================================
    // FILTRO
    // ======================================
    document
        .getElementById('table-filter')
        .addEventListener('keyup', function () {
            tabla.setFilter(function (data) {
                const texto = this.value.toLowerCase();
                return Object.values(data).some(valor =>
                    String(valor ?? '')
                        .toLowerCase()
                        .includes(texto)
                );
            }.bind(this));
        });
    // ======================================
    // EXPORTAR
    // ======================================
    document
        .getElementById('export-csv')
        .addEventListener('click', function () {
            tabla.download('csv', 'cotizaciones_2026.csv');
        });
});


// =====================================================
// DETALLE PRO DEL HISTORIAL
// =====================================================

function renderHistorialDetalle(item) {
    const container = document.getElementById('historial-detalle');

    // =========================
    // SAFE PARSE (FIX)
    // =========================
    const antes = (typeof item.datos_anteriores === 'string')
        ? JSON.parse(item.datos_anteriores || '{}')
        : (item.datos_anteriores || {});

    const despues = (typeof item.datos_nuevos === 'string')
        ? JSON.parse(item.datos_nuevos || '{}')
        : (item.datos_nuevos || {});

    const fecha = item.fecha
        ? new Date(item.fecha).toLocaleString('es-MX')
        : '';

    let cambiosHTML = '';

    Object.keys(despues).forEach(key => {

        const oldVal = antes?.[key] ?? '';
        const newVal = despues?.[key] ?? '';

        if (oldVal != newVal) {

            cambiosHTML += `
                <div class="mb-2 border-bottom pb-2">

                    <div class="fw-semibold text-dark">
                        ${key.toUpperCase()}
                    </div>

                    <div>
                        <span class="text-danger">🔴 ${oldVal}</span>
                        →
                        <span class="text-success">🟢 ${newVal}</span>
                    </div>

                </div>
            `;
        }
    });

    container.innerHTML = `
        <div class="border-bottom mb-3 pb-2">

            <div class="fs-5 fw-bold text-primary">
                ${item.accion}
            </div>

            <div class="text-muted small">
                👤 Modificado por: ${item.usuario ?? 'N/A'}
            </div>

            <div class="text-muted small">
                🕒 ${fecha}
            </div>

        </div>

        <div>
            ${cambiosHTML || '<div class="text-muted">Sin cambios detectados</div>'}
        </div>
    `;
}