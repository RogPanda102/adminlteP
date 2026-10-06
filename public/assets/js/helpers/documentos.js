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

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary">
                                    Ver
                                </button>

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