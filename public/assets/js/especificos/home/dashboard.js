document.addEventListener('DOMContentLoaded', () => {

    const btnAnio =
        document.getElementById('btn-anio');

    const descripcion =
        document.getElementById('descripcion-anio');

    const enlaces =
        document.querySelectorAll('.anio-selector');

    enlaces.forEach(enlace => {

        enlace.addEventListener('click', async (e) => {

            e.preventDefault();

            const anio =
                enlace.dataset.anio;

            btnAnio.textContent = anio;

            descripcion.textContent =
                `Estadísticas generales del año ${anio}.`;

            try {

                const respuesta =
                    await fetch(
                        `${BASE_URL}dashboard/estadisticas?anio=${anio}`
                    );

                const datos =
                    await respuesta.json();

                document.getElementById(
                    'total_cotizaciones'
                ).textContent =
                    datos.total_cotizaciones;

                document.getElementById(
                    'total_enviadas'
                ).textContent =
                    datos.total_enviadas;

                document.getElementById(
                    'total_respaldo'
                ).textContent =
                    datos.total_respaldo;

                document.getElementById(
                    'total_reenviar'
                ).textContent =
                    datos.total_reenviar;

            } catch (error) {

                console.error(
                    'Error obteniendo estadísticas:',
                    error
                );

            }

        });

    });

});