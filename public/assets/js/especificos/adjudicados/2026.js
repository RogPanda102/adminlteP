let tabla;
let historialAbierto = false;

document.addEventListener('DOMContentLoaded', function () {

    // =====================================================
    // TABLA ADJUDICADOS
    // =====================================================

    tabla = new Tabulator('#tabla-adjudicados', {

        layout: 'fitColumns',
        pagination: true,
        paginationSize: 10,

        rowFormatter: function (row) {

            const data = row.getData();
            const estado = (data.pago || '').toLowerCase();

            if (estado === 'pagado') {
                row.getElement().classList.add('table-success');
            } else if (estado === 'pendiente') {
                row.getElement().classList.add('table-light');
            } else if (estado === 'cancelado') {
                row.getElement().classList.add('table-danger');
            }
        },

        columns: [
            { title: 'REQ', field: 'req' },
            { title: 'Folio', field: 'folio' },
            { title: 'Elaboró', field: 'elaboro' },
            { title: 'Partida', field: 'partida' },

            {
                title: 'Fecha Elaboración',
                field: 'fecha_elaboracion',
                formatter: cell => {

                    const v = cell.getValue();
                    if (!v) return '';

                    const f = new Date(v + 'T00:00:00');

                    return f.toLocaleDateString('es-MX', {
                        day: '2-digit',
                        month: 'long',
                        year: 'numeric'
                    });
                }
            },

            {
                title: 'Total',
                field: 'total',
                hozAlign: 'right',
                formatter: cell => {

                    const v = parseFloat(cell.getValue() || 0);

                    return v.toLocaleString('es-MX', {
                        style: 'currency',
                        currency: 'MXN'
                    });
                }
            }
        ],

        data: window.adjudicados || []
    });

    // =====================================================
    // ROW CLICK
    // =====================================================

    tabla.on('rowClick', function (e, row) {

        const data = row.getData();
        window.currentAdjudicacion = data;

        document.getElementById('erp-folio-title').textContent = data.folio || '';

        const status = document.getElementById('erp-status');
        const estado = (data.pago || 'pendiente').toLowerCase();

        status.className = 'badge';

        if (estado === 'pagado') status.classList.add('bg-success');
        else if (estado === 'parcial') status.classList.add('bg-warning', 'text-dark');
        else if (estado === 'cancelado') status.classList.add('bg-danger');
        else status.classList.add('bg-secondary');

        status.textContent = estado;

        document.getElementById('det-req').textContent = data.req || '';
        document.getElementById('det-folio').textContent = data.folio || '';
        document.getElementById('det-elaboro').textContent = data.elaboro || '';
        document.getElementById('det-partida').textContent = data.partida || '';
        document.getElementById('det-analista').textContent = data.analista || '';
        document.getElementById('det-total').textContent = data.total || '';
        document.getElementById('det-pago').textContent = data.pago || '';
        document.getElementById('det-dia-pago').textContent = data.dia_pago || '';
        document.getElementById('det-dependencia').textContent = data.dependencia || '';

        document.getElementById('det-fecha-elaboracion').textContent = data.fecha_elaboracion || '';
        document.getElementById('det-fecha-inicio').textContent = data.fecha_inicio_entrega || '';
        document.getElementById('det-fecha-fin').textContent = data.fecha_fin_entrega || '';

        bootstrap.Offcanvas.getOrCreateInstance(
            document.getElementById('offcanvasDetalleAdjudicacion')
        ).show();

        cerrarHistorial();
    });


        // =====================================================
    // EDITAR MODAL
    // =====================================================

    document.getElementById('btn-editar')?.addEventListener('click', function () {

        const data = window.currentAdjudicacion;
        if (!data) return;

        document.getElementById('edit-id').value = data.id || '';
        document.getElementById('edit-req').value = data.req || '';
        document.getElementById('edit-folio').value = data.folio || '';
        document.getElementById('edit-elaboro').value = data.elaboro || '';
        document.getElementById('edit-partida').value = data.partida || '';
        document.getElementById('edit-analista').value = data.analista || '';

        document.getElementById('edit-fecha_elaboracion').value = data.fecha_elaboracion || '';
        document.getElementById('edit-fecha_inicio_entrega').value = data.fecha_inicio_entrega || '';
        document.getElementById('edit-fecha_fin_entrega').value = data.fecha_fin_entrega || '';
        document.getElementById('edit-dia_pago').value = data.dia_pago || '';

        document.getElementById('edit-total').value = data.total || '';
        document.getElementById('edit-pago').value = data.pago || 'pendiente';
        document.getElementById('edit-dependencia').value = data.dependencia || '';

        new bootstrap.Modal(
            document.getElementById('modalEditarAdjudicacion')
        ).show();

        setTimeout(() => {

            configurarControlPago({
                pago: '#edit-pago',
                total: '#edit-total',
                diaPago: '#edit-dia_pago',
                resetOnPendiente: true
            });

        }, 50);
    });

    // =====================================================
    // COLLAPSE SAP
    // =====================================================

    document.addEventListener('click', (e) => {

        const header = e.target.closest('.section-toggle');
        if (!header) return;

        const target = document.querySelector(header.dataset.target);
        if (!target) return;

        document.querySelectorAll('.collapse').forEach(el => {
            if (el !== target) {
                bootstrap.Collapse.getOrCreateInstance(el).hide();
            }
        });

        bootstrap.Collapse.getOrCreateInstance(target).toggle();
    });

    // =====================================================
    // GUARDAR AJAX
    // =====================================================

    document.getElementById('formEditarAdjudicacion')?.addEventListener('submit', function (e) {

        e.preventDefault();

        const payload = {

            id: document.getElementById('edit-id').value,
            req: document.getElementById('edit-req').value,
            folio: document.getElementById('edit-folio').value,
            elaboro: document.getElementById('edit-elaboro').value,
            partida: document.getElementById('edit-partida').value,
            analista: document.getElementById('edit-analista').value,

            fecha_elaboracion: document.getElementById('edit-fecha_elaboracion').value,
            fecha_inicio_entrega: document.getElementById('edit-fecha_inicio_entrega').value,
            fecha_fin_entrega: document.getElementById('edit-fecha_fin_entrega').value,

            total: document.getElementById('edit-total').value,
            dia_pago: document.getElementById('edit-dia_pago').value,
            pago: document.getElementById('edit-pago').value,

            dependencia: document.getElementById('edit-dependencia').value
        };

        fetch(BASE_URL + 'adjudicados/update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
            .then(r => r.json())
            .then(data => {

                if (data.success) {

                    const fila = tabla.getRow(payload.id);
                    if (fila) fila.update(payload);

                    bootstrap.Modal.getInstance(
                        document.getElementById('modalEditarAdjudicacion')
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
    // HISTORIAL
    // =====================================================

    const btnHistorial = document.getElementById('btn-historial');

    btnHistorial?.addEventListener('click', async function () {

        const id = window.currentAdjudicacion?.id;
        if (!id) return;

        const panelHistorial = document.getElementById('erp-panel-historial');
        const panelDetalle = document.getElementById('erp-panel-detalle');

        if (!panelHistorial || !panelDetalle) return;

        if (!historialAbierto) {

            historialAbierto = true;

            panelDetalle.style.width = '60%';
            panelHistorial.style.width = '60%';

            document.getElementById('historial-items').innerHTML =
                `<small class="text-muted">Cargando...</small>`;

            document.getElementById('historial-detalle').innerHTML =
                `<div class="text-muted">Selecciona un cambio</div>`;

            try {

                const res = await fetch(`${BASE_URL}historial/adjudicados&id=${id}`);
                const json = await res.json();

                if (!json.success) throw new Error(json.message);

                const data = json.data || [];

                window.historialData = data;

                const itemsContainer = document.getElementById('historial-items');

                if (!data.length) {
                    itemsContainer.innerHTML =
                        `<div class="text-muted">Sin cambios registrados</div>`;
                    return;
                }

                itemsContainer.innerHTML = data.map((item, index) => {

                    const fecha = item.fecha
                        ? new Date(item.fecha).toLocaleString('es-MX')
                        : '';

                    return `
                        <div class="hist-item border-bottom py-2 px-2"
                             data-index="${index}"
                             style="cursor:pointer">

                            <div class="fw-bold text-primary">
                                ${item.accion}
                            </div>

                            <small class="text-muted">
                                ${fecha}
                            </small>

                        </div>
                    `;
                }).join('');

                // CLICK EN ITEMS
                document.querySelectorAll('.hist-item').forEach(el => {

                    el.addEventListener('click', function () {

                        const index = this.dataset.index;
                        const item = window.historialData[index];

                        renderHistorialDetalle(item);
                    });
                });

            } catch (err) {

                document.getElementById('historial-items').innerHTML =
                    `<div class="text-danger">Error cargando historial</div>`;
            }

            return;
        }

        historialAbierto = false;
        cerrarHistorial();
    });

});

// =====================================================
// HELPERS
// =====================================================

function cerrarHistorial() {

    const panelHistorial = document.getElementById('erp-panel-historial');
    const panelDetalle = document.getElementById('erp-panel-detalle');

    if (panelHistorial) panelHistorial.style.width = '0%';
    if (panelDetalle) panelDetalle.style.width = '100%';

    historialAbierto = false;
}

// =====================================================
// DETALLE PRO DEL HISTORIAL
// =====================================================

function renderHistorialDetalle(item)
{
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
                👤 Usuario ID: ${item.usuario_id ?? 'N/A'}
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