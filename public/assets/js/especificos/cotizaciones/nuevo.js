document.addEventListener('DOMContentLoaded', function () {

    const autocompleteAnalista = crearAutocomplete({
        input: '#analista',
        resultados: '#lista-analista',
        url: 'cotizaciones/buscarCatalogoAjax',
        campo: 'analista',
        onSelect: function (item) {
            document.getElementById('analista_id').value = item.id;
        }
    });

    document.getElementById('analista').addEventListener('input', function () {
        document.getElementById('analista_id').value = '';
    });

    const autocompleteProveedor = crearAutocomplete({
        input: '#proveedor',
        resultados: '#lista-proveedor',
        url: 'cotizaciones/buscarCatalogoAjax',
        campo: 'proveedor',

        onSelect: function (item) {
            document.getElementById('proveedor_id').value = item.id;
        },
        onEmpty: function (texto) 
        {
            document.getElementById('nuevo_proveedor').value = texto;
            modalNuevoProveedor.show();
        }
    });

    const modalNuevoAnalista = new bootstrap.Modal(
        document.getElementById("modalNuevoAnalista")
    );

    const modalNuevoProveedor = new bootstrap.Modal(
        document.getElementById("modalNuevoProveedor")
    );
    document.getElementById("formNuevoProveedor").addEventListener("submit", async function (e) {

        e.preventDefault();

        const formData = new FormData();

        formData.append(
            "proveedor",
            document.getElementById("nuevo_proveedor").value
        );

        formData.append(
            "servicios",
            document.getElementById("nuevo_servicios").value
        );

        formData.append(
            "ubicacion",
            document.getElementById("nuevo_ubicacion").value
        );

        formData.append(
            "contacto",
            document.getElementById("nuevo_contacto").value
        );

        formData.append(
            "telefono",
            document.getElementById("nuevo_telefono_proveedor").value
        );

        formData.append(
            "email",
            document.getElementById("nuevo_email").value
        );

        formData.append(
            "enlace",
            document.getElementById("nuevo_enlace").value
        );

        const respuesta = await fetch(
            BASE_URL + "proveedores/guardarAjax",
            {
                method: "POST",
                body: formData
            }
        );

        const json = await respuesta.json();

        if (!json.ok) {

            alert(json.mensaje || 'No se pudo registrar el proveedor');
            return;

        }

        // Colocar proveedor en el formulario principal
        document.getElementById('proveedor').value = json.nombre;

        // Guardar ID del proveedor
        document.getElementById('proveedor_id').value = json.id;

        // Limpiar formulario del modal
        document.getElementById('formNuevoProveedor').reset();

        // Cerrar modal
        modalNuevoProveedor.hide();

    });

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

        // Colocar nombre en el input
        document.getElementById("analista").value = json.nombre;

        // Guardar el id oculto
        document.getElementById("analista_id").value = json.id;

        // Limpiar formulario
        document.getElementById("formNuevoAnalista").reset();

        // Cerrar modal
        modalNuevoAnalista.hide();

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

    // =====================================================
    // BOTÓN MOSTRAR TODOS LOS ANALISTAS
    // =====================================================

    const btnAnalista =
        document.getElementById('btnAnalista');

    if (btnAnalista) {

        btnAnalista.addEventListener('click', function () {

            autocompleteAnalista.mostrarTodos();

        });

    }

    // =====================================================
    // BOTÓN MOSTRAR TODOS LOS PROVEEDORES
    // =====================================================

    const btnProveedor =
        document.getElementById('btnProveedor');

    if (btnProveedor) {

        btnProveedor.addEventListener('click', function () {

            autocompleteProveedor.mostrarTodos();

        });

    }


    

});

