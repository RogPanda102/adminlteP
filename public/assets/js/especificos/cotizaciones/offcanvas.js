document.addEventListener('DOMContentLoaded', function () {
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

            document.getElementById('edit-analista_id').value =
                data.analista_id || '';

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

            document.getElementById('edit-analista').value =
                data.analista || '';

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

                dependencia: document.getElementById('edit-dependencia').value,

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