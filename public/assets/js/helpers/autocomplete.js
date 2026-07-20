function crearAutocomplete(config) {

    const input =
        document.querySelector(config.input);

    const resultados =
        document.querySelector(config.resultados);

    if (!input || !resultados) {
        return;
    }


    input.addEventListener('keyup', async () => {

        const texto = input.value.trim();


        if (texto.length < 2) {

            resultados.style.display = 'none';
            resultados.innerHTML = '';

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


        const datos = await respuesta.json();


        resultados.innerHTML = '';



        // =========================
        // SIN RESULTADOS
        // =========================
        if (!datos.length) {


            const sinResultados =
                document.createElement('div');


            sinResultados.className =
                'list-group-item text-muted';


            sinResultados.innerHTML = `
                <i class="bi bi-search me-2"></i>
                Sin resultados
            `;


            resultados.appendChild(sinResultados);



            if (config.onEmpty) {


                const crear =
                    document.createElement('a');


                crear.href = '#';


                crear.className =
                    'list-group-item list-group-item-action text-primary fw-semibold';


                crear.innerHTML = `
                    <i class="bi bi-plus-circle me-2"></i>
                    Crear "${texto}"
                `;


                crear.addEventListener('click', e => {

                    e.preventDefault();

                    config.onEmpty(texto);

                    resultados.style.display = 'none';

                });


                resultados.appendChild(crear);

            }


            resultados.style.display = 'block';

            return;
        }




        // =========================
        // RESULTADOS
        // =========================
        datos.forEach(item => {


            const opcion =
                document.createElement('a');


            opcion.href = '#';


            opcion.className =
                'list-group-item list-group-item-action';


            opcion.textContent =
                item.nombre;



            opcion.addEventListener('click', e => {


                e.preventDefault();


                input.value =
                    item.nombre;


                resultados.style.display =
                    'none';



                if (config.onSelect) {

                    config.onSelect(item);

                }


            });


            resultados.appendChild(opcion);


        });



        resultados.style.display = 'block';


    });



    document.addEventListener('click', e => {


        if (
            !input.contains(e.target) &&
            !resultados.contains(e.target)
        ) {


            resultados.style.display = 'none';


        }


    });

}