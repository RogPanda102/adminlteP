document.addEventListener('DOMContentLoaded', function () {

    let grafica = null;

    const modal = document.getElementById('modalCotizaciones');
    const filtroAnio = document.getElementById('filtro-anio-cotizaciones');
    const filtroMes = document.getElementById('filtro-mes-cotizaciones');

    // ✅ CORREGIDO (IDs reales del modal)
    const elTotal = document.getElementById('total-anio');
    const elPromedio = document.getElementById('promedio-mensual');
    const elMejorMes = document.getElementById('mejor-mes');

    function cargarGraficaCotizaciones()
    {
        let anio = filtroAnio ? filtroAnio.value : '';
        let mes = filtroMes ? filtroMes.value : '';

        let url =
            BASE_URL +
            'perfil/estadisticasCotizaciones?anio=' + anio;

        if (mes !== '') {
            url += '&mes=' + mes;
        }

        fetch(url)
        .then(response => response.json())
        .then(data => {

            if (data.error) return;

            crearGrafica(data.grafica);
            pintarMetricas(data.metricas);

        })
        .catch(error => {
            console.error('Error cargando estadísticas:', error);
        });
    }

    function crearGrafica(datos)
    {
        const canvas = document.getElementById('graficaCotizaciones');
        if (!canvas) return;

        const meses = [
            'Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
        ];

        const valores = [];
        for (let i = 1; i <= 12; i++) {
            valores.push(datos[i] ?? 0);
        }

        if (grafica) {
            grafica.destroy();
        }

        grafica = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Cotizaciones realizadas',
                    data: valores
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    function pintarMetricas(metricas)
    {
        if (!metricas) return;

        if (elTotal) {
            elTotal.innerText = metricas.total ?? 0;
        }

        if (elPromedio) {
            elPromedio.innerText = metricas.promedio ?? 0;
        }

        if (elMejorMes) {
            elMejorMes.innerText = metricas.mejor_mes ?? '-';
        }
    }

    // abrir modal
    if (modal) {
        modal.addEventListener('shown.bs.modal', function () {
            cargarGraficaCotizaciones();
        });
    }

    // filtros
    if (filtroAnio) {
        filtroAnio.addEventListener('change', cargarGraficaCotizaciones);
    }

    if (filtroMes) {
        filtroMes.addEventListener('change', cargarGraficaCotizaciones);
    }

});