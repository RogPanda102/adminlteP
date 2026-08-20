document.addEventListener('DOMContentLoaded', function () {

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

    const catalogoProveedor = crearCatalogo({
        campo: 'proveedor',
        input: '#proveedor',
        resultados: '#lista-proveedor',
        idInput: '#proveedor_id',
        onEmpty: function (texto) {

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

        catalogoProveedor.seleccionar({
            id: json.id,
            nombre: json.nombre
        });

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

        catalogoAnalista.seleccionar({
            id: json.id,
            nombre: json.nombre
        });

        // Limpiar formulario
        document.getElementById("formNuevoAnalista").reset();

        // Cerrar modal
        modalNuevoAnalista.hide();

    });

    // =====================================================
    // AUTOCOMPLETE DEPENDENCIA
    // =====================================================

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
    // BOTÓN MOSTRAR TODOS LOS ANALISTAS
    // =====================================================

    const btnAnalista =
        document.getElementById('btnAnalista');

    if (btnAnalista) {

        btnAnalista.addEventListener('click', function () {

            catalogoAnalista.mostrarTodos();

        });

    }

    // =====================================================
    // BOTÓN MOSTRAR TODOS LOS PROVEEDORES
    // =====================================================

    const btnProveedor =
        document.getElementById('btnProveedor');

    if (btnProveedor) {

        btnProveedor.addEventListener('click', function () {

            catalogoProveedor.mostrarTodos();

        });

    }

    const btnDependencia =
        document.getElementById('btnDependencia');

    if (btnDependencia) {

        btnDependencia.addEventListener('click', function () {

            catalogoDependencia.mostrarTodos();

        });

    }


    

});

