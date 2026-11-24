/**
 * =========================================================
 * PREVISUALIZACIÓN REUTILIZABLE DE REGISTROS
 * =========================================================
 */

function inicializarPrevisualizacion(configuracion) {

    const formulario = document.getElementById(configuracion.formulario);
    const botonPrevisualizar = document.getElementById(configuracion.boton);
    const botonConfirmar = document.getElementById(configuracion.confirmar);
    const contenido = document.getElementById(configuracion.contenido);
    const elementoModal = document.getElementById(configuracion.modal);

    if (
        !formulario ||
        !botonPrevisualizar ||
        !botonConfirmar ||
        !contenido ||
        !elementoModal
    ) {
        console.error('No se encontraron los elementos de previsualización.');
        return;
    }

    const modal = new bootstrap.Modal(elementoModal);

    // Obtener el valor de un campo por su nombre.
    function obtenerValor(nombre) {

        const campo = formulario.querySelector(`[name="${nombre}"]`);

        if (!campo) {
            return '';
        }

        if (campo.type === 'checkbox') {
            return campo.checked ? 'Sí' : 'No';
        }

        if (campo.tagName === 'SELECT') {
            return campo.options[campo.selectedIndex]?.text || '';
        }

        return campo.value.trim() || 'No especificado';
    }

    // Evitar que los datos introducidos se interpreten como HTML.
    function escaparHTML(valor) {

        return String(valor)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }

    // Crear una fila del reporte.
    function crearDato(etiqueta, valor) {

        return `
            <div class="col-md-6 mb-3">

                <div class="border-bottom pb-2">

                    <small class="text-muted d-block mb-1">
                        ${escaparHTML(etiqueta)}
                    </small>

                    <div class="fw-semibold text-dark">
                        ${escaparHTML(valor)}
                    </div>

                </div>

            </div>
        `;

    }

    // Crear una sección del reporte.
    function crearSeccion(titulo, icono, clase, campos) {

        let html = `
            <div class="mb-4">

                <h6 class="fw-bold ${clase} border-bottom pb-2 mb-3">

                    <i class="bi ${icono} me-2"></i>

                    ${escaparHTML(titulo)}

                </h6>

                <div class="row">
        `;

        campos.forEach(campo => {

            html += crearDato(
                campo.etiqueta,
                obtenerValor(campo.nombre)
            );

        });

        html += `
                </div>

            </div>
        `;

        return html;

    }

    // Generar el reporte.
    function generarReporte() {

        let html = '';

        configuracion.secciones.forEach(seccion => {

            html += crearSeccion(
                seccion.titulo,
                seccion.icono,
                seccion.clase,
                seccion.campos
            );

        });

        contenido.innerHTML = html;

    }

    // Abrir previsualización.
    botonPrevisualizar.addEventListener('click', function () {

        if (!formulario.reportValidity()) {
            return;
        }

        generarReporte();

        modal.show();

    });

    // Confirmar y enviar formulario.
    botonConfirmar.addEventListener('click', function () {

        botonConfirmar.disabled = true;

        botonConfirmar.innerHTML = `
            <span class="spinner-border spinner-border-sm me-1"></span>
            Guardando...
        `;

        formulario.submit();

    });

}