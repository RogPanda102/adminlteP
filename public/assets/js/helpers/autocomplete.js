function crearAutocomplete(config) {

    const input =
        document.querySelector(config.input);

    const resultados =
        document.querySelector(config.resultados);

    if (!input || !resultados) {
        return;
    }

    input.addEventListener('keyup', async () => {

        const texto =
            input.value.trim();

        if (texto.length < 2) {

            resultados.style.display =
                'none';

            return;
        }

        const respuesta = await fetch(

            BASE_URL +
            config.url +
            '?campo=' +
            encodeURIComponent(config.campo) +
            '&q=' +
            encodeURIComponent(texto)

        );

        const datos =
            await respuesta.json();

        resultados.innerHTML = '';

        if (!datos.length) {

            resultados.style.display =
                'none';

            return;
        }

        datos.forEach(item => {

            const opcion =
                document.createElement('a');

            opcion.href = '#';

            opcion.className =
                'list-group-item list-group-item-action';

            opcion.textContent =
                item[config.campo];

            opcion.addEventListener(
                'click',
                e => {

                    e.preventDefault();

                    input.value =
                        item[config.campo];

                    resultados.style.display =
                        'none';

                    if (config.onSelect) {
                        config.onSelect(item);
                    }
                }
            );

            resultados.appendChild(opcion);

        });

        resultados.style.display =
            'block';

    });

    document.addEventListener(
        'click',
        e => {

            if (
                !input.contains(e.target) &&
                !resultados.contains(e.target)
            ) {

                resultados.style.display =
                    'none';
            }
        }
    );
}
