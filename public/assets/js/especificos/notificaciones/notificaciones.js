/* =========================================
   EVENTOS DEL DROPDOWN DE NOTIFICACIONES
   ========================================= */

document.addEventListener('click', function (evento) {

    const botonIndividual = evento.target.closest(
        '.btn-navbar-marcar-leida'
    );

    const botonTodas = evento.target.closest(
        '.btn-navbar-marcar-todas'
    );

    if (!botonIndividual && !botonTodas) {
        return;
    }

    evento.preventDefault();
    evento.stopPropagation();

    if (botonIndividual) {
        marcarNotificacionNavbarLeida(
            botonIndividual.dataset.id,
            botonIndividual
        );
    }

    if (botonTodas) {
        marcarTodasNavbarLeidas(botonTodas);
    }
});


/* =========================================
   ACTUALIZAR CONTADOR DE LA CAMPANA
   ========================================= */

function actualizarContadorNavbar(total) {

    const badge = document.getElementById(
        'navbar-notificaciones-total'
    );

    if (!badge) {
        return;
    }

    total = Math.max(0, parseInt(total, 10) || 0);

    badge.textContent = total > 0 ? total : '';
    badge.style.display = total > 0 ? '' : 'none';
}


/* =========================================
   REORDENAR NOTIFICACIONES
   No leídas primero; después, más recientes.
   ========================================= */

function ordenarNotificacionesNavbar() {

    const menu = document.getElementById(
        'navbar-notificaciones-menu'
    );

    if (!menu) {
        return;
    }

    const filas = Array.from(
        menu.querySelectorAll('.navbar-notificacion')
    );

    const elementos = filas.map(fila => {

        const separador = fila.nextElementSibling;

        return {
            fila: fila,
            separador:
                separador &&
                separador.classList.contains('notificacion-divider')
                    ? separador
                    : null,
            leida: fila.classList.contains('leida'),
            id: parseInt(fila.dataset.notificacionId, 10) || 0
        };
    });

    elementos.sort((a, b) => {

        // Primero las no leídas.
        if (a.leida !== b.leida) {
            return a.leida ? 1 : -1;
        }

        // Dentro de cada grupo, las más recientes primero.
        return b.id - a.id;
    });

    const fragmento = document.createDocumentFragment();

    elementos.forEach(elemento => {

        fragmento.appendChild(elemento.fila);

        if (elemento.separador) {
            fragmento.appendChild(elemento.separador);
        }
    });

    // Insertar antes del footer, si existe.
    const footer = menu.querySelector('.notificaciones-footer');

    if (footer) {
        menu.insertBefore(fragmento, footer);
    } else {
        menu.appendChild(fragmento);
    }
}


/* =========================================
   MARCAR UNA NOTIFICACIÓN COMO LEÍDA
   ========================================= */

async function marcarNotificacionNavbarLeida(id, boton) {

    if (!id || boton.disabled) {
        return;
    }

    boton.disabled = true;

    try {

        const respuesta = await fetch(
            APP.baseUrl + 'notificaciones/marcar-leida',
            {
                method: 'POST',
                headers: {
                    'Content-Type':
                        'application/x-www-form-urlencoded'
                },
                body: 'id=' + encodeURIComponent(id)
            }
        );

        const datos = await respuesta.json();

        if (!respuesta.ok || !datos.ok) {
            boton.disabled = false;
            console.error(
                'No se pudo marcar la notificación como leída.'
            );
            return;
        }

        const notificacion = boton.closest(
            '.navbar-notificacion'
        );

        if (!notificacion) {
            return;
        }

        // Actualizar estado visual.
        notificacion.classList.remove('no-leida');
        notificacion.classList.add('leida');

        // Ya no se necesita el botón individual.
        boton.remove();

        // Actualizar contador y reorganizar.
        const badge = document.getElementById(
            'navbar-notificaciones-total'
        );

        const totalActual = badge
            ? parseInt(badge.textContent, 10) || 0
            : 0;

        actualizarContadorNavbar(totalActual - 1);
        ordenarNotificacionesNavbar();

        // Si ya no quedan pendientes, quitar el botón global.
        if (
            document.querySelectorAll(
                '#navbar-notificaciones-menu ' +
                '.navbar-notificacion.no-leida'
            ).length === 0
        ) {
            document.querySelector(
                '#navbar-notificaciones-menu ' +
                '.btn-navbar-marcar-todas'
            )?.remove();
        }

    } catch (error) {

        boton.disabled = false;

        console.error(
            'Error al marcar la notificación:',
            error
        );
    }
}


/* =========================================
   MARCAR TODAS COMO LEÍDAS
   ========================================= */

async function marcarTodasNavbarLeidas(boton) {

    if (boton.disabled) {
        return;
    }

    boton.disabled = true;

    try {

        const respuesta = await fetch(
            APP.baseUrl + 'notificaciones/marcar-todas',
            {
                method: 'POST'
            }
        );

        const datos = await respuesta.json();

        if (!respuesta.ok || !datos.ok) {
            boton.disabled = false;

            console.error(
                'No se pudieron marcar todas como leídas.'
            );

            return;
        }

        const menu = document.getElementById(
            'navbar-notificaciones-menu'
        );

        if (!menu) {
            return;
        }

        // Cambiar todas las notificaciones pendientes.
        menu.querySelectorAll(
            '.navbar-notificacion.no-leida'
        ).forEach(notificacion => {

            notificacion.classList.remove('no-leida');
            notificacion.classList.add('leida');

            notificacion.querySelector(
                '.btn-navbar-marcar-leida'
            )?.remove();
        });

        // Limpiar contador y botón global.
        actualizarContadorNavbar(0);
        boton.remove();

        // Ordenar las leídas por las más recientes.
        ordenarNotificacionesNavbar();

    } catch (error) {

        boton.disabled = false;

        console.error(
            'Error al marcar todas como leídas:',
            error
        );
    }
}



// =========================
// TEMPORIZADOR DE SESIÓN
// =========================

const TIEMPO_INACTIVIDAD = 10 * 60 * 1000;

let temporizadorSesion;


// =========================
// REINICIAR TEMPORIZADOR
// =========================

function reiniciarTemporizadorSesion() {

    clearTimeout(temporizadorSesion);

    temporizadorSesion = setTimeout(
        () => {
            // =========================
            // CERRAR SESIÓN
            // =========================

            window.location.href =
            APP.baseUrl + 'logout?sesion_expirada=1';

        },
        TIEMPO_INACTIVIDAD
    );

}


// =========================
// DETECTAR ACTIVIDAD
// =========================

const eventosActividad = [
    'click',
    'keydown',
    'mousemove',
    'scroll',
    'input',
    'change'
];


eventosActividad.forEach(evento => {

    document.addEventListener(
        evento,
        reiniciarTemporizadorSesion,
        { passive: true }
    );

});


// =========================
// INICIAR TEMPORIZADOR
// =========================

reiniciarTemporizadorSesion();