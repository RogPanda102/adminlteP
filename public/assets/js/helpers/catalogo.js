function crearCatalogo(config) {

    const input = document.querySelector(config.input);
    const resultados = document.querySelector(config.resultados);
    const idInput = document.querySelector(config.idInput);

    if (!input || !resultados || !idInput) {
        return;
    }

    input.addEventListener('input', function () {

        idInput.value = '';

    });

    const autocomplete = crearAutocomplete({

        input: config.input,
        resultados: config.resultados,
        url: 'cotizaciones/buscarCatalogoAjax',
        campo: config.campo,

        onSelect: function (item) {

            input.value = item.nombre;
            idInput.value = item.id;

            if (config.onSelect) {
                config.onSelect(item);
            }

        },

        onEmpty: function (texto) {

            if (config.onEmpty) {
                config.onEmpty(texto);
            }

        }

    });

    return {
        mostrarTodos: autocomplete.mostrarTodos,

        seleccionar: function (item) {

            input.value = item.nombre;
            idInput.value = item.id;

        }
    };

}