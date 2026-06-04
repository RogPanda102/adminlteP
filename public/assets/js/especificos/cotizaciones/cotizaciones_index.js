document.addEventListener('DOMContentLoaded', () => {

    const btnNueva = document.getElementById('nuevaCotizacion');

    if (btnNueva) {

        btnNueva.addEventListener('click', () => {

            window.location.href = BASE_URL + 'cotizaciones/nueva';

        });

    }

});