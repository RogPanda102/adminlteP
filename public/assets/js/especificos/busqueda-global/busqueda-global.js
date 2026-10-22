
(() => {
    'use strict';

    const wrapper = document.getElementById('navbar-busqueda-global');
    const toggle = document.getElementById('navbar-busqueda-toggle');
    const panel = document.getElementById('navbar-busqueda-panel');
    const input = document.getElementById('input-busqueda-global');
    const closeButton = document.getElementById('navbar-busqueda-cerrar');
    const form = document.getElementById('form-busqueda-global');
    const resultados = document.getElementById('resultados-busqueda-global');

    if (
        !wrapper ||
        !toggle ||
        !panel ||
        !input ||
        !closeButton ||
        !form ||
        !resultados
    ) {
        return;
    }

    let temporizador = null;
    let controladorPeticion = null;
    let ultimaBusqueda = 0;

    // =========================================
    // ABRIR Y CERRAR
    // =========================================

    function abrirBusqueda() {
        wrapper.classList.add('is-open');

        toggle.setAttribute('aria-expanded', 'true');
        toggle.setAttribute('aria-label', 'Cerrar búsqueda global');

        panel.setAttribute('aria-hidden', 'false');

        input.focus();

        // Si ya hay texto, permite consultar al volver a abrir.
        if (input.value.trim().length >= 2) {
            buscar(input.value.trim());
        }
    }

    function cerrarBusqueda() {
        wrapper.classList.remove('is-open');

        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Abrir búsqueda global');

        panel.setAttribute('aria-hidden', 'true');

        // Se conserva el texto y los resultados.
        clearTimeout(temporizador);
    }

    toggle.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();

        if (wrapper.classList.contains('is-open')) {
            cerrarBusqueda();
        } else {
            abrirBusqueda();
        }
    });

    closeButton.addEventListener('click', (event) => {
        event.preventDefault();
        cerrarBusqueda();
    });

    // Cerrar al hacer clic fuera.
    document.addEventListener('click', (event) => {
        const dentroBusqueda = wrapper.contains(event.target);
        const dentroResultados = resultados.contains(event.target);

        if (!dentroBusqueda && !dentroResultados) {
            cerrarBusqueda();
        }
    });

    // Cerrar con Escape.
    document.addEventListener('keydown', (event) => {
        if (
            event.key === 'Escape' &&
            wrapper.classList.contains('is-open')
        ) {
            cerrarBusqueda();
            toggle.focus();
        }
    });

    // =========================================
    // PRESENTACIÓN DE ESTADOS
    // =========================================

    function mostrarMensaje(mensaje, tipo = 'info') {
        resultados.replaceChildren();

        const elemento = document.createElement('div');

        elemento.className = `busqueda-global-mensaje text-${tipo}`;
        elemento.setAttribute('role', 'status');
        elemento.textContent = mensaje;

        resultados.appendChild(elemento);
        resultados.hidden = false;
    }

    function crearElemento(etiqueta, clase, texto) {
        const elemento = document.createElement(etiqueta);

        if (clase) {
            elemento.className = clase;
        }

        elemento.textContent = texto ?? '';

        return elemento;
    }

    // =========================================
    // DIBUJAR RESULTADOS
    // =========================================

    function mostrarResultados(registros) {
        resultados.replaceChildren();
        resultados.hidden = false;

        if (!registros.length) {
            mostrarMensaje('No se encontraron coincidencias.');
            return;
        }

        const grupos = [
            { tipo: 'Cotización', titulo: 'Cotizaciones' },
            { tipo: 'Adjudicado', titulo: 'Adjudicaciones' },
            { tipo: 'Servicio', titulo: 'Servicios' }
        ];

        grupos.forEach((grupo) => {
            const elementos = registros.filter(
                (registro) => registro.tipo === grupo.tipo
            );

            if (!elementos.length) {
                return;
            }

            const seccion = document.createElement('section');
            seccion.className = 'busqueda-global-grupo';

            const encabezado = crearElemento(
                'div',
                'busqueda-global-grupo-titulo',
                grupo.titulo
            );

            seccion.appendChild(encabezado);

            elementos.forEach((registro) => {
                const tarjeta = document.createElement('div');
                tarjeta.className = 'busqueda-global-resultado';

                const folio = registro.folio
                    ? String(registro.folio)
                    : 'Sin folio';

                const requisicion = registro.req
                    ? String(registro.req)
                    : 'Sin requisición';

                const anio = registro.anio
                    ? String(registro.anio)
                    : 'Sin año';

                tarjeta.appendChild(
                    crearElemento(
                        'div',
                        'busqueda-global-resultado-folio',
                        folio
                    )
                );

                tarjeta.appendChild(
                    crearElemento(
                        'div',
                        'busqueda-global-resultado-req',
                        `Requisición: ${requisicion}`
                    )
                );

                tarjeta.appendChild(
                    crearElemento(
                        'div',
                        'busqueda-global-resultado-anio',
                        `Año: ${anio}`
                    )
                );

                seccion.appendChild(tarjeta);
            });

            resultados.appendChild(seccion);
        });
    }

    // =========================================
    // CONSULTAR EL ENDPOINT
    // =========================================

    async function buscar(termino) {
        termino = termino.trim();

        // Invalidar respuestas anteriores.
        const idBusqueda = ++ultimaBusqueda;

        if (controladorPeticion) {
            controladorPeticion.abort();
            controladorPeticion = null;
        }

        if (termino.length < 2) {
            resultados.replaceChildren();
            resultados.hidden = true;
            return;
        }

        const urlBase = form.dataset.url;

        if (!urlBase) {
            mostrarMensaje(
                'Falta configurar la URL del buscador.',
                'danger'
            );
            return;
        }

        controladorPeticion = new AbortController();

        mostrarMensaje('Buscando...', 'secondary');

        try {
            const separador = urlBase.includes('?') ? '&' : '?';

            const respuesta = await fetch(
                `${urlBase}${separador}q=${encodeURIComponent(termino)}`,
                {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin',
                    signal: controladorPeticion.signal
                }
            );

            if (idBusqueda !== ultimaBusqueda) {
                return;
            }

            if (respuesta.status === 401) {
                mostrarMensaje(
                    'Tu sesión ha expirado. Inicia sesión nuevamente.',
                    'danger'
                );
                return;
            }

            if (!respuesta.ok) {
                throw new Error(`Error HTTP ${respuesta.status}`);
            }

            const datos = await respuesta.json();

            if (idBusqueda !== ultimaBusqueda) {
                return;
            }

            if (!datos.ok) {
                mostrarMensaje(
                    datos.mensaje || 'No fue posible realizar la búsqueda.',
                    'danger'
                );
                return;
            }

            mostrarResultados(
                Array.isArray(datos.resultados) ? datos.resultados : []
            );

        } catch (error) {
            if (error.name === 'AbortError') {
                return;
            }

            console.error('Error en la búsqueda global:', error);

            if (idBusqueda === ultimaBusqueda) {
                mostrarMensaje(
                    'Ocurrió un error al buscar. Intenta nuevamente.',
                    'danger'
                );
            }
        }
    }

    // =========================================
    // BUSCAR MIENTRAS SE ESCRIBE
    // =========================================

    input.addEventListener('input', () => {
        clearTimeout(temporizador);

        const termino = input.value.trim();

        if (termino.length < 2) {
            ultimaBusqueda++;

            if (controladorPeticion) {
                controladorPeticion.abort();
                controladorPeticion = null;
            }

            resultados.replaceChildren();
            resultados.hidden = true;
            return;
        }

        temporizador = setTimeout(() => {
            buscar(termino);
        }, 300);
    });

    // Evitar recargar la página al enviar el formulario.
    form.addEventListener('submit', (event) => {
        event.preventDefault();

        clearTimeout(temporizador);
        buscar(input.value);
    });

})();
