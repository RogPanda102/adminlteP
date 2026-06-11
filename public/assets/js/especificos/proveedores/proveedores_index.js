document.addEventListener('DOMContentLoaded', () => {

    const btnNuevo = document.getElementById('nuevoProveedor');

    if (btnNuevo) {

        btnNuevo.addEventListener('click', () => {

            window.location.href = BASE_URL + 'proveedores/nuevo';

        });

    }

});
