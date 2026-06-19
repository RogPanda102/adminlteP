let tabla;

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
    // OFFCANVAS DETALLE
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

        // GENERAL
        document.getElementById('det-req').textContent = data.req || '';
        document.getElementById('det-folio').textContent = data.folio || '';
        document.getElementById('det-elaboro').textContent = data.elaboro || '';
        document.getElementById('det-partida').textContent = data.partida || '';
        document.getElementById('det-analista').textContent = data.analista || '';

        // PAGOS
        document.getElementById('det-total').textContent = data.total || '';
        document.getElementById('det-pago').textContent = data.pago || '';
        document.getElementById('det-dia-pago').textContent = data.dia_pago || '';

        // DEPENDENCIA
        document.getElementById('det-dependencia').textContent = data.dependencia || '';

        // FECHAS
        document.getElementById('det-fecha-elaboracion').textContent = data.fecha_elaboracion || '';
        document.getElementById('det-fecha-inicio').textContent = data.fecha_inicio_entrega || '';
        document.getElementById('det-fecha-fin').textContent = data.fecha_fin_entrega || '';

        bootstrap.Offcanvas.getOrCreateInstance(
            document.getElementById('offcanvasDetalleAdjudicacion')
        ).show();
    });

    // =====================================================
    // FILTRO
    // =====================================================

    document.getElementById('table-filter')
        .addEventListener('keyup', function () {

            const value = this.value;

            tabla.setFilter([
                { field: 'folio', type: 'like', value },
                { field: 'req', type: 'like', value },
                { field: 'elaboro', type: 'like', value }
            ]);
        });

    // =====================================================
    // EXPORTAR
    // =====================================================

    document.getElementById('export-csv')
        .addEventListener('click', function () {

            tabla.download('csv', 'adjudicados_2026.csv');
        });

    // =====================================================
    // EDITAR MODAL
    // =====================================================

    const btnEditar = document.getElementById('btn-editar');

    if (btnEditar) {

        btnEditar.addEventListener('click', function () {

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

            const modal = new bootstrap.Modal(
                document.getElementById('modalEditarAdjudicacion')
            );

            modal.show();

            setTimeout(() => {

                configurarControlPago({
                    pago: '#edit-pago',
                    total: '#edit-total',
                    diaPago: '#edit-dia_pago',
                    resetOnPendiente: true
                });

            }, 50);
        });
    }

    // =====================================================
    // COLLAPSE TIPO SAP (SOLO 1 ABIERTO)
    // =====================================================

    document.addEventListener('click', (e) => {

        const header = e.target.closest('.section-toggle');
        if (!header) return;

        const target = document.querySelector(header.dataset.target);
        if (!target) return;

        const all = document.querySelectorAll('.collapse');

        all.forEach(el => {
            if (el !== target) {
                bootstrap.Collapse.getOrCreateInstance(el).hide();
            }
        });

        bootstrap.Collapse.getOrCreateInstance(target).toggle();
    });

    // =====================================================
    // GUARDAR AJAX
    // =====================================================

    const form = document.getElementById('formEditarAdjudicacion');

    if (form) {

        form.addEventListener('submit', function (e) {

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
    }

});