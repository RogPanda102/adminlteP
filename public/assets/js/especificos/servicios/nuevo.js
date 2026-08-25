document.addEventListener('DOMContentLoaded', () => {

    // =====================================================
    // AUTOCOMPLETE SERVICIOS POR REQ
    // =====================================================
    const buscador =
        document.getElementById('buscar-servicio');

    const sugerencias =
        document.getElementById('resultados-servicio');

    if (buscador && sugerencias) {

        buscador.addEventListener('keyup', async () => {

            const texto =
                buscador.value.trim();

            if (texto.length < 2) {

                sugerencias.style.display =
                    'none';

                return;

            }

            const respuesta = await fetch(

                BASE_URL +
                'servicios/buscar?q=' +
                encodeURIComponent(texto)

            );

            const datos =
                await respuesta.json();

            sugerencias.innerHTML = '';

            if (!datos.length) {

                sugerencias.style.display =
                    'none';

                return;

            }

            datos.forEach(item => {

                const opcion =
                    document.createElement('a');

                opcion.href = '#';

                opcion.className =
                    'list-group-item list-group-item-action';

                opcion.innerHTML =
                    `<strong>${item.req}</strong>
                    <br>
                    <small>
                        Folio: ${item.folio ?? ''}
                    </small>`;

                opcion.addEventListener('click', e => {

                    e.preventDefault();

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

                    document.getElementById('tipo_servicio_id').value =
                        item.tipo_servicio_id ?? '';

                    document.getElementById('adjudicado_id').value =
                        item.id ?? '';

                    buscador.value =
                        item.req + ' - ' + item.folio;

                    sugerencias.style.display =
                        'none';

                });

                sugerencias.appendChild(opcion);

            });

            sugerencias.style.display =
                'block';

        });

        document.addEventListener('click', e => {

            if (

                !buscador.contains(e.target) &&
                !sugerencias.contains(e.target)

            ) {

                sugerencias.style.display =
                    'none';

            }

        });

    }


    // =====================================================
    // AUTOCOMPLETE TIPO DE SERVICIO
    // =====================================================

    const autocompleteTipoServicio = crearAutocomplete({

        input: '#tipo_servicio',

        resultados: '#resultados-tipo-servicio',

        url: 'servicios/buscar-tipo-servicio',

        campo: 'tipo_servicio',

        onSelect: function (item) {

            document.getElementById('tipo_servicio_id').value =
                item.id;

        }

    });

    // =====================================================
    // BOTÓN ▼ MOSTRAR CATÁLOGO COMPLETO
    // =====================================================

    document
        .getElementById('btn-tipo-servicio')
        .addEventListener('click', () => {

            autocompleteTipoServicio.mostrarTodos();

        });

});
    // =====================================================
    // CALCULAR FECHA DE FINALIZACIÓN
    // =====================================================

    const inicio = document.getElementById('inicio');
    const cantidad = document.getElementById('tiempo_cantidad');
    const unidad = document.getElementById('tiempo_unidad');
    const finalizacion = document.getElementById('finalizacion');

    function calcularFechaFinalizacion() {

        // -----------------------------------------
        // Obtener valores
        // -----------------------------------------

        const fechaInicio = inicio.value;
        const valorCantidad = parseInt(cantidad.value, 10);
        const valorUnidad = unidad.value;

        // -----------------------------------------
        // Si faltan datos, limpiar finalización
        // -----------------------------------------

        if (
            !fechaInicio ||
            !valorCantidad ||
            !valorUnidad
        ) {

            finalizacion.value = '';

            return;
        }

        // -----------------------------------------
        // Crear fecha evitando problemas de zona horaria
        // -----------------------------------------

        const [anio, mes, dia] =
            fechaInicio.split('-').map(Number);

        const fecha = new Date(
            anio,
            mes - 1,
            dia
        );

        // -----------------------------------------
        // Calcular duración
        // -----------------------------------------

        switch (valorUnidad) {

            case 'dias':

                fecha.setDate(
                    fecha.getDate() + valorCantidad
                );

                break;

            case 'meses': {

                const diaOriginal =
                    fecha.getDate();

                fecha.setDate(1);

                fecha.setMonth(
                    fecha.getMonth() + valorCantidad
                );

                // Último día del mes resultante
                const ultimoDia =
                    new Date(
                        fecha.getFullYear(),
                        fecha.getMonth() + 1,
                        0
                    ).getDate();

                fecha.setDate(
                    Math.min(
                        diaOriginal,
                        ultimoDia
                    )
                );

                break;
            }

            case 'años': {

                const diaOriginal =
                    fecha.getDate();

                const mesOriginal =
                    fecha.getMonth();

                fecha.setDate(1);

                fecha.setFullYear(
                    fecha.getFullYear() + valorCantidad
                );

                fecha.setMonth(
                    mesOriginal
                );

                // Último día del mes resultante
                const ultimoDia =
                    new Date(
                        fecha.getFullYear(),
                        fecha.getMonth() + 1,
                        0
                    ).getDate();

                fecha.setDate(
                    Math.min(
                        diaOriginal,
                        ultimoDia
                    )
                );

                break;
            }

            default:

                finalizacion.value = '';

                return;
        }

        // -----------------------------------------
        // Formatear YYYY-MM-DD
        // -----------------------------------------

        const anioFinal =
            fecha.getFullYear();

        const mesFinal =
            String(
                fecha.getMonth() + 1
            ).padStart(2, '0');

        const diaFinal =
            String(
                fecha.getDate()
            ).padStart(2, '0');

        finalizacion.value =
            `${anioFinal}-${mesFinal}-${diaFinal}`;
    }

    // -----------------------------------------
    // Recalcular cuando cambie algún dato
    // -----------------------------------------

    inicio.addEventListener(
        'change',
        calcularFechaFinalizacion
    );

    cantidad.addEventListener(
        'input',
        calcularFechaFinalizacion
    );

    unidad.addEventListener(
        'change',
        calcularFechaFinalizacion
    );

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
    // =====================================================
    // GUARDAR DEPENDENCIA AJAX
    // =====================================================
    document.getElementById("formNuevaDependencia").addEventListener("submit", async function (e) {

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
    // GUARDAR ANALISTA AJAX/BTN
    // =====================================================
    const modalNuevoAnalista = new bootstrap.Modal(
        document.getElementById("modalNuevoAnalista")
    );
    document.getElementById("btnNuevoAnalista").addEventListener("click", () => { modalNuevoAnalista.show(); });
    document.getElementById("formNuevoAnalista").addEventListener("submit", async function (e) {

        e.preventDefault();

        const formData = new FormData();

        formData.append(
            "nombre",
            document.getElementById("nuevo_nombre").value
        );

        formData.append(
            "apellido_paterno",
            document.getElementById("nuevo_apellido_paterno").value
        );

        formData.append(
            "apellido_materno",
            document.getElementById("nuevo_apellido_materno").value
        );

        formData.append(
            "telefono",
            document.getElementById("nuevo_telefono").value
        );

        const respuesta = await fetch(
            BASE_URL + "contactos/guardarAnalistaAjax",
            {
                method: "POST",
                body: formData
            }
        );

        const json = await respuesta.json();

        if (!json.ok) {

            alert(json.mensaje);
            return;

        }

        catalogoAnalista.seleccionar({
            id: json.id,
            nombre: json.nombre
        });

        // Limpiar formulario
        document.getElementById("formNuevoAnalista").reset();

        // Cerrar modal
        modalNuevoAnalista.hide();

    });

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
    // BOTÓN MOSTRAR TODOS LOS ANALISTAS
    // =====================================================

    const btnAnalista =
        document.getElementById('btnAnalista');

    if (btnAnalista) {

        btnAnalista.addEventListener('click', function () {

            catalogoAnalista.mostrarTodos();

        });

    }

});
