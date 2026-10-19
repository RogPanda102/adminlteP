// =====================================================
// HELPER GENERICO DE DOCUMENTOS
// =====================================================

function inicializarDocumentos(config = {}) {

    // =====================================================
    // CONFIGURACION
    // =====================================================

    const modulo = config.modulo || '';
    const obtenerRegistroId = config.obtenerRegistroId || (() => null);
    const obtenerRegistrosRelacionados =
        config.obtenerRegistrosRelacionados || (() => []);

    const btnDocumentosId = config.btnDocumentosId || 'btn-documentos';
    const btnVolverId = config.btnVolverId || '';
    const erpPanelsId = config.erpPanelsId || 'erp-panels';
    const btnSubirDocumentoId = config.btnSubirDocumentoId || 'btn-subir-documento';
    const btnCancelarDocumentoId = config.btnCancelarDocumentoId || 'btn-cancelar-documento';
    const panelCargaDocumentoId = config.panelCargaDocumentoId || 'panel-carga-documento';
    const inputDocumentoArchivoId = config.inputDocumentoArchivoId || 'documento-archivo';
    const infoDocumentoArchivoId = config.infoDocumentoArchivoId || 'info-documento-archivo';
    const selectDocumentoTipoId = config.selectDocumentoTipoId || 'documento-tipo';
    const btnConfirmarDocumentoId = config.btnConfirmarDocumentoId || 'btn-confirmar-documento';

    // =====================================================
    // ELEMENTOS
    // =====================================================

    const btnDocumentos = document.getElementById(btnDocumentosId);
    const btnVolver = document.getElementById(btnVolverId);
    const erpPanels = document.getElementById(erpPanelsId);
    const btnSubirDocumento = document.getElementById(btnSubirDocumentoId);
    const btnCancelarDocumento = document.getElementById(btnCancelarDocumentoId);
    const panelCargaDocumento = document.getElementById(panelCargaDocumentoId);
    const inputDocumentoArchivo = document.getElementById(inputDocumentoArchivoId);
    const infoDocumentoArchivo = document.getElementById(infoDocumentoArchivoId);
    const selectDocumentoTipo = document.getElementById(selectDocumentoTipoId);
    const btnConfirmarDocumento = document.getElementById(btnConfirmarDocumentoId);

    const zonaArrastreDocumentos =
        document.getElementById('zona-arrastre-documentos');

    const btnSeleccionarDocumentos =
        document.getElementById('btn-seleccionar-documentos');

    // La lista se mantiene en memoria.
    // No dependemos de input.files para conservar la seleccion.
    let archivosDocumentoSeleccionados = [];

    // =====================================================
    // CONSTANTES
    // =====================================================

    const contenedores = [
        'solicitud',
        'cotizacion',
        'evidencia',
        'factura',
        'entrega',
        'otro'
    ];

    const extensionesPermitidas = [
        'pdf', 'jpg', 'jpeg', 'png',
        'doc', 'docx', 'xls', 'xlsx'
    ];

    const mimesPermitidos = [
        'application/pdf',
        'image/jpeg',
        'image/png',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    const maximoBytes = 5 * 1024 * 1024;
    const estadoCompresionDocumentos = new WeakMap();
    let procesandoArchivosDocumento = false;

    // =====================================================
    // UTILIDADES
    // =====================================================

    function escaparHTML(valor) {
        return String(valor ?? '').replace(/[&<>"']/g, caracter => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        })[caracter]);
    }

    function formatearTamanoDocumento(bytes) {
        bytes = Number(bytes);

        if (!bytes || bytes <= 0) {
            return '0 B';
        }

        const unidades = ['B', 'KB', 'MB', 'GB'];
        const indice = Math.min(
            Math.floor(Math.log(bytes) / Math.log(1024)),
            unidades.length - 1
        );

        return (
            bytes / Math.pow(1024, indice)
        ).toFixed(indice === 0 ? 0 : 2) + ' ' + unidades[indice];
    }

    // =====================================================
    // COMPRESION DE IMAGENES EN EL NAVEGADOR
    // =====================================================

    function crearBlobCanvas(canvas, tipo, calidad) {
        return new Promise((resolve, reject) => {
            canvas.toBlob(
                blob => {
                    if (!blob) {
                        reject(new Error('No se pudo procesar la imagen.'));
                        return;
                    }

                    resolve(blob);
                },
                tipo,
                calidad
            );
        });
    }

    async function comprimirImagenDocumento(archivo) {
        const tipo = archivo.type;
        const esJPEG = tipo === 'image/jpeg';
        const esPNG = tipo === 'image/png';

        if (!esJPEG && !esPNG) {
            return {
                archivo,
                comprimido: false,
                errorPeso: archivo.size > maximoBytes,
                tamanoOriginal: archivo.size
            };
        }

        let imagenURL = null;

        try {
            imagenURL = URL.createObjectURL(archivo);

            const imagen = new Image();

            await new Promise((resolve, reject) => {
                imagen.onload = resolve;
                imagen.onerror = () => {
                    reject(new Error('No se pudo abrir la imagen.'));
                };

                imagen.src = imagenURL;
            });

            const anchoOriginal = imagen.naturalWidth;
            const altoOriginal = imagen.naturalHeight;

            if (!anchoOriginal || !altoOriginal) {
                throw new Error('La imagen no tiene dimensiones válidas.');
            }

            let mejorArchivo = archivo;

            const escalas = [
                1, 0.85, 0.70, 0.55, 0.40,
                0.30, 0.22, 0.15, 0.10
            ];

            const calidades = esJPEG
                ? [0.85, 0.70, 0.55, 0.40, 0.30]
                : [undefined];

            for (const escala of escalas) {
                const canvas = document.createElement('canvas');

                canvas.width = Math.max(
                    1,
                    Math.round(anchoOriginal * escala)
                );

                canvas.height = Math.max(
                    1,
                    Math.round(altoOriginal * escala)
                );

                const contexto = canvas.getContext('2d');

                if (!contexto) {
                    throw new Error('No se pudo procesar la imagen.');
                }

                contexto.drawImage(
                    imagen,
                    0,
                    0,
                    canvas.width,
                    canvas.height
                );

                for (const calidad of calidades) {
                    const blob = await crearBlobCanvas(
                        canvas,
                        tipo,
                        calidad
                    );

                    const candidato = new File(
                        [blob],
                        archivo.name,
                        {
                            type: tipo,
                            lastModified: archivo.lastModified
                        }
                    );

                    if (candidato.size < mejorArchivo.size) {
                        mejorArchivo = candidato;
                    }

                    if (candidato.size <= maximoBytes) {
                        canvas.width = 1;
                        canvas.height = 1;

                        return {
                            archivo: candidato,
                            comprimido: candidato.size < archivo.size,
                            errorPeso: false,
                            tamanoOriginal: archivo.size
                        };
                    }
                }

                canvas.width = 1;
                canvas.height = 1;
            }

            return {
                archivo: mejorArchivo,
                comprimido: mejorArchivo.size < archivo.size,
                errorPeso: mejorArchivo.size > maximoBytes,
                tamanoOriginal: archivo.size
            };

        } catch (error) {
            console.error(
                `Error al comprimir ${archivo.name}:`,
                error
            );

            return {
                archivo,
                comprimido: false,
                errorPeso: archivo.size > maximoBytes,
                tamanoOriginal: archivo.size
            };

        } finally {
            if (imagenURL) {
                URL.revokeObjectURL(imagenURL);
            }
        }
    }

    
    async function comprimirImagenDocumento(archivo) {
        const tipo = archivo.type;
        const esJPEG = tipo === 'image/jpeg';
        const esPNG = tipo === 'image/png';

        if (!esJPEG && !esPNG) {
            return {
                archivo,
                comprimido: false,
                errorPeso: archivo.size > maximoBytes,
                tamanoOriginal: archivo.size
            };
        }

        let imagenURL = null;

        try {
            imagenURL = URL.createObjectURL(archivo);

            const imagen = new Image();

            await new Promise((resolve, reject) => {
                imagen.onload = resolve;
                imagen.onerror = () => reject(
                    new Error('No se pudo abrir la imagen.')
                );

                imagen.src = imagenURL;
            });

            const anchoOriginal = imagen.naturalWidth;
            const altoOriginal = imagen.naturalHeight;

            if (!anchoOriginal || !altoOriginal) {
                throw new Error('La imagen no tiene dimensiones válidas.');
            }

            let mejorArchivo = archivo;

            // Reducir progresivamente las dimensiones.
            const escalas = [
                1, 0.85, 0.70, 0.55, 0.40,
                0.30, 0.22, 0.15, 0.10
            ];

            // En JPEG también se reduce la calidad.
            // En PNG se conservan los colores y la transparencia.
            const calidades = esJPEG
                ? [0.85, 0.70, 0.55, 0.40, 0.30]
                : [undefined];

            for (const escala of escalas) {
                const canvas = document.createElement('canvas');

                canvas.width = Math.max(
                    1,
                    Math.round(anchoOriginal * escala)
                );

                canvas.height = Math.max(
                    1,
                    Math.round(altoOriginal * escala)
                );

                const contexto = canvas.getContext('2d');

                if (!contexto) {
                    throw new Error('No se pudo procesar la imagen.');
                }

                contexto.drawImage(
                    imagen,
                    0,
                    0,
                    canvas.width,
                    canvas.height
                );

                for (const calidad of calidades) {
                    const blob = await crearBlobCanvas(
                        canvas,
                        tipo,
                        calidad
                    );

                    const candidato = new File(
                        [blob],
                        archivo.name,
                        {
                            type: tipo,
                            lastModified: archivo.lastModified
                        }
                    );

                    // Conservar la versión de menor tamaño encontrada.
                    if (candidato.size < mejorArchivo.size) {
                        mejorArchivo = candidato;
                    }

                    // Detenerse cuando ya cumple el límite.
                    if (candidato.size <= maximoBytes) {
                        return {
                            archivo: candidato,
                            comprimido: candidato !== archivo,
                            errorPeso: false,
                            tamanoOriginal: archivo.size
                        };
                    }
                }

                // Liberar memoria del lienzo antes de continuar.
                canvas.width = 1;
                canvas.height = 1;
            }

            // Si no alcanza el límite, conservar la mejor versión obtenida.
            return {
                archivo: mejorArchivo,
                comprimido: mejorArchivo !== archivo,
                errorPeso: mejorArchivo.size > maximoBytes,
                tamanoOriginal: archivo.size
            };

        } catch (error) {
            console.error(
                `Error al comprimir ${archivo.name}:`,
                error
            );

            return {
                archivo,
                comprimido: false,
                errorPeso: true,
                tamanoOriginal: archivo.size
            };

        } finally {
            if (imagenURL) {
                URL.revokeObjectURL(imagenURL);
            }
        }
    }


    function obtenerContenedor(tipo) {
        return document.getElementById(`documentos-${tipo}`);
    }

    function mostrarAviso(mensaje) {
        if (typeof toastr !== 'undefined') {
            toastr.warning(mensaje);
        } else {
            console.warn(mensaje);
        }
    }

    // =====================================================
    // ARCHIVOS SELECCIONADOS
    // =====================================================

    function limpiarArchivosSeleccionados() {
        archivosDocumentoSeleccionados = [];

        if (inputDocumentoArchivo) {
            inputDocumentoArchivo.value = '';
        }

        if (infoDocumentoArchivo) {
            infoDocumentoArchivo.innerHTML = '';
            infoDocumentoArchivo.classList.add('d-none');
        }

        zonaArrastreDocumentos?.classList.remove(
            'border-primary',
            'bg-primary-subtle'
        );
    }

    function obtenerExtension(nombre) {
        const partes = String(nombre || '').split('.');

        return partes.length > 1
            ? partes.pop().toLowerCase()
            : '';
    }
    // Carga de archivos 
    async function agregarArchivosDocumento(listaArchivos) {
        const archivos = Array.from(listaArchivos || []);

        if (!archivos.length || procesandoArchivosDocumento) {
            return;
        }

        const archivosPesados = archivos.filter(
            archivo => archivo.size > maximoBytes
        );

        if (archivosPesados.length) {
            mostrarAviso(
                'Algunos archivos superan el límite. Se intentará reducir ' +
                'su tamaño para ser almacenados y guardados correctamente.'
            );
        }

        procesandoArchivosDocumento = true;

        if (btnConfirmarDocumento) {
            btnConfirmarDocumento.disabled = true;
        }

        try {
            for (const archivoOriginal of archivos) {
                const extension = obtenerExtension(archivoOriginal.name);

                if (!extensionesPermitidas.includes(extension)) {
                    mostrarAviso(
                        `${archivoOriginal.name}: la extensión no está permitida.`
                    );
                    continue;
                }

                if (
                    archivoOriginal.type &&
                    !mimesPermitidos.includes(archivoOriginal.type)
                ) {
                    mostrarAviso(
                        `${archivoOriginal.name}: el tipo de archivo no está permitido.`
                    );
                    continue;
                }

                const duplicado = archivosDocumentoSeleccionados.some(
                    existente =>
                        existente.name === archivoOriginal.name &&
                        existente.size === archivoOriginal.size &&
                        existente.lastModified === archivoOriginal.lastModified
                );

                if (duplicado) {
                    continue;
                }

                let resultado = {
                    archivo: archivoOriginal,
                    comprimido: false,
                    errorPeso: archivoOriginal.size > maximoBytes,
                    tamanoOriginal: archivoOriginal.size
                };

                if (archivoOriginal.size > maximoBytes) {
                    const esImagen = [
                        'jpg',
                        'jpeg',
                        'png'
                    ].includes(extension);

                    if (esImagen) {
                        resultado = await comprimirImagenDocumento(
                            archivoOriginal
                        );
                    }
                }

                const archivoFinal = resultado.archivo;

                estadoCompresionDocumentos.set(archivoFinal, {
                    errorPeso: resultado.errorPeso,
                    comprimido: resultado.comprimido,
                    tamanoOriginal: resultado.tamanoOriginal
                });

                archivosDocumentoSeleccionados.push(archivoFinal);

                if (resultado.errorPeso) {
                    mostrarAviso(
                        `${archivoOriginal.name}: sigue superando los 5 MB. ` +
                        'Elimínalo de la selección para poder subir los demás.'
                    );
                } else if (resultado.comprimido) {
                    if (typeof toastr !== 'undefined') {
                        toastr.success(
                            `${archivoOriginal.name}: reducido de ` +
                            `${formatearTamanoDocumento(resultado.tamanoOriginal)} ` +
                            `a ${formatearTamanoDocumento(archivoFinal.size)}.`
                        );
                    }
                }
            }

            renderizarArchivosDocumento();

        } finally {
            procesandoArchivosDocumento = false;

            if (btnConfirmarDocumento) {
                btnConfirmarDocumento.disabled = false;
            }
        }
    }

    function renderizarArchivosDocumento() {
        if (!infoDocumentoArchivo) {
            return;
        }

        infoDocumentoArchivo.innerHTML = '';

        if (!archivosDocumentoSeleccionados.length) {
            infoDocumentoArchivo.classList.add('d-none');
            return;
        }

        const encabezado = document.createElement('div');
        encabezado.className =
            'd-flex justify-content-between align-items-center mb-2';

        const titulo = document.createElement('span');
        titulo.className = 'fw-semibold small';
        titulo.textContent =
            `Archivos seleccionados (${archivosDocumentoSeleccionados.length})`;

        const quitarTodos = document.createElement('button');
        quitarTodos.type = 'button';
        quitarTodos.className = 'btn btn-sm btn-link text-danger';
        quitarTodos.textContent = 'Quitar todos';

        quitarTodos.addEventListener('click', () => {
            limpiarArchivosSeleccionados();
        });

        encabezado.append(titulo, quitarTodos);
        infoDocumentoArchivo.appendChild(encabezado);

        archivosDocumentoSeleccionados.forEach((archivo, indice) => {
            const fila = document.createElement('div');
            fila.className =
                'd-flex align-items-center gap-2 border rounded p-2 mb-2 bg-white';

            const estado = estadoCompresionDocumentos.get(archivo) || {
                errorPeso: archivo.size > maximoBytes,
                comprimido: false,
                tamanoOriginal: archivo.size
            };

            const icono = document.createElement('i');
            icono.className = estado.errorPeso
                ? 'bi bi-exclamation-triangle-fill text-danger fs-4'
                : 'bi bi-file-earmark text-primary fs-4';

            if (estado.errorPeso) {
                icono.title = 'Archivo demasiado pesado. Elimínalo para continuar.';
                icono.setAttribute('aria-label', 'Archivo que supera el límite de 5 MB');
            }

            const detalles = document.createElement('div');
            detalles.className = 'flex-grow-1 overflow-hidden';

            const nombre = document.createElement('div');
            nombre.className = 'fw-semibold small text-break';
            nombre.textContent = archivo.name;

            const tamano = document.createElement('div');
            tamano.className = estado.errorPeso
                ? 'text-danger small'
                : 'text-muted small';

            tamano.textContent = estado.errorPeso
                ? `! ${formatearTamanoDocumento(archivo.size)} — supera el límite de 5 MB`
                : estado.comprimido
                    ? `${formatearTamanoDocumento(estado.tamanoOriginal)} → ${formatearTamanoDocumento(archivo.size)}`
                    : formatearTamanoDocumento(archivo.size);

            detalles.append(nombre, tamano);

            const quitar = document.createElement('button');
            quitar.type = 'button';
            quitar.className = 'btn btn-sm btn-outline-danger';
            quitar.title = 'Quitar archivo';
            quitar.setAttribute('aria-label', `Quitar ${archivo.name}`);

            const iconoQuitar = document.createElement('i');
            iconoQuitar.className = 'bi bi-x-lg';
            quitar.appendChild(iconoQuitar);

            quitar.addEventListener('click', () => {
                archivosDocumentoSeleccionados.splice(indice, 1);
                renderizarArchivosDocumento();
            });

            fila.append(icono, detalles, quitar);
            infoDocumentoArchivo.appendChild(fila);
        });

        infoDocumentoArchivo.classList.remove('d-none');
    }

    // =====================================================
    // SELECCION MANUAL
    // =====================================================

    btnSeleccionarDocumentos?.addEventListener('click', event => {
        event.preventDefault();
        event.stopPropagation();
        inputDocumentoArchivo?.click();
    });

    // Pulsar el area abre el selector, excepto si se pulsa
    // directamente el boton de seleccion.
    zonaArrastreDocumentos?.addEventListener('click', event => {
        if (event.target.closest('#btn-seleccionar-documentos')) {
            return;
        }

        inputDocumentoArchivo?.click();
    });

    inputDocumentoArchivo?.addEventListener('change', function () {
        agregarArchivosDocumento(this.files);

        // Se limpia el input, no la lista en memoria.
        // Asi se puede seleccionar otra vez el mismo archivo.
        this.value = '';
    });

    // =====================================================
    // ARRASTRAR Y SOLTAR
    // =====================================================

    // Se usa document en fase de captura para evitar que el navegador
    // abra o descargue los archivos al soltarlos.
    function esArrastreDeArchivos(event) {
        return Array.from(event.dataTransfer?.types || []).includes('Files');
    }

    function obtenerZonaArrastre(event) {
        if (!(event.target instanceof Element)) {
            return null;
        }

        return event.target.closest('#zona-arrastre-documentos');
    }

    document.addEventListener('dragenter', event => {
        if (!esArrastreDeArchivos(event)) {
            return;
        }

        event.preventDefault();

        const zona = obtenerZonaArrastre(event);

        if (zona) {
            zona.classList.add('border-primary', 'bg-primary-subtle');
        }
    }, true);

    document.addEventListener('dragover', event => {
        if (!esArrastreDeArchivos(event)) {
            return;
        }

        // Evita que el navegador abra el archivo arrastrado.
        event.preventDefault();

        const zona = obtenerZonaArrastre(event);

        if (zona) {
            event.dataTransfer.dropEffect = 'copy';

            zona.classList.add('border-primary', 'bg-primary-subtle');
        } else {
            event.dataTransfer.dropEffect = 'none';
        }
    }, true);

    document.addEventListener('dragleave', event => {
        if (!esArrastreDeArchivos(event)) {
            return;
        }

        const zona = obtenerZonaArrastre(event);

        if (!zona) {
            return;
        }

        // Mantener el resaltado si el cursor pasa a un elemento hijo.
        if (
            event.relatedTarget instanceof Node &&
            zona.contains(event.relatedTarget)
        ) {
            return;
        }

        zona.classList.remove('border-primary', 'bg-primary-subtle');
    }, true);

    document.addEventListener('drop', event => {
        if (!esArrastreDeArchivos(event)) {
            return;
        }

        // Impide que el navegador navegue al archivo soltado.
        event.preventDefault();
        event.stopPropagation();

        const zona = obtenerZonaArrastre(event);

        if (zona) {
            zona.classList.remove('border-primary', 'bg-primary-subtle');

            const archivos = event.dataTransfer?.files;

            if (archivos?.length) {
                agregarArchivosDocumento(archivos);
            }
        }
    }, true);

    // =====================================================
    // MOSTRAR CARGANDO / VACIO
    // =====================================================

    function mostrarCargando() {
        contenedores.forEach(tipo => {
            const container = obtenerContenedor(tipo);

            if (!container) return;

            container.innerHTML = `
                <div class="text-muted small text-center py-3">
                    Cargando documentos...
                </div>
            `;
        });
    }

    function limpiarContenedores() {
        contenedores.forEach(tipo => {
            const container = obtenerContenedor(tipo);

            if (container) {
                container.innerHTML = '';
            }
        });
    }

    function mostrarSinDocumentos() {
        contenedores.forEach(tipo => {
            const container = obtenerContenedor(tipo);

            if (!container) return;

            container.innerHTML = `
                <div class="text-muted small text-center py-3">
                    No hay documentos registrados.
                </div>
            `;
        });
    }

    // =====================================================
    // CARGAR DOCUMENTOS
    // =====================================================

    async function cargarDocumentos() {
        const registroId = obtenerRegistroId();

        if (!registroId) {
            toastr.error('No se encontro el registro.');
            return;
        }

        if (erpPanels) {
            erpPanels.style.transform = 'translateX(-50%)';
        }

        mostrarCargando();

        try {
            let documentos = [];

            const urlPrincipal =
                `${BASE_URL}${modulo}/documentos` +
                `?modulo=${encodeURIComponent(modulo)}` +
                `&registro_id=${encodeURIComponent(registroId)}`;

            const responsePrincipal = await fetch(urlPrincipal);
            const jsonPrincipal = await responsePrincipal.json();

            if (!responsePrincipal.ok || !jsonPrincipal.success) {
                throw new Error(
                    jsonPrincipal.message ||
                    'Error al cargar los documentos.'
                );
            }

            documentos = [...(jsonPrincipal.data || [])];

            const relacionados = obtenerRegistrosRelacionados() || [];

            for (const relacionado of relacionados) {
                if (
                    !relacionado ||
                    !relacionado.modulo ||
                    !relacionado.registro_id
                ) {
                    continue;
                }

                const urlRelacionado =
                    `${BASE_URL}${relacionado.modulo}/documentos` +
                    `?modulo=${encodeURIComponent(relacionado.modulo)}` +
                    `&registro_id=${encodeURIComponent(relacionado.registro_id)}`;

                const responseRelacionado = await fetch(urlRelacionado);
                const jsonRelacionado = await responseRelacionado.json();

                if (!responseRelacionado.ok || !jsonRelacionado.success) {
                    throw new Error(
                        jsonRelacionado.message ||
                        'Error al cargar documentos relacionados.'
                    );
                }

                documentos.push(...(jsonRelacionado.data || []));
            }

            limpiarContenedores();

            if (!documentos.length) {
                mostrarSinDocumentos();
                return;
            }

            documentos.forEach(doc => {
                const container = obtenerContenedor(doc.tipo);

                if (!container) return;

                const nombreOriginal = doc.nombre_original || 'Documento';
                const extension = (doc.extension || '').toLowerCase();

                const fecha = doc.fecha_creacion
                    ? new Date(doc.fecha_creacion).toLocaleString('es-MX')
                    : '';

                const tamano = doc.tamano
                    ? formatearTamanoDocumento(doc.tamano)
                    : '';

                let origen = config.obtenerOrigen
                    ? config.obtenerOrigen(doc)
                    : doc.modulo;

                if (!origen) {
                    origen = 'Documento';
                }

                const urlDocumento =
                    `${BASE_URL}${doc.modulo}/documentos/ver` +
                    `?id=${encodeURIComponent(doc.id)}`;

                const esImagen = ['jpg', 'jpeg', 'png'].includes(extension);
                const esPdf = extension === 'pdf';

                let vistaPrevia = '';

                if (esImagen) {
                    vistaPrevia = `
                        <div class="documento-preview"
                            style="
                                display:none;
                                position:absolute;
                                z-index:9999;
                                right:100%;
                                top:50%;
                                transform:translateY(-50%);
                                margin-right:8px;
                                width:220px;
                                padding:6px;
                                background:#fff;
                                border:1px solid #dee2e6;
                                border-radius:6px;
                                box-shadow:0 4px 14px rgba(0,0,0,.15);
                            ">
                            <img
                                src="${urlDocumento}"
                                alt="${escaparHTML(nombreOriginal)}"
                                style="display:block;width:100%;max-height:180px;object-fit:contain;border-radius:4px;"
                            >
                            <div class="text-muted small text-center mt-1 text-truncate">
                                ${escaparHTML(nombreOriginal)}
                            </div>
                        </div>
                    `;
                } else if (esPdf) {
                    vistaPrevia = `
                        <div class="documento-preview"
                            style="
                                display:none;
                                position:absolute;
                                z-index:9999;
                                right:100%;
                                top:50%;
                                transform:translateY(-50%);
                                margin-right:8px;
                                width:260px;
                                height:220px;
                                padding:4px;
                                background:#fff;
                                border:1px solid #dee2e6;
                                border-radius:6px;
                                box-shadow:0 4px 14px rgba(0,0,0,.15);
                                overflow:hidden;
                            ">
                            <iframe
                                src="${urlDocumento}"
                                title="${escaparHTML(nombreOriginal)}"
                                style="width:100%;height:100%;border:0;"
                            ></iframe>
                        </div>
                    `;
                } else {
                    vistaPrevia = `
                        <div class="documento-preview"
                            style="
                                display:none;
                                position:absolute;
                                z-index:9999;
                                right:100%;
                                top:50%;
                                transform:translateY(-50%);
                                margin-right:8px;
                                width:220px;
                                padding:18px 10px;
                                background:#fff;
                                border:1px solid #dee2e6;
                                border-radius:6px;
                                box-shadow:0 4px 14px rgba(0,0,0,.15);
                                text-align:center;
                            ">
                            <i class="bi bi-file-earmark-text" style="font-size:48px;"></i>
                            <div class="small fw-semibold mt-2 text-break">
                                ${escaparHTML(nombreOriginal)}
                            </div>
                            <div class="text-muted small mt-1">
                                ${escaparHTML(extension.toUpperCase())}
                            </div>
                        </div>
                    `;
                }

                container.insertAdjacentHTML('beforeend', `
                    <div class="border rounded p-2 mb-2 bg-white">
                        <div class="d-flex align-items-start gap-2">
                            <div class="fs-4 text-primary">📄</div>

                            <div class="flex-grow-1">
                                <div class="fw-semibold text-break">
                                    ${escaparHTML(nombreOriginal)}
                                </div>

                                <div class="text-muted small">
                                    ${escaparHTML(doc.extension || '')}
                                    ${tamano ? ' · ' + escaparHTML(tamano) : ''}
                                </div>

                                <div class="text-muted small">
                                    ${escaparHTML(fecha)}
                                    ${doc.usuario_nombre
                                        ? ' · ' + escaparHTML(doc.usuario_nombre)
                                        : ''}
                                </div>

                                <div class="text-muted small mt-1">
                                    <i class="bi bi-folder2-open me-1"></i>
                                    ${escaparHTML(origen)}
                                </div>
                            </div>

                            <div class="position-relative" style="z-index:1000;">
                                ${vistaPrevia}

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary btn-ver-documento"
                                    data-documento-id="${escaparHTML(doc.id)}"
                                    data-url-documento="${escaparHTML(urlDocumento)}"
                                >
                                    <i class="bi bi-eye me-1"></i>
                                    Ver
                                </button>
                            </div>
                        </div>
                    </div>
                `);
            });

        } catch (error) {
            console.error(error);

            contenedores.forEach(tipo => {
                const container = obtenerContenedor(tipo);

                if (!container) return;

                container.innerHTML = `
                    <div class="text-danger small text-center py-3">
                        Error al cargar los documentos.
                    </div>
                `;
            });
        }
    }

    // =====================================================
    // MOSTRAR / OCULTAR MINIATURAS
    // =====================================================

    document.addEventListener('mouseover', event => {
        const boton = event.target.closest('.btn-ver-documento');

        if (!boton) return;

        const contenedor = boton.parentElement;
        const preview = contenedor?.querySelector('.documento-preview');

        if (preview) {
            preview.style.display = 'block';
        }
    });

    document.addEventListener('mouseout', event => {
        const boton = event.target.closest('.btn-ver-documento');

        if (!boton) return;

        if (
            event.relatedTarget &&
            boton.contains(event.relatedTarget)
        ) {
            return;
        }

        const contenedor = boton.parentElement;
        const preview = contenedor?.querySelector('.documento-preview');

        if (preview) {
            preview.style.display = 'none';
        }
    });

    // =====================================================
    // VER DOCUMENTO
    // =====================================================

    document.addEventListener('click', event => {
        const boton = event.target.closest('.btn-ver-documento');

        if (!boton) return;

        const url = boton.dataset.urlDocumento;

        if (!url) {
            toastr.error('No se encontro la ruta del documento.');
            return;
        }

        window.open(url, '_blank');
    });

    // =====================================================
    // ABRIR DOCUMENTOS
    // =====================================================

    btnDocumentos?.addEventListener('click', cargarDocumentos);

    // =====================================================
    // VOLVER AL REGISTRO
    // =====================================================

    btnVolver?.addEventListener('click', () => {
        if (!erpPanels) return;

        erpPanels.style.transform = 'translateX(0)';
    });

    // =====================================================
    // MOSTRAR PANEL DE CARGA
    // =====================================================

    btnSubirDocumento?.addEventListener('click', () => {
        panelCargaDocumento?.classList.remove('d-none');
    });

    // =====================================================
    // CANCELAR CARGA
    // =====================================================

    btnCancelarDocumento?.addEventListener('click', () => {
        limpiarArchivosSeleccionados();

        if (selectDocumentoTipo) {
            selectDocumentoTipo.value = '';
        }

        panelCargaDocumento?.classList.add('d-none');
    });

    // =====================================================
    // ENVIAR DOCUMENTOS
    // =====================================================

    btnConfirmarDocumento?.addEventListener('click', async () => {
        const tipo = selectDocumentoTipo?.value || '';
        const archivos = [...archivosDocumentoSeleccionados];

        if (!tipo) {
            toastr.warning('Selecciona el tipo de documento.');
            selectDocumentoTipo?.focus();
            return;
        }

        if (!archivos.length) {
            toastr.warning('Selecciona al menos un archivo.');
            btnSeleccionarDocumentos?.focus();
            return;
        }

                if (!archivos.length) {
            toastr.warning('Selecciona al menos un archivo.');
            btnSeleccionarDocumentos?.focus();
            return;
        }

        // =====================================================
        // VALIDAR ARCHIVOS QUE SUPERAN LOS 5 MB
        // =====================================================
        const archivosPesados = archivos.filter(archivo => {
            const estado = estadoCompresionDocumentos.get(archivo);

            return estado
                ? estado.errorPeso || archivo.size > maximoBytes
                : archivo.size > maximoBytes;
        });

        if (archivosPesados.length) {
            toastr.warning(
                'No se pueden subir los archivos marcados con !. ' +
                'Elimínalos de la selección para continuar.'
            );
            return;
        }

        const registroId = obtenerRegistroId();

        if (!registroId) {
            toastr.error('No se encontro el registro.');
            return;
        }

        const textoOriginal = btnConfirmarDocumento.innerHTML;
        const archivosFallidos = [];
        let archivosGuardados = 0;

        btnConfirmarDocumento.disabled = true;

        try {
            for (let i = 0; i < archivos.length; i++) {
                const archivo = archivos[i];

                btnConfirmarDocumento.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                    Enviando ${i + 1} de ${archivos.length}...
                `;

                const formData = new FormData();
                formData.append('modulo', modulo);
                formData.append('registro_id', registroId);
                formData.append('tipo', tipo);
                formData.append('archivo', archivo);

                try {
                    const url = `${BASE_URL}${modulo}/documentos/subir`;

                    const response = await fetch(url, {
                        method: 'POST',
                        body: formData
                    });

                    const json = await response.json();

                    if (!response.ok || !json.success) {
                        throw new Error(
                            json.message || 'No fue posible subir el documento.'
                        );
                    }

                    archivosGuardados++;

                    // Quitar de la lista solo el archivo guardado.
                    archivosDocumentoSeleccionados =
                        archivosDocumentoSeleccionados.filter(
                            seleccionado => seleccionado !== archivo
                        );

                    renderizarArchivosDocumento();

                } catch (error) {
                    console.error(
                        `Error al subir ${archivo.name}:`,
                        error
                    );

                    archivosFallidos.push({
                        archivo,
                        mensaje: error.message
                    });
                }
            }

            if (archivosGuardados > 0) {
                await cargarDocumentos();
            }

            if (archivosFallidos.length === 0) {
                toastr.success(
                    `Se guardaron ${archivosGuardados} archivo(s) correctamente.`
                );

                limpiarArchivosSeleccionados();

                if (selectDocumentoTipo) {
                    selectDocumentoTipo.value = '';
                }

                panelCargaDocumento?.classList.add('d-none');

            } else {
                toastr.warning(
                    `Se guardaron ${archivosGuardados} de ${archivos.length}. ` +
                    `${archivosFallidos.length} archivo(s) no se pudieron guardar.`
                );

                console.error(
                    'Archivos que no se guardaron:',
                    archivosFallidos
                );
            }

        } catch (error) {
            console.error(error);

            toastr.error(
                error.message || 'Error al enviar los documentos.'
            );

        } finally {
            btnConfirmarDocumento.disabled = false;
            btnConfirmarDocumento.innerHTML = textoOriginal;
        }
    });

    // =====================================================
    // FUNCIONES PUBLICAS
    // =====================================================

    return {
        cargarDocumentos,
        formatearTamanoDocumento
    };
}