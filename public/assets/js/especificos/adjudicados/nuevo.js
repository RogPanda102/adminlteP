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
    // GUARDAR DEPENDENCIA AJAX
    // =====================================================

    document.getElementById("formNuevaDependencia")
        .addEventListener("submit", async function (e) {

            e.preventDefault();

            const formData = new FormData();

            formData.append(
                "nombre",
                document.getElementById("nuevo_dependencia").value
            );

            formData.append(
                "descripcion",
                document.getElementById("nuevo_descripcion_dependencia").value
            );

            formData.append(
                "ubicacion",
                document.getElementById("nuevo_ubicacion_dependencia").value
            );

            const respuesta = await fetch(
                BASE_URL + "dependencias/guardarAjax",
                {
                    method: "POST",
                    body: formData
                }
            );

            const json = await respuesta.json();

            if (!json.ok) {

                alert(
                    json.mensaje ||
                    "No se pudo registrar la dependencia"
                );

                return;
            }

            catalogoDependencia.seleccionar({
                id: json.id,
                nombre: json.nombre
            });

            document
                .getElementById("formNuevaDependencia")
                .reset();

            modalNuevaDependencia.hide();

        });

    

    // =====================================================
    // AUTOCOMPLETE ANALISTA
    // =====================================================

    const modalNuevoAnalista = new bootstrap.Modal(
        document.getElementById("modalNuevoAnalista")
    );

    const catalogoAnalista = crearCatalogo({
        campo: 'analista',
        input: '#analista',
        resultados: '#lista-analista',
        idInput: '#analista_id',
        onEmpty: function (texto) {

            document.getElementById('nuevo_nombre').value = texto;

            modalNuevoAnalista.show();

        }
    });
    
    // =====================================================
    // AUTOCOMPLETE DEPENDENCIA
    // =====================================================
    const modalNuevaDependencia = new bootstrap.Modal(
        document.getElementById("modalNuevaDependencia")
    );

    const catalogoDependencia = crearCatalogo({
        campo: 'dependencia',
        input: '#dependencia',
        resultados: '#lista-dependencia',
        idInput: '#dependencia_id',
        onEmpty: function (texto) {
            document.getElementById('nuevo_dependencia').value = texto;
            modalNuevaDependencia.show();
        }
    });

    const btnDependencia =
        document.getElementById('btnDependencia');

    if (btnDependencia) {

        btnDependencia.addEventListener('click', function () {

            catalogoDependencia.mostrarTodos();

        });

    }


});


// =====================================================
// CONTROL ESTATUS DE PAGO
// =====================================================

configurarControlPago({
    pago: '#pago',
    total: '#total',
    diaPago: '#dia_pago'
});