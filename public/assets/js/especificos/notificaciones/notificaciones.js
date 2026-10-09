// =========================
// MARCAR NOTIFICACIÓN DEL NAVBAR COMO LEÍDA
// =========================

document.addEventListener(
    'click',
    function (evento) {

        const boton =
            evento.target.closest(
                '.btn-navbar-marcar-leida'
            );

        if (!boton) {
            return;
        }

        evento.preventDefault();
        evento.stopPropagation();

        const id =
            boton.dataset.id;

        if (!id) {
            return;
        }

        marcarNotificacionNavbarLeida(
            id,
            boton
        );

    }
);


// =========================
// PROCESAR NOTIFICACIÓN
// =========================

async function marcarNotificacionNavbarLeida(
    id,
    boton
) {

    try {

        const respuesta = await fetch(
            APP.baseUrl +
            'notificaciones/marcar-leida',
            {
                method: 'POST',

                headers: {
                    'Content-Type':
                        'application/x-www-form-urlencoded'
                },

                body:
                    'id=' +
                    encodeURIComponent(id)
            }
        );


        const datos =
            await respuesta.json();


        if (!datos.ok) {

            console.error(
                'No se pudo marcar la notificación como leída.'
            );

            return;
        }


        // =========================
        // OBTENER CONTENEDOR
        // =========================

        const notificacion =
            boton.closest(
                '.navbar-notificacion'
            );


        if (!notificacion) {
            return;
        }


        // =========================
        // CAMBIAR ESTADO VISUAL
        // =========================

        notificacion.classList.remove(
            'no-leida'
        );

        notificacion.classList.add(
            'leida'
        );


        // =========================
        // ELIMINAR BOTÓN
        // =========================

        boton.remove();


        // =========================
        // ACTUALIZAR BADGE
        // =========================

        const badge =
            document.getElementById(
                'navbar-notificaciones-total'
            );


        if (badge) {

            let total =
                parseInt(
                    badge.textContent
                ) || 0;


            total--;


            if (total > 0) {

                badge.textContent =
                    total;

            } else {

                badge.textContent =
                    '';

                badge.style.display =
                    'none';

            }

        }

    } catch (error) {

        console.error(
            'Error al marcar notificación:',
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