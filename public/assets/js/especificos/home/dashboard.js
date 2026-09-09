document.addEventListener('DOMContentLoaded', () => {

    // ======================================================
    // ELEMENTOS
    // ======================================================

    const btnAnio =
        document.getElementById('btn-anio');

    const btnModulo =
        document.getElementById('btn-modulo');

    const descripcion =
        document.getElementById('descripcion-anio');

    const enlacesAnio =
        document.querySelectorAll('.anio-selector');

    const enlacesModulo =
        document.querySelectorAll('.modulo-selector');


    // ======================================================
    // MÓDULO ACTUAL
    // ======================================================

    let moduloActual = 'cotizaciones';


    // ======================================================
    // NOMBRES DE MÓDULOS
    // ======================================================

    const nombresModulos = {

        cotizaciones: 'Cotizaciones',

        adjudicados: 'Adjudicados',

        servicios: 'Servicios'

    };


    // ======================================================
    // CAMBIO DE AÑO
    // ======================================================

    enlacesAnio.forEach(enlace => {

        enlace.addEventListener('click', async (e) => {

            e.preventDefault();

            const anio =
                enlace.dataset.anio;

            btnAnio.textContent = anio;

            await cargarEstadisticas(
                anio,
                moduloActual
            );

        });

    });


    // ======================================================
    // CAMBIO DE MÓDULO
    // ======================================================

    enlacesModulo.forEach(enlace => {

        enlace.addEventListener('click', async (e) => {

            e.preventDefault();

            moduloActual =
                enlace.dataset.modulo;

            const nombreModulo =
                nombresModulos[moduloActual]
                ?? moduloActual;

            btnModulo.textContent =
                nombreModulo;

            const anio =
                btnAnio.textContent.trim();

            await cargarEstadisticas(
                anio,
                moduloActual
            );

        });

    });


    // ======================================================
    // CARGAR ESTADÍSTICAS
    // ======================================================

    async function cargarEstadisticas(anio, modulo) {

        descripcion.textContent =
            `Estadísticas de ${nombresModulos[modulo]} del año ${anio}.`;

        try {

            const respuesta =
                await fetch(
                    `${BASE_URL}dashboard/estadisticas?anio=${anio}&modulo=${modulo}`
                );

            if (!respuesta.ok) {

                throw new Error(
                    `HTTP ${respuesta.status}`
                );

            }

            const datos =
                await respuesta.json();

            console.log(
                'Dashboard:',
                datos
            );


            // ==================================================
            // ACTUALIZAR CARDS
            // ==================================================

            actualizarCards(
                datos,
                modulo
            );


            // ==================================================
            // ACTUALIZAR RANKING
            // ==================================================

            actualizarRankingAnalistas(
                datos,
                anio,
                modulo
            );

        } catch (error) {

            console.error(
                'Error obteniendo estadísticas:',
                error
            );

        }

    }


    // ======================================================
    // CARGAR ESTADÍSTICAS INICIALES
    // ======================================================

    const anioInicial =
        btnAnio.textContent.trim();

    cargarEstadisticas(
        anioInicial,
        moduloActual
    );

});


// ======================================================
// CARDS
// ======================================================

function actualizarCards(datos, modulo) {

    const totalCotizaciones =
        document.getElementById(
            'total_cotizaciones'
        );

    const totalEnviadas =
        document.getElementById(
            'total_enviadas'
        );

    const totalRespaldo =
        document.getElementById(
            'total_respaldo'
        );

    const totalReenviar =
        document.getElementById(
            'total_reenviar'
        );


    // ======================================================
    // COTIZACIONES
    // ======================================================

    if (modulo === 'cotizaciones') {

        totalCotizaciones.textContent =
            datos.total_cotizaciones ?? 0;

        totalEnviadas.textContent =
            datos.total_enviadas ?? 0;

        totalRespaldo.textContent =
            datos.total_respaldo ?? 0;

        totalReenviar.textContent =
            datos.total_reenviar ?? 0;

        return;
    }


    // ======================================================
    // ADJUDICADOS
    // ======================================================

    if (modulo === 'adjudicados') {

        totalCotizaciones.textContent =
            datos.total_adjudicados ?? 0;

        totalEnviadas.textContent =
            0;

        totalRespaldo.textContent =
            0;

        totalReenviar.textContent =
            0;

        return;
    }


    // ======================================================
    // SERVICIOS
    // ======================================================

    if (modulo === 'servicios') {

        totalCotizaciones.textContent =
            datos.total_servicios ?? 0;

        totalEnviadas.textContent =
            0;

        totalRespaldo.textContent =
            0;

        totalReenviar.textContent =
            0;

    }

}


// ======================================================
// RANKING ANALISTAS
// ======================================================

function actualizarRankingAnalistas(
    datos,
    anio,
    modulo
) {

    const badgeAnio =
        document.getElementById(
            'ranking-analistas-anio'
        );

    const titulo =
        document.getElementById(
            'ranking-analistas-titulo'
        );

    const tbody =
        document.getElementById(
            'ranking-analistas-body'
        );


    if (!tbody) {

        return;

    }


    // ======================================================
    // ACTUALIZAR AÑO
    // ======================================================

    if (badgeAnio) {

        badgeAnio.textContent =
            anio;

    }


    // ======================================================
    // ACTUALIZAR TÍTULO
    // ======================================================

    if (titulo) {

        titulo.textContent =
            nombresModulos[modulo] ?? modulo;

    }


    // ======================================================
    // OBTENER RANKING
    // ======================================================

    let ranking = [];


    if (modulo === 'cotizaciones') {

        ranking =
            datos.top_analistas_cotizaciones
            || [];

    }

    if (modulo === 'adjudicados') {

        ranking =
            datos.top_analistas_adjudicados
            || [];

    }

    if (modulo === 'servicios') {

        ranking =
            datos.top_analistas_servicios
            || [];

    }


    // ======================================================
    // LIMPIAR TABLA
    // ======================================================

    tbody.innerHTML = '';


    // ======================================================
    // SIN REGISTROS
    // ======================================================

    if (ranking.length === 0) {

        tbody.innerHTML = `
            <tr>
                <td colspan="3"
                    class="text-center text-muted py-4">
                    No existen registros.
                </td>
            </tr>
        `;

        return;

    }


    // ======================================================
    // GENERAR RANKING
    // ======================================================

    ranking.forEach((item, index) => {

        let posicion;

        switch (index + 1) {

            case 1:
                posicion = '🥇';
                break;

            case 2:
                posicion = '🥈';
                break;

            case 3:
                posicion = '🥉';
                break;

            default:
                posicion =
                    index + 1;

        }


        tbody.innerHTML += `
            <tr>

                <td>
                    ${posicion}
                </td>

                <td class="fw-semibold">
                    ${item.analista ?? ''}
                </td>

                <td class="text-end">

                    <span class="badge bg-primary fs-6">
                        ${item.total ?? 0}
                    </span>

                </td>

            </tr>
        `;

    });

}