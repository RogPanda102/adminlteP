document.addEventListener('DOMContentLoaded', function () {

    crearAutocomplete({
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

    crearAutocomplete({
        input: '#proveedor',
        resultados: '#lista-proveedor',
        url: 'cotizaciones/buscarCatalogoAjax',
        campo: 'proveedor'
    });

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

        // Colocar nombre en el input
        document.getElementById("analista").value = json.nombre;

        // Guardar el id oculto
        document.getElementById("analista_id").value = json.id;

        // Limpiar formulario
        document.getElementById("formNuevoAnalista").reset();

        // Cerrar modal
        modalNuevoAnalista.hide();

    });


    

});

