document.addEventListener('DOMContentLoaded', () => {

    // =====================================================
    // AUTOCOMPLETE COTIZACIONES
    // =====================================================

    const buscador = document.getElementById('buscar-cotizacion');

    const sugerencias = document.getElementById('resultados-cotizacion');

    if (buscador && sugerencias) {

        buscador.addEventListener('keyup', async () => {

            const texto = buscador.value.trim();

            if (texto.length < 2) {
                sugerencias.style.display = 'none';
                return;
            }

            const respuesta = await fetch(
                BASE_URL +
                'cotizaciones/buscar?q=' +
                encodeURIComponent(texto)
            );

            const datos = await respuesta.json();

            sugerencias.innerHTML = '';

            if (!datos.length) {
                sugerencias.style.display = 'none';
                return;
            }

            datos.forEach(item => {

                const opcion = document.createElement('a');

                opcion.href = '#';

                opcion.className =
                    'list-group-item list-group-item-action';

                opcion.innerHTML =
                    `<strong>${item.req}</strong>
                    <br>
                    <small>N° ${item.folio}</small>`;

                opcion.addEventListener('click', e => {

                    e.preventDefault();

                    document.getElementById('cotizacion_id').value =
                        item.id ?? '';

                    document.getElementById('req').value =
                        item.req ?? '';

                    document.getElementById('folio').value =
                        item.folio ?? '';

                    document.getElementById('elaboro').value =
                        item.elaboro ?? '';

                    document.getElementById('partida').value =
                        item.partida ?? '';

                    document.getElementById('analista').value =
                        item.analista ?? '';

                    document.getElementById('analista_id').value =
                        item.analista_id ?? '';
                        

                    buscador.value =
                        item.req + ' - ' + item.folio;

                    sugerencias.style.display = 'none';
                });

                sugerencias.appendChild(opcion);
            });

            sugerencias.style.display = 'block';
        });

        document.addEventListener('click', e => {

            if (
                !buscador.contains(e.target) &&
                !sugerencias.contains(e.target)
            ) {
                sugerencias.style.display = 'none';
            }

        });
    }


    // =====================================================
    // AUTOCOMPLETE ANALISTA
    // =====================================================

    crearAutocomplete({
        input: '#analista',
        resultados: '#lista-analista',
        url: 'cotizaciones/buscarCatalogoAjax',
        campo: 'analista',
        onSelect: function (item) {

            const campoId =
                document.getElementById('analista_id');

            if (campoId) {
                campoId.value = item.id;
            }

        }
    });


    const analista =
        document.getElementById('analista');

    if (analista) {

        analista.addEventListener('input', function () {

            const campoId =
                document.getElementById('analista_id');

            if (campoId) {
                campoId.value = '';
            }

        });

    }


    // =====================================================
    // AUTOCOMPLETE PROVEEDOR
    // =====================================================

    crearAutocomplete({
        input: '#proveedor',
        resultados: '#lista-proveedor',
        url: 'cotizaciones/buscarCatalogoAjax',
        campo: 'proveedor'
    });


    // =====================================================
    // AUTOCOMPLETE DEPENDENCIA
    // =====================================================

    crearAutocomplete({
        input: '#dependencia',
        resultados: '#lista-dependencia',
        url: 'cotizaciones/buscarCatalogoAjax',
        campo: 'dependencia'
    });


});


// =====================================================
// CONTROL ESTATUS DE PAGO
// =====================================================

configurarControlPago({
    pago: '#pago',
    total: '#total',
    diaPago: '#dia_pago'
});