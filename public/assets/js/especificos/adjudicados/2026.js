let tabla;

document.addEventListener('DOMContentLoaded', function () {

    // =====================================================
    // TABLA ADJUDICADOS ERP PRO
    // =====================================================

    tabla = new Tabulator('#tabla-adjudicados', {

        layout: 'fitColumns',

        pagination: true,
        paginationSize: 10,

        movableColumns: true,

        responsiveLayout: "collapse",


        // ==============================
        // ESTADO VISUAL DE FILA
        // ==============================

        rowFormatter: function (row) {

            const data = row.getData();
            const estado = (data.pago || '').toLowerCase();

            const el = row.getElement();


            el.classList.remove(
                'row-pagado',
                'row-pendiente',
                'row-cancelado'
            );


            if (estado === 'pagado') {

                el.classList.add('row-pagado');

            } else if (estado === 'pendiente') {

                el.classList.add('row-pendiente');

            } else if (estado === 'cancelado') {

                el.classList.add('row-cancelado');

            }

        },


        columns: [


            // ==============================
            // REQ
            // ==============================

            {
                title: "REQ",
                field: "req",
                width: 110,
                hozAlign: "center",

                formatter: function (cell) {

                    return `
                    <span class="fw-semibold">
                        ${cell.getValue() || ''}
                    </span>
                `;

                }
            },


            // ==============================
            // FOLIO
            // ==============================

            {
                title: "FOLIO",
                field: "folio",
                width: 110,
                hozAlign: "center",

                formatter: function (cell) {

                    return `
                    <span class="folio-badge">
                        ${cell.getValue() || ''}
                    </span>
                `;

                }
            },


            // ==============================
            // ELABORÓ
            // ==============================

            {
                title: "ELABORÓ",
                field: "elaboro",

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


            // ==============================
            // PARTIDA
            // ==============================

            {
                title: "PARTIDA",
                field: "partida",

                formatter: function (cell) {

                    return `

                    <span class="erp-sub">
                        ${cell.getValue() || ''}
                    </span>

                `;

                }
            },


            // ==============================
            // FECHA
            // ==============================

            {
                title: "FECHA ELABORACIÓN",
                field: "fecha_elaboracion",
                width: 170,

                formatter: function (cell) {

                    const v = cell.getValue();

                    if (!v) return "";


                    const f = new Date(v + "T00:00:00");


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


            // ==============================
            // TOTAL
            // ==============================

            {
                title: "TOTAL",
                field: "total",

                hozAlign: "right",

                width: 150,


                formatter: function (cell) {

                    const v = parseFloat(
                        cell.getValue() || 0
                    );


                    return `

                    <div class="erp-total">

                        ${v.toLocaleString(
                        'es-MX',
                        {
                            style: 'currency',
                            currency: 'MXN'
                        }
                    )}

                    </div>

                `;

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
        const folioInput = document.getElementById('edit-folio');
        let folio = folioInput.value.trim();

        // fuerza solo números y máximo 4 dígitos
        folio = folio.replace(/\D/g, '').slice(0, 4);

        // lo regresas al input (UX)
        folioInput.value = folio;


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


        const itemsContainer = document.getElementById('historial-items');

        const detalleContainer = document.getElementById('historial-detalle');


        if (!itemsContainer || !detalleContainer) return;



        // estado inicial

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
                `${BASE_URL}historial/adjudicados&id=${id}`
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

                    ? new Date(item.fecha)
                        .toLocaleString('es-MX')

                    : '';



                let badge = 'bg-primary';



                if (item.accion === 'CREATE') {

                    badge = 'bg-success';

                }


                if (item.accion === 'DELETE') {

                    badge = 'bg-danger';

                }





                return `

                <div class="hist-item p-3 mb-2 border rounded shadow-sm"

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






            document.querySelectorAll('.hist-item')

                .forEach(item => {



                    item.addEventListener('click', function () {



                        document.querySelectorAll('.hist-item')

                            .forEach(el => {

                                el.classList.remove('border-primary');

                            });



                        this.classList.add('border-primary');




                        const index = this.dataset.index;



                        const registro = window.historialData[index];



                        renderHistorialDetalle(registro);



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