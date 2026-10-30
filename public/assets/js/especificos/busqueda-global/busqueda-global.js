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

    let modalDetalle = null;
    let modalDetalleInstancia = null;

    // =========================================
    // ABRIR Y CERRAR BÚSQUEDA
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
    // TARJETAS CLICABLES
    // =========================================

    function crearTarjeta(registro) {
        const tarjeta = crearElemento(
            'article',
            'busqueda-global-resultado'
        );

        tarjeta.setAttribute('role', 'button');
        tarjeta.setAttribute('tabindex', '0');
        tarjeta.setAttribute(
            'aria-label',
            `Consultar expediente ${registro.folio || 'sin folio'}`
        );
        tarjeta.setAttribute('title', 'Ver expediente completo');

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

        const modulos = obtenerModulos(registro);

        if (modulos.length) {
            detalles.appendChild(
                crearElemento(
                    'div',
                    'small text-muted mt-1',
                    `Etapas: ${modulos.join(' · ')}`
                )
            );
        }

        tarjeta.append(folio, detalles);

        tarjeta.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            abrirDetalle(registro);
        });

        tarjeta.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                event.stopPropagation();
                abrirDetalle(registro);
            }
        });

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
                modulo &&
                obtenerModulos(registro).includes(modulo.nombre)
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
    // MODAL DE DETALLE
    // =========================================

    function obtenerModalDetalle() {
        if (modalDetalle && modalDetalleInstancia) {
            return modalDetalle;
        }

        if (!window.bootstrap || !window.bootstrap.Modal) {
            throw new Error(
                'Bootstrap Modal no está disponible. Verifica que Bootstrap JS esté cargado.'
            );
        }

        modalDetalle = document.createElement('div');
        modalDetalle.className = 'modal fade';
        modalDetalle.id = 'modal-detalle-busqueda-global';
        modalDetalle.tabIndex = -1;
        modalDetalle.setAttribute('aria-hidden', 'true');
        modalDetalle.setAttribute(
            'aria-labelledby',
            'titulo-detalle-busqueda-global'
        );

        // Este HTML es estático; los datos se insertan usando textContent.
        modalDetalle.innerHTML = `
            <div class="modal-dialog modal-dialog-scrollable" style="max-width: 80vw; width: 80vw; height: 80vh; max-height: 80vh;">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title" id="titulo-detalle-busqueda-global">
                                Expediente
                            </h5>
                            <div id="subtitulo-detalle-busqueda-global"
                                 class="small text-muted mt-1">
                                Información relacionada
                            </div>
                        </div>
                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body" id="contenido-detalle-busqueda-global">
                    </div>
                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        `;

        document.body.appendChild(modalDetalle);

        modalDetalleInstancia = window.bootstrap.Modal.getOrCreateInstance(
            modalDetalle
        );

        return modalDetalle;
    }

    function establecerTituloModal(titulo, subtitulo = '') {
        const tituloElemento = modalDetalle.querySelector(
            '#titulo-detalle-busqueda-global'
        );

        const subtituloElemento = modalDetalle.querySelector(
            '#subtitulo-detalle-busqueda-global'
        );

        tituloElemento.textContent = titulo;
        subtituloElemento.textContent = subtitulo;
    }

    function obtenerUrlDetalle(registro) {
        const urlBase = form.dataset.url;

        if (!urlBase) {
            throw new Error('Falta configurar la URL del buscador.');
        }

        const url = new URL(
            `${urlBase.replace(/\/+$/, '')}/detalle`,
            window.location.href
        );

        const ids = registro.ids || {};

        ['cotizacion', 'adjudicado', 'servicio'].forEach((modulo) => {
            const id = Number(ids[modulo]);

            if (Number.isInteger(id) && id > 0) {
                url.searchParams.set(`${modulo}_id`, String(id));
            }
        });

        if (!url.searchParams.has('cotizacion_id') &&
            !url.searchParams.has('adjudicado_id') &&
            !url.searchParams.has('servicio_id')) {
            throw new Error(
                'El resultado no contiene los identificadores necesarios para consultar su expediente.'
            );
        }

        return url.toString();
    }

    async function abrirDetalle(registro) {
        let modal;

        try {
            modal = obtenerModalDetalle();
        } catch (error) {
            console.error(error);
            mostrarMensaje(error.message, 'danger');
            return;
        }

        establecerTituloModal(
            `Expediente: ${registro.folio || 'Sin folio'}`,
            `Requisición: ${registro.req || 'Sin requisición'} · Año: ${registro.anio || 'Sin año'}`
        );

        const contenido = modal.querySelector(
            '#contenido-detalle-busqueda-global'
        );

        contenido.replaceChildren();

        const cargando = crearElemento(
            'div',
            'text-center text-muted py-5'
        );

        cargando.append(
            crearElemento('div', 'spinner-border text-primary mb-3'),
            crearElemento(
                'div',
                '',
                'Consultando información del expediente...'
            )
        );

        contenido.appendChild(cargando);
        modalDetalleInstancia.show();

        try {
            const url = obtenerUrlDetalle(registro);

            const respuesta = await fetch(url, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                },
                credentials: 'same-origin'
            });

            if (respuesta.status === 401) {
                throw new Error(
                    'Tu sesión ha expirado. Inicia sesión nuevamente.'
                );
            }

            const datos = await respuesta.json();

            if (!respuesta.ok || !datos.ok) {
                throw new Error(
                    datos.mensaje ||
                    `No fue posible consultar el expediente (HTTP ${respuesta.status}).`
                );
            }

            renderizarDetalle(datos.detalle || {});
        } catch (error) {
            console.error('Error al consultar el expediente:', error);

            contenido.replaceChildren(
                crearElemento(
                    'div',
                    'alert alert-danger mb-0',
                    error.message || 'Ocurrió un error al cargar el expediente.'
                )
            );
        }
    }

    // =========================================
    // FORMATO DE DATOS
    // =========================================

    const ETIQUETAS = {
        folio: 'Folio',
        req: 'Requisición',
        anio: 'Año',
        fecha: 'Fecha',
        fecha_elaboracion: 'Fecha de elaboración',
        proveedor: 'Proveedor',
        elaboro: 'Elaboró',
        partida: 'Partida',
        estatus: 'Estado de cotización',
        cotizacion_estatus: 'Estado de cotización',
        pago: 'Estado de pago',
        total: 'Importe total',
        dia_pago: 'Fecha de pago',
        fecha_inicio_entrega: 'Inicio de entrega',
        fecha_fin_entrega: 'Fin de entrega',
        fecha_contratacion: 'Fecha de contratación',
        inicio: 'Inicio del servicio',
        finalizacion: 'Finalización del servicio',
        tiempo_cantidad: 'Duración',
        tiempo_unidad: 'Unidad de duración',
        dependencia: 'Dependencia',
        analista: 'Analista',
        tipo_servicio: 'Tipo de servicio',
        nombre: 'Nombre',
        telefono: 'Teléfono',
        correo: 'Correo',
        tipo: 'Tipo de documento',
        nombre_original: 'Archivo',
        extension: 'Extensión',
        mime: 'Formato',
        tamano: 'Tamaño',
        fecha_creacion: 'Fecha de registro'
    };

    const CAMPOS_OMITIDOS = new Set([
        'id',
        'documentos',
        'ids',
        'urls',
        'modulos',
        'estados',
        'servicios',
        'cotizacion',
        'adjudicado',
        'etapa_actual',
        'total_documentos',
        'registro_id',
        'cotizacion_id',
        'adjudicado_id',
        'servicio_id',
        'analista_id',
        'dependencia_id',
        'tipo_servicio_id',
        'creado_por',
        'actualizado_por',
        'fecha_actualizacion',
        'eliminado',
        'subido_por',
        'ruta',
        'nombre_archivo'
    ]);

    function formatearEtiqueta(campo) {
        if (ETIQUETAS[campo]) {
            return ETIQUETAS[campo];
        }

        return campo
            .replace(/_/g, ' ')
            .replace(/\b\w/g, (letra) => letra.toUpperCase());
    }

    function formatearValor(campo, valor) {
        if (valor === null || valor === undefined || valor === '') {
            return 'No especificado';
        }

        if (typeof valor === 'object') {
            if (valor.nombre) return String(valor.nombre);
            if (valor.descripcion) return String(valor.descripcion);
            return '';
        }

        if (
            campo === 'total' ||
            campo === 'monto' ||
            campo === 'importe'
        ) {
            const numero = Number(valor);

            if (Number.isFinite(numero)) {
                return numero.toLocaleString('es-MX', {
                    style: 'currency',
                    currency: 'MXN'
                });
            }
        }

        if (campo === 'tamano') {
            return formatearTamano(valor);
        }

        return String(valor);
    }

    function formatearTamano(bytes) {
        const numero = Number(bytes);

        if (!Number.isFinite(numero) || numero < 0) {
            return 'Tamaño desconocido';
        }

        if (numero < 1024) {
            return `${numero} B`;
        }

        if (numero < 1024 * 1024) {
            return `${(numero / 1024).toFixed(1)} KB`;
        }

        return `${(numero / (1024 * 1024)).toFixed(2)} MB`;
    }

    function crearSeccion(titulo, subtitulo = '') {
        const seccion = crearElemento('section', 'mb-4');

        const encabezado = crearElemento(
            'div',
            'border-bottom pb-2 mb-3'
        );

        encabezado.appendChild(
            crearElemento('h6', 'fw-bold mb-0', titulo)
        );

        if (subtitulo) {
            encabezado.appendChild(
                crearElemento('div', 'small text-muted mt-1', subtitulo)
            );
        }

        seccion.appendChild(encabezado);

        return seccion;
    }

    function crearTablaDatos(registro) {
        const tabla = crearElemento(
            'div',
            'row g-3'
        );

        let cantidad = 0;

        Object.entries(registro).forEach(([campo, valor]) => {
            if (
                CAMPOS_OMITIDOS.has(campo) ||
                campo.endsWith('_id') ||
                valor === null ||
                valor === undefined ||
                typeof valor === 'function'
            ) {
                return;
            }

            if (Array.isArray(valor)) {
                return;
            }

            if (typeof valor === 'object') {
                if (!valor.nombre && !valor.descripcion) {
                    return;
                }
            }

            const valorTexto = formatearValor(campo, valor);

            if (!valorTexto) {
                return;
            }

            const columna = crearElemento(
                'div',
                'col-12 col-md-6 col-lg-4'
            );

            columna.append(
                crearElemento(
                    'div',
                    'small text-muted',
                    formatearEtiqueta(campo)
                ),
                crearElemento(
                    'div',
                    'fw-semibold text-break',
                    valorTexto
                )
            );

            tabla.appendChild(columna);
            cantidad++;
        });

        if (!cantidad) {
            tabla.appendChild(
                crearElemento(
                    'div',
                    'col-12 text-muted',
                    'No hay datos adicionales disponibles.'
                )
            );
        }

        return tabla;
    }

    function obtenerDocumentos(registro) {
        if (!registro || typeof registro !== 'object') {
            return [];
        }

        return Array.isArray(registro.documentos)
            ? registro.documentos
            : [];
    }

    function obtenerUrlDocumento(documento) {
        const urlBase = form.dataset.url;

        if (!urlBase || !documento.modulo || !documento.id) {
            return null;
        }

        const baseSistema = urlBase.replace(
            /busqueda-global\/?$/,
            ''
        );

        const modulosPermitidos = {
            cotizaciones: 'cotizaciones',
            adjudicados: 'adjudicados',
            servicios: 'servicios'
        };

        const modulo = modulosPermitidos[documento.modulo];

        if (!modulo) {
            return null;
        }

        const url = new URL(
            `${baseSistema}${modulo}/documentos/ver`,
            window.location.href
        );

        url.searchParams.set('id', String(documento.id));

        return url.toString();
    }

    function crearListaDocumentos(documentos) {
        const lista = crearElemento('div', 'list-group');

        if (!documentos.length) {
            lista.appendChild(
                crearElemento(
                    'div',
                    'alert alert-light border mb-0',
                    'No hay documentos adjuntos en este registro.'
                )
            );

            return lista;
        }

        documentos.forEach((documento) => {
            const fila = crearElemento(
                'div',
                'list-group-item d-flex flex-wrap align-items-center justify-content-between gap-3'
            );

            const informacion = crearElemento('div', 'flex-grow-1');

            informacion.append(
                crearElemento(
                    'div',
                    'fw-semibold text-break',
                    documento.nombre_original || 'Documento sin nombre'
                ),
                crearElemento(
                    'div',
                    'small text-muted',
                    [
                        documento.tipo || 'Sin tipo',
                        documento.extension
                            ? String(documento.extension).toUpperCase()
                            : '',
                        documento.tamano !== undefined
                            ? formatearTamano(documento.tamano)
                            : '',
                        documento.fecha_creacion || ''
                    ].filter(Boolean).join(' · ')
                )
            );

            fila.appendChild(informacion);

            const urlDocumento = obtenerUrlDocumento(documento);

            if (urlDocumento) {
                const enlace = crearElemento(
                    'a',
                    'btn btn-outline-primary btn-sm',
                    'Abrir documento'
                );

                enlace.href = urlDocumento;
                enlace.target = '_blank';
                enlace.rel = 'noopener noreferrer';

                fila.appendChild(enlace);
            } else {
                fila.appendChild(
                    crearElemento(
                        'span',
                        'small text-muted',
                        'Acceso no disponible'
                    )
                );
            }

            lista.appendChild(fila);
        });

        return lista;
    }

    function agregarRegistro(contenido, titulo, registro) {
        if (!registro || typeof registro !== 'object') {
            return;
        }

        const seccion = crearSeccion(titulo);

        seccion.appendChild(crearTablaDatos(registro));

        const documentos = obtenerDocumentos(registro);
        const seccionDocumentos = crearElemento('div', 'mt-3');

        seccionDocumentos.append(
            crearElemento(
                'div',
                'fw-semibold mb-2',
                `Documentos adjuntos (${documentos.length})`
            ),
            crearListaDocumentos(documentos)
        );

        seccion.appendChild(seccionDocumentos);
        contenido.appendChild(seccion);
    }

    function renderizarDetalle(detalle) {
        const contenido = modalDetalle.querySelector(
            '#contenido-detalle-busqueda-global'
        );

        contenido.replaceChildren();

        const cotizacion = detalle.cotizacion || null;
        const adjudicado = detalle.adjudicado || null;
        const servicios = Array.isArray(detalle.servicios)
            ? detalle.servicios
            : [];

        const etapas = [];

        if (cotizacion) etapas.push('Cotización');
        if (adjudicado) etapas.push('Adjudicación');
        if (servicios.length) etapas.push('Servicio');

        const etapaActual = detalle.etapa_actual || (
            servicios.length
                ? 'Servicio'
                : adjudicado
                    ? 'Adjudicación'
                    : cotizacion
                        ? 'Cotización'
                        : 'Sin información'
        );

        const resumen = crearElemento(
            'div',
            'alert alert-primary d-flex flex-wrap align-items-center justify-content-between gap-2'
        );

        const textoResumen = crearElemento('div');

        textoResumen.append(
            crearElemento(
                'div',
                'fw-bold',
                'Resumen del expediente'
            ),
            crearElemento(
                'div',
                'small',
                etapas.length
                    ? `Información disponible en: ${etapas.join(' → ')}`
                    : 'No se encontraron registros relacionados.'
            )
        );

        const etiquetaEtapa = crearElemento(
            'span',
            'badge text-bg-primary',
            `Etapa actual: ${etapaActual}`
        );

        resumen.append(textoResumen, etiquetaEtapa);
        contenido.appendChild(resumen);

        if (cotizacion) {
            agregarRegistro(
                contenido,
                '1. Cotización',
                cotizacion
            );
        }

        if (adjudicado) {
            agregarRegistro(
                contenido,
                '2. Adjudicación',
                adjudicado
            );
        }

        servicios.forEach((servicio, indice) => {
            agregarRegistro(
                contenido,
                `3. Servicio${servicios.length > 1 ? ` ${indice + 1}` : ''}`,
                servicio
            );
        });

        if (cotizacion && !adjudicado && !servicios.length) {
            contenido.appendChild(
                crearElemento(
                    'div',
                    'alert alert-warning mb-0',
                    'Este expediente únicamente cuenta con una cotización. Todavía no tiene una adjudicación ni un servicio relacionado.'
                )
            );
        } else if (!cotizacion && adjudicado && !servicios.length) {
            contenido.appendChild(
                crearElemento(
                    'div',
                    'alert alert-info mb-0',
                    'Este expediente tiene una adjudicación, pero no se encontraron servicios relacionados.'
                )
            );
        } else if (!cotizacion && !adjudicado && !servicios.length) {
            contenido.appendChild(
                crearElemento(
                    'div',
                    'alert alert-warning mb-0',
                    'No se encontraron datos para mostrar.'
                )
            );
        }

        const totalDocumentos = Number(detalle.total_documentos);

        if (Number.isFinite(totalDocumentos)) {
            contenido.appendChild(
                crearElemento(
                    'div',
                    'text-muted small text-end mt-3',
                    `Total de documentos registrados: ${totalDocumentos}`
                )
            );
        }
    }

    // =========================================
    // CONSULTAR RESULTADOS
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
