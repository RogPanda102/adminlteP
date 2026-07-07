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

                // =========================
                // CARDS
                // =========================
                document.getElementById(
                    'total_cotizaciones'
                ).textContent = datos.total_cotizaciones;

                document.getElementById(
                    'total_enviadas'
                ).textContent = datos.total_enviadas;

                document.getElementById(
                    'total_respaldo'
                ).textContent = datos.total_respaldo;

                document.getElementById(
                    'total_reenviar'
                ).textContent = datos.total_reenviar;

                // =========================
                // RANKING ANALISTAS
                // =========================
                actualizarRankingAnalistas(datos, anio);

            } catch (error) {

                console.error(
                    'Error obteniendo estadísticas:',
                    error
                );

            }

        });

    });

});


// ======================================================
// RANKING ANALISTAS
// ======================================================
function actualizarRankingAnalistas(datos, anio) {

    document.getElementById(
        'ranking-analistas-anio'
    ).textContent = anio;

    const tbody =
        document.getElementById(
            'ranking-analistas-body'
        );

    tbody.innerHTML = '';

    const cotizaciones = {};

    (datos.top_analistas_cotizaciones || []).forEach(item => {

        cotizaciones[item.analista] =
            item.total;

    });

    const adjudicados =
        datos.top_analistas_adjudicados || [];

    if (adjudicados.length === 0) {

        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center text-muted py-4">
                    No existen registros.
                </td>
            </tr>
        `;

        return;

    }

    adjudicados.forEach((item, index) => {

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
                posicion = index + 1;

        }

        const cot =
            cotizaciones[item.analista] ?? 0;

        tbody.innerHTML += `
            <tr>

                <td>${posicion}</td>

                <td class="fw-semibold">
                    ${item.analista}
                </td>

                <td class="text-end">
                    <span class="badge bg-success fs-6">
                        ${item.total}
                    </span>
                </td>

                <td class="text-end">
                    <span class="badge bg-primary fs-6">
                        ${cot}
                    </span>
                </td>

            </tr>
        `;
    });

}