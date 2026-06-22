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

                sugerencias.style.display = 'none';

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


                sugerencias.style.display = 'none';

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


});





// =====================================================
// AUTOCOMPLETE DEPENDENCIA
// =====================================================


crearAutocomplete({


    input: '#dependencia',


    resultados: '#resultados-dependencia',


    url: 'servicios/buscar-dependencia',


    campo: 'dependencia'


});