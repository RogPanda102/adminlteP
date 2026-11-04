
/**
 * INDICADOR DE PROGRESO DEL EXPEDIENTE
 *
 * Helper global reutilizable para Cotizaciones, Adjudicados y Servicios.
 *
 * Configuración:
 * {
 *     contenedor: '#progreso-expediente',
 *     url: `${BASE_URL}cotizaciones/progresoExpedienteAjax`,
 *     id: 1017,
 *     tipo: 'cotizacion'
 * }
 */

async function cargarIndicadorProgreso(config)
{
    const contenedor = document.querySelector(config.contenedor);

    if (!contenedor || !config.url || !config.id) return;

    // =====================================================
    // DEFINIR TIPO DE REGISTRO
    // =====================================================

    const tipo = config.tipo || 'cotizacion';

    // =====================================================
    // ESTADO DE CARGA
    // =====================================================

    contenedor.innerHTML = `
        <div class="text-muted small text-center py-3">
            <span class="spinner-border spinner-border-sm me-2"></span>
            Consultando progreso...
        </div>
    `;

    try {

        // =====================================================
        // CONSULTAR PROGRESO
        // =====================================================

        const separador = config.url.includes('?') ? '&' : '?';

        const parametros = new URLSearchParams({
            id: config.id,
            tipo: tipo
        });

        const respuesta = await fetch(
            `${config.url}${separador}${parametros.toString()}`
        );

        const json = await respuesta.json();

        if (!respuesta.ok || !json.ok) {
            throw new Error(
                json.mensaje || 'No se pudo consultar el progreso'
            );
        }

        const progreso = json.progreso;

        // =====================================================
        // DEFINIR ETAPA ACTUAL
        // =====================================================

        const etapas = [
            { nombre: 'Cotización' },
            { nombre: 'Adjudicación' },
            { nombre: 'Servicio' }
        ];

        let etapaActual = 0;

        if (progreso.servicio) {
            etapaActual = 2;
        } else if (progreso.adjudicacion) {
            etapaActual = 1;
        }

        // =====================================================
        // GENERAR PASOS
        // =====================================================

        const pasosHTML = etapas.map((etapa, index) => {

            let clase = 'pendiente';

            if (index < etapaActual) {
                clase = 'completado';
            } else if (index === etapaActual) {
                clase = 'actual';
            }

            return `
                <div class="expediente-paso ${clase}">

                    <div class="expediente-paso-circulo">
                        ${
                            index < etapaActual
                                ? '<i class="bi bi-check-lg"></i>'
                                : index + 1
                        }
                    </div>

                    <div class="expediente-paso-nombre">
                        ${etapa.nombre}
                    </div>

                </div>
            `;

        }).join('');

        // =====================================================
        // RENDERIZAR INDICADOR
        // =====================================================

        contenedor.innerHTML = `
            <div class="expediente-resumen">

                <div class="expediente-resumen-encabezado">

                    <div>
                        <div class="expediente-resumen-titulo">
                            Progreso del expediente
                        </div>

                        <div class="expediente-resumen-subtitulo">
                            Seguimiento de etapas
                        </div>
                    </div>

                    <span class="expediente-etapa-actual">
                        ${etapas[etapaActual].nombre}
                    </span>

                </div>

                <div class="expediente-progreso">
                    ${pasosHTML}
                </div>

            </div>
        `;

    } catch (error) {

        console.error('Error al cargar indicador de progreso:', error);

        contenedor.innerHTML = `
            <div class="alert alert-warning small mb-3">
                No fue posible consultar el progreso del expediente.
            </div>
        `;

    }
}
