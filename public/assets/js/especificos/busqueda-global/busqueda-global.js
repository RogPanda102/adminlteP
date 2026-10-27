
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
    let registrosActuales = [];
    let filtroActual = 'todo';

    const MODULOS = [
        { clave: 'cotizacion', nombre: 'Cotización' },
        { clave: 'adjudicado', nombre: 'Adjudicado' },
        { clave: 'servicio', nombre: 'Servicio' }
    ];

    // =========================================
    // ABRIR Y CERRAR
    // =========================================

    function abrirBusqueda() {
        wrapper.classList.add('is-open');

        toggle.setAttribute('aria-expanded', 'true');
        toggle.setAttribute('aria-label', 'Cerrar búsqueda global');

        panel.setAttribute('aria-hidden', 'false');

        input.focus();

        if (input.value.trim().length >= 2) {
            buscar(input.value.trim());
        }
    }

    function cerrarBusqueda() {
        wrapper.classList.remove('is-open');

        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Abrir búsqueda global');

        panel.setAttribute('aria-hidden', 'true');

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
        event.stopPropagation();
        cerrarBusqueda();
    });

    document.addEventListener('click', (event) => {
        const rutaEvento = event.composedPath();

        if (
            rutaEvento.includes(wrapper) ||
            rutaEvento.includes(resultados)
        ) {
            return;
        }

        cerrarBusqueda();
    });

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
    // ELEMENTOS Y MENSAJES
    // =========================================

    function crearElemento(etiqueta, clase, texto) {
        const elemento = document.createElement(etiqueta);

        if (clase) {
            elemento.className = clase;
        }

        elemento.textContent = texto ?? '';

        return elemento;
    }

    function mostrarMensaje(mensaje, tipo = 'info') {
        resultados.replaceChildren();

        const elemento = crearElemento(
            'div',
            `busqueda-global-mensaje text-${tipo}`,
            mensaje
        );

        elemento.setAttribute('role', 'status');

        resultados.appendChild(elemento);
        resultados.hidden = false;
    }

    // =========================================
    // FILTROS
    // =========================================

    function obtenerModulos(registro) {
        if (Array.isArray(registro.modulos)) {
            return registro.modulos;
        }

        if (registro.tipo) {
            return [registro.tipo];
        }

        return [];
    }

    function contarPorModulo(registros, nombreModulo) {
        return registros.filter((registro) =>
            obtenerModulos(registro).includes(nombreModulo)
        ).length;
    }

    function crearFiltros() {
        const filtros = crearElemento(
            'div',
            'busqueda-global-filtros'
        );

        filtros.setAttribute('role', 'tablist');
        filtros.setAttribute('aria-label', 'Filtrar resultados');

        const opciones = [
            { id: 'todo', texto: 'Todo' },
            {
                id: 'cotizacion',
                texto: 'Cotizaciones',
                modulo: 'Cotización'
            },
            {
                id: 'adjudicado',
                texto: 'Adjudicaciones',
                modulo: 'Adjudicado'
            },
            {
                id: 'servicio',
                texto: 'Servicios',
                modulo: 'Servicio'
            }
        ];

        opciones.forEach((opcion) => {
            const cantidad = opcion.modulo
                ? contarPorModulo(registrosActuales, opcion.modulo)
                : registrosActuales.length;

            const boton = document.createElement('button');

            boton.type = 'button';
            boton.className = 'busqueda-global-filtro';

            if (filtroActual === opcion.id) {
                boton.classList.add('active');
            }

            boton.setAttribute('role', 'tab');
            boton.setAttribute(
                'aria-selected',
                filtroActual === opcion.id ? 'true' : 'false'
            );

            boton.append(
                crearElemento(
                    'span',
                    'busqueda-global-filtro-texto',
                    opcion.texto
                ),
                crearElemento(
                    'span',
                    'busqueda-global-filtro-contador',
                    String(cantidad)
                )
            );

            boton.addEventListener('click', (event) => {
                // Evita que el clic llegue al detector global de cierre.
                event.preventDefault();
                event.stopPropagation();

                filtroActual = opcion.id;
                dibujarResultados();
            });

            filtros.appendChild(boton);
        });

        return filtros;
    }

    // =========================================
    // TARJETAS
    // =========================================

    function crearTarjeta(registro) {
        const tarjeta = crearElemento(
            'article',
            'busqueda-global-resultado'
        );

        const folio = crearElemento(
            'div',
            'busqueda-global-resultado-folio',
            registro.folio ? String(registro.folio) : 'Sin folio'
        );

        const detalles = crearElemento(
            'div',
            'busqueda-global-resultado-detalles'
        );

        detalles.append(
            crearElemento(
                'div',
                'busqueda-global-resultado-req',
                `Requisición: ${registro.req || 'Sin requisición'}`
            ),
            crearElemento(
                'div',
                'busqueda-global-resultado-anio',
                `Año: ${registro.anio || 'Sin año'}`
            )
        );

        tarjeta.append(folio, detalles);

        return tarjeta;
    }

    // =========================================
    // DIBUJAR RESULTADOS
    // =========================================

    function dibujarResultados() {
        resultados.replaceChildren();
        resultados.hidden = false;

        resultados.appendChild(crearFiltros());

        let registrosFiltrados = registrosActuales;

        if (filtroActual !== 'todo') {
            const modulo = MODULOS.find(
                (item) => item.clave === filtroActual
            );

            registrosFiltrados = registrosActuales.filter((registro) =>
                modulo && obtenerModulos(registro).includes(modulo.nombre)
            );
        }

        resultados.appendChild(
            crearElemento(
                'div',
                'busqueda-global-resumen',
                `${registrosFiltrados.length} ${
                    registrosFiltrados.length === 1
                        ? 'registro encontrado'
                        : 'registros encontrados'
                }`
            )
        );

        if (!registrosFiltrados.length) {
            resultados.appendChild(
                crearElemento(
                    'div',
                    'busqueda-global-mensaje',
                    'No hay resultados en este módulo.'
                )
            );

            return;
        }

        const lista = crearElemento(
            'div',
            'busqueda-global-lista'
        );

        registrosFiltrados.forEach((registro) => {
            lista.appendChild(crearTarjeta(registro));
        });

        resultados.appendChild(lista);
    }

    function mostrarResultados(registros) {
        registrosActuales = Array.isArray(registros) ? registros : [];
        filtroActual = 'todo';

        if (!registrosActuales.length) {
            resultados.replaceChildren();

            resultados.appendChild(
                crearElemento(
                    'div',
                    'busqueda-global-mensaje',
                    'No se encontraron coincidencias.'
                )
            );

            resultados.hidden = false;
            return;
        }

        dibujarResultados();
    }

    // =========================================
    // CONSULTAR EL ENDPOINT
    // =========================================

    async function buscar(termino) {
        termino = termino.trim();

        const idBusqueda = ++ultimaBusqueda;

        if (controladorPeticion) {
            controladorPeticion.abort();
            controladorPeticion = null;
        }

        if (termino.length < 2) {
            registrosActuales = [];
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

        mostrarMensaje('Buscando coincidencias...', 'secondary');

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

            registrosActuales = [];
            resultados.replaceChildren();
            resultados.hidden = true;
            return;
        }

        temporizador = setTimeout(() => {
            buscar(termino);
        }, 300);
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();

        clearTimeout(temporizador);
        buscar(input.value);
    });

})();
