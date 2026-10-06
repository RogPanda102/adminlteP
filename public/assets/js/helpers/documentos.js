// =====================================================
// HELPER GENERICO DE DOCUMENTOS
// =====================================================

function inicializarDocumentos(config = {}) {

    // =====================================================
    // CONFIGURACIÓN
    // =====================================================

    const modulo = config.modulo || '';
    const obtenerRegistroId = config.obtenerRegistroId || (() => null);
    const obtenerRegistrosRelacionados =
        config.obtenerRegistrosRelacionados || (() => []);

    const btnDocumentosId =
        config.btnDocumentosId || 'btn-documentos';

    const btnVolverId =
        config.btnVolverId || '';

    const erpPanelsId =
        config.erpPanelsId || 'erp-panels';

    const btnSubirDocumentoId =
        config.btnSubirDocumentoId || 'btn-subir-documento';

    const btnCancelarDocumentoId =
        config.btnCancelarDocumentoId || 'btn-cancelar-documento';

    const panelCargaDocumentoId =
        config.panelCargaDocumentoId || 'panel-carga-documento';

    const inputDocumentoArchivoId =
        config.inputDocumentoArchivoId || 'documento-archivo';

    const infoDocumentoArchivoId =
        config.infoDocumentoArchivoId || 'info-documento-archivo';

    const selectDocumentoTipoId =
        config.selectDocumentoTipoId || 'documento-tipo';

    const btnConfirmarDocumentoId =
        config.btnConfirmarDocumentoId || 'btn-confirmar-documento';


    // =====================================================
    // ELEMENTOS
    // =====================================================

    const btnDocumentos =
        document.getElementById(btnDocumentosId);

    const btnVolver =
        document.getElementById(btnVolverId);

    const erpPanels =
        document.getElementById(erpPanelsId);

    const btnSubirDocumento =
        document.getElementById(btnSubirDocumentoId);

    const btnCancelarDocumento =
        document.getElementById(btnCancelarDocumentoId);

    const panelCargaDocumento =
        document.getElementById(panelCargaDocumentoId);

    const inputDocumentoArchivo =
        document.getElementById(inputDocumentoArchivoId);

    const infoDocumentoArchivo =
        document.getElementById(infoDocumentoArchivoId);

    const selectDocumentoTipo =
        document.getElementById(selectDocumentoTipoId);

    const btnConfirmarDocumento =
        document.getElementById(btnConfirmarDocumentoId);


    // =====================================================
    // TIPOS DE DOCUMENTOS
    // =====================================================

    const contenedores = [
        'solicitud',
        'cotizacion',
        'evidencia',
        'factura',
        'entrega',
        'otro'
    ];


    // =====================================================
    // FORMATEAR TAMAÑO
    // =====================================================

    function formatearTamanoDocumento(bytes) {

        bytes = Number(bytes);

        if (!bytes || bytes <= 0) {
            return '0 B';
        }

        const unidades = [
            'B',
            'KB',
            'MB',
            'GB'
        ];

        const indice = Math.floor(
            Math.log(bytes) / Math.log(1024)
        );

        const posicion = Math.min(
            indice,
            unidades.length - 1
        );

        return (
            bytes / Math.pow(1024, posicion)
        ).toFixed(posicion === 0 ? 0 : 2)
            + ' '
            + unidades[posicion];
    }


    // =====================================================
    // OBTENER CONTENEDOR
    // =====================================================

    function obtenerContenedor(tipo) {

        return document.getElementById(
            `documentos-${tipo}`
        );
    }


    // =====================================================
    // MOSTRAR CARGANDO
    // =====================================================

    function mostrarCargando() {

        contenedores.forEach(tipo => {

            const container =
                obtenerContenedor(tipo);

            if (!container) return;

            container.innerHTML = `
                <div class="text-muted small text-center py-3">
                    Cargando documentos...
                </div>
            `;
        });
    }


    // =====================================================
    // LIMPIAR CONTENEDORES
    // =====================================================

    function limpiarContenedores() {

        contenedores.forEach(tipo => {

            const container =
                obtenerContenedor(tipo);

            if (!container) return;

            container.innerHTML = '';
        });
    }


    // =====================================================
    // MOSTRAR SIN DOCUMENTOS
    // =====================================================

    function mostrarSinDocumentos() {

        contenedores.forEach(tipo => {

            const container =
                obtenerContenedor(tipo);

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

        const registroId =
            obtenerRegistroId();

        if (!registroId) {

            toastr.error(
                'No se encontró el registro.'
            );

            return;
        }


        // ----------------------------------
        // Mostrar panel
        // ----------------------------------

        if (erpPanels) {
            erpPanels.style.transform = 'translateX(-50%)';
        }


        // ----------------------------------
        // Mostrar loading
        // ----------------------------------

        mostrarCargando();


        try {

            let documentos = [];


            // ==========================================
            // DOCUMENTOS DEL REGISTRO PRINCIPAL
            // ==========================================

            const urlPrincipal =
                `${BASE_URL}${modulo}/documentos` +
                `?modulo=${modulo}` +
                `&registro_id=${registroId}`;

            const responsePrincipal =
                await fetch(urlPrincipal);

            const jsonPrincipal =
                await responsePrincipal.json();


            if (!jsonPrincipal.success) {

                throw new Error(
                    jsonPrincipal.message ||
                    'Error al cargar los documentos.'
                );
            }


            documentos = [
                ...(jsonPrincipal.data || [])
            ];


            // ==========================================
            // DOCUMENTOS RELACIONADOS
            // ==========================================

            const relacionados =
                obtenerRegistrosRelacionados() || [];


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
                    `?modulo=${relacionado.modulo}` +
                    `&registro_id=${relacionado.registro_id}`;


                const responseRelacionado =
                    await fetch(urlRelacionado);

                const jsonRelacionado =
                    await responseRelacionado.json();


                if (!jsonRelacionado.success) {

                    throw new Error(
                        jsonRelacionado.message ||
                        'Error al cargar documentos relacionados.'
                    );
                }


                documentos = [
                    ...documentos,
                    ...(jsonRelacionado.data || [])
                ];
            }


            // ----------------------------------
            // Limpiar
            // ----------------------------------

            limpiarContenedores();


            // ----------------------------------
            // Sin documentos
            // ----------------------------------

            if (!documentos.length) {

                mostrarSinDocumentos();

                return;
            }


            // ==========================================
            // MOSTRAR DOCUMENTOS
            // ==========================================

            documentos.forEach(doc => {

                const container =
                    obtenerContenedor(doc.tipo);

                if (!container) return;


                const fecha =
                    doc.fecha_creacion
                        ? new Date(
                            doc.fecha_creacion
                        ).toLocaleString('es-MX')
                        : '';


                const tamano =
                    doc.tamano
                        ? formatearTamanoDocumento(
                            doc.tamano
                        )
                        : '';


                // ----------------------------------
                // ORIGEN
                // ----------------------------------

                let origen =
                    config.obtenerOrigen
                        ? config.obtenerOrigen(doc)
                        : doc.modulo;


                if (!origen) {
                    origen = 'Documento';
                }


                // ----------------------------------
                // URL PARA VER DOCUMENTO
                // ----------------------------------

                const urlDocumento =
                    `${BASE_URL}${doc.modulo}/documentos/ver` +
                    `?id=${encodeURIComponent(doc.id)}`;


                // ----------------------------------
                // EXTENSIÓN
                // ----------------------------------

                const extension =
                    (doc.extension || '')
                        .toLowerCase();


                // ----------------------------------
                // TIPO DE PREVISUALIZACIÓN
                // ----------------------------------

                const esImagen =
                    [
                        'jpg',
                        'jpeg',
                        'png'
                    ].includes(extension);


                const esPdf =
                    extension === 'pdf';


                let vistaPrevia = '';


                if (esImagen) {

                    vistaPrevia = `
                        <div
                            class="documento-preview"
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
                            "
                        >
                            <img
                                src="${urlDocumento}"
                                alt="${doc.nombre_original || 'Vista previa'}"
                                style="
                                    display:block;
                                    width:100%;
                                    max-height:180px;
                                    object-fit:contain;
                                    border-radius:4px;
                                "
                            >

                            <div
                                class="text-muted small text-center mt-1 text-truncate"
                                title="${doc.nombre_original || ''}"
                            >
                                ${doc.nombre_original || ''}
                            </div>
                        </div>
                    `;

                } else if (esPdf) {

                    vistaPrevia = `
                        <div
                            class="documento-preview"
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
                            "
                        >
                            <iframe
                                src="${urlDocumento}"
                                title="${doc.nombre_original || 'Vista previa PDF'}"
                                style="
                                    width:100%;
                                    height:100%;
                                    border:0;
                                "
                            ></iframe>
                        </div>
                    `;

                } else {

                    vistaPrevia = `
                        <div
                            class="documento-preview"
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
                            "
                        >
                            <i
                                class="bi bi-file-earmark-text"
                                style="font-size:48px;"
                            ></i>

                            <div class="small fw-semibold mt-2 text-break">
                                ${doc.nombre_original || ''}
                            </div>

                            <div class="text-muted small mt-1">
                                ${extension.toUpperCase()}
                            </div>
                        </div>
                    `;
                }


                // ----------------------------------
                // HTML
                // ----------------------------------

                container.insertAdjacentHTML(
                    'beforeend',
                    `
                        <div class="border rounded p-2 mb-2 bg-white">

                            <div class="d-flex align-items-start gap-2">

                                <div class="fs-4 text-primary">
                                    📄
                                </div>

                                <div class="flex-grow-1">

                                    <div class="fw-semibold text-break">
                                        ${doc.nombre_original || ''}
                                    </div>

                                    <div class="text-muted small">
                                        ${doc.extension || ''}
                                        ${
                                            tamano
                                                ? ' · ' + tamano
                                                : ''
                                        }
                                    </div>

                                    <div class="text-muted small">
                                        ${fecha}
                                        ${
                                            doc.usuario_nombre
                                                ? ' · ' + doc.usuario_nombre
                                                : ''
                                        }
                                    </div>

                                    <div class="text-muted small mt-1">
                                        <i class="bi bi-folder2-open me-1"></i>
                                        ${origen}
                                    </div>

                                </div>

                                <div
                                    class="position-relative"
                                    style="z-index:1000;"
                                >

                                    ${vistaPrevia}

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-outline-primary btn-ver-documento"
                                        data-documento-id="${doc.id}"
                                        data-url-documento="${urlDocumento}"
                                    >
                                        <i class="bi bi-eye me-1"></i>
                                        Ver
                                    </button>

                                </div>

                            </div>

                        </div>
                    `
                );
            });


        } catch (error) {

            console.error(error);

            contenedores.forEach(tipo => {

                const container =
                    obtenerContenedor(tipo);

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
    // MOSTRAR / OCULTAR MINIATURA
    // =====================================================

    document.addEventListener(
        'mouseenter',
        function (event) {

            const boton =
                event.target.closest(
                    '.btn-ver-documento'
                );

            if (!boton) return;


            const contenedor =
                boton.parentElement;


            const preview =
                contenedor?.querySelector(
                    '.documento-preview'
                );


            if (!preview) return;


            preview.style.display =
                'block';
        },
        true
    );


    document.addEventListener(
        'mouseleave',
        function (event) {

            const boton =
                event.target.closest(
                    '.btn-ver-documento'
                );

            if (!boton) return;


            const contenedor =
                boton.parentElement;


            const preview =
                contenedor?.querySelector(
                    '.documento-preview'
                );


            if (!preview) return;


            preview.style.display =
                'none';
        },
        true
    );


    // =====================================================
    // VER DOCUMENTO
    // =====================================================

    document.addEventListener(
        'click',
        function (event) {

            const boton =
                event.target.closest(
                    '.btn-ver-documento'
                );

            if (!boton) return;


            const url =
                boton.dataset.urlDocumento;


            if (!url) {

                toastr.error(
                    'No se encontró la ruta del documento.'
                );

                return;
            }


            window.open(
                url,
                '_blank'
            );
        }
    );


    // =====================================================
    // ABRIR DOCUMENTOS
    // =====================================================

    btnDocumentos?.addEventListener(
        'click',
        cargarDocumentos
    );


    // =====================================================
    // VOLVER AL REGISTRO
    // =====================================================

    btnVolver?.addEventListener(
        'click',
        function () {

            if (!erpPanels) return;

            erpPanels.style.transform =
                'translateX(0)';
        }
    );


    // =====================================================
    // MOSTRAR CARGA DE DOCUMENTO
    // =====================================================

    btnSubirDocumento?.addEventListener(
        'click',
        function () {

            if (!panelCargaDocumento) return;

            panelCargaDocumento.classList.remove(
                'd-none'
            );
        }
    );


    // =====================================================
    // CANCELAR CARGA
    // =====================================================

    btnCancelarDocumento?.addEventListener(
        'click',
        function () {

            if (!panelCargaDocumento) return;


            if (inputDocumentoArchivo) {
                inputDocumentoArchivo.value = '';
            }


            if (infoDocumentoArchivo) {

                infoDocumentoArchivo.innerHTML = '';

                infoDocumentoArchivo.classList.add(
                    'd-none'
                );
            }


            panelCargaDocumento.classList.add(
                'd-none'
            );
        }
    );


    // =====================================================
    // SELECCIONAR ARCHIVO
    // =====================================================

    inputDocumentoArchivo?.addEventListener(
        'change',
        function () {

            if (!infoDocumentoArchivo) return;


            const archivo =
                this.files?.[0];


            if (!archivo) {

                infoDocumentoArchivo.classList.add(
                    'd-none'
                );

                infoDocumentoArchivo.innerHTML = '';

                return;
            }


            const nombre =
                archivo.name;


            const tamano =
                formatearTamanoDocumento(
                    archivo.size
                );


            infoDocumentoArchivo.innerHTML = `
                <div class="border rounded bg-light p-2">

                    <div class="d-flex align-items-center gap-2">

                        <i class="bi bi-file-earmark text-primary fs-4"></i>

                        <div class="flex-grow-1">

                            <div class="fw-semibold text-break">
                                ${nombre}
                            </div>

                            <div class="text-muted small">
                                ${tamano}
                            </div>

                        </div>

                    </div>

                </div>
            `;


            infoDocumentoArchivo.classList.remove(
                'd-none'
            );
        }
    );


    // =====================================================
    // ENVIAR DOCUMENTO
    // =====================================================

    btnConfirmarDocumento?.addEventListener(
        'click',
        async function () {

            const tipo =
                selectDocumentoTipo?.value || '';


            const archivo =
                inputDocumentoArchivo?.files?.[0];


            // ----------------------------------
            // Validar tipo
            // ----------------------------------

            if (!tipo) {

                toastr.warning(
                    'Selecciona el tipo de documento.'
                );

                selectDocumentoTipo?.focus();

                return;
            }


            // ----------------------------------
            // Validar archivo
            // ----------------------------------

            if (!archivo) {

                toastr.warning(
                    'Selecciona un archivo.'
                );

                inputDocumentoArchivo?.focus();

                return;
            }


            // ----------------------------------
            // Registro
            // ----------------------------------

            const registroId =
                obtenerRegistroId();


            if (!registroId) {

                toastr.error(
                    'No se encontró el registro.'
                );

                return;
            }


            // ----------------------------------
            // FormData
            // ----------------------------------

            const formData =
                new FormData();


            formData.append(
                'modulo',
                modulo
            );


            formData.append(
                'registro_id',
                registroId
            );


            formData.append(
                'tipo',
                tipo
            );


            formData.append(
                'archivo',
                archivo
            );


            // ----------------------------------
            // Estado botón
            // ----------------------------------

            const textoOriginal =
                btnConfirmarDocumento.innerHTML;


            btnConfirmarDocumento.disabled =
                true;


            btnConfirmarDocumento.innerHTML = `
                <span
                    class="spinner-border spinner-border-sm me-1"
                    role="status">
                </span>
                Enviando...
            `;


            try {

                const url =
                    `${BASE_URL}${modulo}/documentos/subir`;


                const response =
                    await fetch(
                        url,
                        {
                            method: 'POST',
                            body: formData
                        }
                    );


                const json =
                    await response.json();


                // ----------------------------------
                // Error
                // ----------------------------------

                if (!json.success) {

                    throw new Error(
                        json.message ||
                        'No fue posible subir el documento.'
                    );
                }


                // ----------------------------------
                // Éxito
                // ----------------------------------

                toastr.success(
                    json.message ||
                    'Documento enviado correctamente.'
                );


                // ----------------------------------
                // Limpiar formulario
                // ----------------------------------

                if (selectDocumentoTipo) {
                    selectDocumentoTipo.value = '';
                }


                if (inputDocumentoArchivo) {
                    inputDocumentoArchivo.value = '';
                }


                if (infoDocumentoArchivo) {

                    infoDocumentoArchivo.innerHTML = '';

                    infoDocumentoArchivo.classList.add(
                        'd-none'
                    );
                }


                // ----------------------------------
                // Ocultar carga
                // ----------------------------------

                panelCargaDocumento?.classList.add(
                    'd-none'
                );


                // ----------------------------------
                // Recargar documentos
                // ----------------------------------

                await cargarDocumentos();


            } catch (error) {

                console.error(error);

                toastr.error(
                    error.message ||
                    'Error al enviar el documento.'
                );


            } finally {

                btnConfirmarDocumento.disabled =
                    false;


                btnConfirmarDocumento.innerHTML =
                    textoOriginal;
            }
        }
    );


    // =====================================================
    // RETORNAR FUNCIONES PÚBLICAS
    // =====================================================

    return {
        cargarDocumentos,
        formatearTamanoDocumento
    };
}