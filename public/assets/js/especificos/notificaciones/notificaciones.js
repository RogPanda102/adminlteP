async function actualizarNotificaciones() {

    try {

        const respuesta = await fetch(
            APP.baseUrl + 'notificaciones/ajax'
        );

        const datos = await respuesta.json();
        // =========================
        // Actualizar badge
        // =========================
        const badge = document.getElementById(
            'navbar-notificaciones-total'
        );

        if (datos.total > 0) {

            badge.style.display = '';

            badge.textContent = datos.total;

        } else {

            badge.style.display = 'none';

        }
        // =========================
        // Actualizar menú
        // =========================
        const parser = new DOMParser();

        const documento = parser.parseFromString(
            datos.html,
            'text/html'
        );

        const nuevoMenu = documento.getElementById(
            'navbar-notificaciones-menu'
        );

        const menuActual = document.getElementById(
            'navbar-notificaciones-menu'
        );

        if (nuevoMenu && menuActual) {

            menuActual.innerHTML = nuevoMenu.innerHTML;

        }

    } catch (error) {

        console.error(
            'Error al actualizar notificaciones:',
            error
        );

    }

}

setInterval(
    actualizarNotificaciones,
    30000
);