
document.addEventListener('DOMContentLoaded', () => {

    const contenedor = document.getElementById('tabla-historial');
    const buscador = document.getElementById('buscar-historial');

    if (!contenedor || typeof Tabulator === 'undefined') {
        return;
    }

    const historial = window.historial || [];

    // =========================
    // Formatear fecha
    // =========================
    function formatearFecha(valor, hora12 = false) {

        if (
            !valor ||
            valor === '0000-00-00' ||
            valor === '0000-00-00 00:00:00'
        ) {
            return 'Sin valor';
        }

        const coincidencia = String(valor).match(
            /^(\d{4})-(\d{2})-(\d{2})(?:[ T](\d{2}):(\d{2})(?::(\d{2}))?)?$/
        );

        if (!coincidencia) {
            return String(valor);
        }

        const [, anio, mes, dia, hora, minuto] = coincidencia;

        if (!hora) {
            return `${dia}/${mes}/${anio}`;
        }

        if (hora12) {

            let horas = Number(hora);
            const periodo = horas >= 12 ? 'p. m.' : 'a. m.';

            horas = horas % 12 || 12;

            return `${dia}/${mes}/${anio} · ${String(horas).padStart(2, '0')}:${minuto} ${periodo}`;
        }

        return `${dia}/${mes}/${anio} · ${hora}:${minuto}`;
    }

    // =========================
    // Formatear acción
    // =========================
    function formatearAccion(celda) {

        const accion = celda.getValue();

        const etiquetas = {
            INSERT: 'Creación',
            UPDATE: 'Modificación',
            DELETE: 'Eliminación'
        };

        const colores = {
            INSERT: 'success',
            UPDATE: 'primary',
            DELETE: 'danger'
        };

        const etiqueta = document.createElement('span');

        etiqueta.className =
            `badge text-bg-${colores[accion] || 'secondary'}`;

        etiqueta.textContent =
            etiquetas[accion] || accion || 'Sin acción';

        return etiqueta;
    }


    function obtenerCambios(movimiento) {

    let anteriores = {};
    let nuevos = {};

    try {
        anteriores = JSON.parse(movimiento.datos_anteriores || '{}');
        nuevos = JSON.parse(movimiento.datos_nuevos || '{}');

    } catch (error) {
        console.error('Error al interpretar los datos:', error);
        return [];
    }

    const cambios = [];

    Object.keys(nuevos).forEach(campo => {

        const anterior = anteriores[campo] ?? null;
        const nuevo = nuevos[campo] ?? null;

        const anteriorFormateado = formatearValor(anterior, campo);
        const nuevoFormateado = formatearValor(nuevo, campo);

        if (anteriorFormateado !== nuevoFormateado) {

            cambios.push({
                campo,
                anterior,
                nuevo
            });

        }

    });

    return cambios;
}


    // =========================
    // Nombres legibles de campos
    // =========================
    function nombreCampo(campo) {

        const nombres = {
            req: 'Requisición',
            folio: 'Folio',
            elaboro: 'Elaboró',
            partida: 'Partida',
            analista: 'Analista',
            analista_id: 'ID del analista',
            dependencia_id: 'Dependencia',
            fecha_elaboracion: 'Fecha de elaboración',
            fecha_inicio_entrega: 'Inicio de entrega',
            fecha_fin_entrega: 'Fin de entrega',
            total: 'Total',
            dia_pago: 'Día de pago',
            pago: 'Estado de pago',
            cotizacion_id: 'Cotización relacionada',
            anio: 'Año',
            actualizado_por: 'Actualizado por',
            eliminado: 'Estado del registro',
            fecha_actualizacion: 'Fecha de actualización',
            fecha_creacion: 'Fecha de creación',
            creado_por: 'Creado por',
        };

        return nombres[campo] || campo
            .replace(/_/g, ' ')
            .replace(/\b\w/g, letra => letra.toUpperCase());
    }

    function obtenerNombreUsuario(usuarioId) {

        const usuario = (window.usuarios || []).find(
            usuario => Number(usuario.id) === Number(usuarioId)
        );

        if (!usuario) {
            return `Usuario #${usuarioId}`;
        }

        return usuario.nombre_completo ||
            [
                usuario.nombre,
                usuario.apellido_paterno,
                usuario.apellido_materno
            ].filter(Boolean).join(' ') ||
            usuario.nick ||
            `Usuario #${usuarioId}`;
    }

    function formatearValor(valor, campo = '') {

        const camposRelacionados = [
            'analista_id',
            'dependencia_id',
            'cotizacion_id',
            'actualizado_por',
            'creado_por'
        ];

        if (camposRelacionados.includes(campo)) {

            if (
                valor === null ||
                valor === undefined ||
                valor === '' ||
                Number(valor) === 0
            ) {
                return 'Sin asignar';
            }

            if (
                campo === 'actualizado_por' ||
                campo === 'creado_por'
            ) {
                return obtenerNombreUsuario(valor);
            }
        }

        // Formato de fechas
        if (
            campo.startsWith('fecha_') ||
            campo === 'inicio' ||
            campo === 'finalizacion'
        ) {
            return formatearFecha(valor);
        }

        if (
            valor === null ||
            valor === undefined ||
            valor === '' ||
            valor === '0000-00-00' ||
            valor === '0000-00-00 00:00:00'
        ) {
            return 'Sin valor';
        }

        if (typeof valor === 'object') {
            return JSON.stringify(valor);
        }

        return String(valor);
    }

    // =========================
    // Mostrar detalle del movimiento
    // =========================
    function mostrarDetalle(movimiento) {

        const modalElemento = document.getElementById('modalDetalleHistorial');
        const contenido = document.getElementById('detalle-historial-contenido');
        const subtitulo = document.getElementById('detalle-historial-subtitulo');

        if (!modalElemento || !contenido || !subtitulo) {
            return;
        }

        const accion = movimiento.accion;
        const cambios = obtenerCambios(movimiento);

        const modulo = movimiento.modulo || 'Sin módulo';

        subtitulo.textContent =
            `${modulo.toUpperCase()} · REGISTRO #${movimiento.registro_id}`;

        contenido.replaceChildren();

        // Encabezado del movimiento
        const encabezado = document.createElement('div');
        encabezado.className = 'mb-3';

        const usuario = document.createElement('div');
        usuario.className = 'text-body-secondary small';
        usuario.textContent =
            `Realizado por: ${movimiento.usuario_nombre || movimiento.usuario_nick || 'Usuario desconocido'}`;

        const tipo = document.createElement('span');

        const etiquetas = {
            INSERT: 'Creación',
            UPDATE: 'Modificación',
            DELETE: 'Eliminación'
        };

        const colores = {
            INSERT: 'success',
            UPDATE: 'primary',
            DELETE: 'danger'
        };

        tipo.className =
            `badge text-bg-${colores[accion] || 'secondary'} mt-2`;

        tipo.textContent = etiquetas[accion] || accion;

        encabezado.append(usuario, tipo);
        contenido.appendChild(encabezado);

        // Movimientos de modificación
        if (accion === 'UPDATE') {

            if (cambios.length === 0) {

                const mensaje = document.createElement('p');
                mensaje.className = 'text-body-secondary mb-0';
                mensaje.textContent = 'No se detectaron cambios entre los datos anteriores y nuevos.';

                contenido.appendChild(mensaje);

            } else {

                cambios.forEach(cambio => {

                    const tarjeta = document.createElement('div');
                    tarjeta.className = 'border rounded-3 p-3 mb-2';

                    const titulo = document.createElement('div');
                    titulo.className = 'fw-semibold mb-2';
                    titulo.textContent = nombreCampo(cambio.campo);

                    const fila = document.createElement('div');
                    fila.className = 'd-flex flex-wrap align-items-center gap-2';

                    const anterior = document.createElement('span');
                    anterior.className = 'badge text-bg-danger';
                    anterior.textContent = formatearValor(cambio.anterior, cambio.campo);

                    const flecha = document.createElement('i');
                    flecha.className = 'bi bi-arrow-right text-body-secondary';

                    const nuevo = document.createElement('span');
                    nuevo.className = 'badge text-bg-success';
                    nuevo.textContent = formatearValor(cambio.nuevo, cambio.campo);

                    fila.append(anterior, flecha, nuevo);
                    tarjeta.append(titulo, fila);
                    contenido.appendChild(tarjeta);

                });

            }

        } else {

            // Creación o eliminación: mostrar los datos correspondientes
            const datos = accion === 'DELETE'
                ? JSON.parse(movimiento.datos_anteriores || '{}')
                : JSON.parse(movimiento.datos_nuevos || '{}');

            Object.entries(datos).forEach(([campo, valor]) => {

                if (campo === 'id') return;

                const fila = document.createElement('div');
                fila.className = 'border-bottom py-2';

                const titulo = document.createElement('div');
                titulo.className = 'small text-body-secondary';
                titulo.textContent = nombreCampo(campo);

                const texto = document.createElement('div');
                texto.className = 'fw-medium';
                texto.textContent = formatearValor(valor, campo);

                fila.append(titulo, texto);
                contenido.appendChild(fila);

            });

        }

        bootstrap.Modal.getOrCreateInstance(modalElemento).show();
    }


    // =========================
    // Tabla de historial
    // =========================
    const tablaHistorial = new Tabulator(contenedor, {

        data: historial,

        layout: 'fitColumns',

        responsiveLayout: 'collapse',

        pagination: true,
        paginationSize: 10,
        paginationSizeSelector: [10, 20, 50],

        placeholder: 'No hay movimientos registrados.',

        columns: [

            {
                title: 'FECHA',
                field: 'fecha',
                minWidth: 155,
                formatter: function (celda) {
                    return formatearFecha(celda.getValue(), true);
                }
            },

            {
                title: 'USUARIO',
                field: 'usuario_nombre',
                minWidth: 180,
                formatter: function (celda) {
                    const nombre = celda.getValue() || 'Usuario desconocido';
                    const nick = celda.getRow().getData().usuario_nick || '';

                    const elemento = document.createElement('div');

                    const nombreElemento = document.createElement('div');
                    nombreElemento.className = 'fw-semibold';
                    nombreElemento.textContent = nombre;

                    const nickElemento = document.createElement('small');
                    nickElemento.className = 'text-body-secondary';
                    nickElemento.textContent = nick;

                    elemento.append(nombreElemento, nickElemento);

                    return elemento;
                }
            },

            {
                title: 'MÓDULO',
                field: 'modulo',
                minWidth: 125,
                formatter: function (celda) {
                    const modulo = celda.getValue() || '—';

                    return modulo.charAt(0).toUpperCase() +
                        modulo.slice(1);
                }
            },

            {
                title: 'REGISTRO',
                field: 'registro_id',
                hozAlign: 'center',
                width: 110,
                formatter: function (celda) {
                    return '#' + celda.getValue();
                }
            },

            {
                title: 'ACCIÓN',
                field: 'accion',
                hozAlign: 'center',
                minWidth: 125,
                formatter: formatearAccion
            },

            {
                title: 'DETALLE',
                field: 'id',
                hozAlign: 'center',
                headerSort: false,
                minWidth: 120,

                formatter: function (cell) {

                    const boton = document.createElement('button');

                    boton.type = 'button';
                    boton.className =
                        'btn btn-sm btn-outline-primary rounded-pill px-3';

                    boton.innerHTML = `
                        <i class="bi bi-eye me-1"></i>
                        Ver detalle
                    `;

                    boton.addEventListener('click', function (e) {

                        e.stopPropagation();

                        const movimiento = cell.getRow().getData();

                        mostrarDetalle(movimiento);

                    });

                    return boton;
                }
            }

        ]

    });

    // =========================
    // Buscador
    // =========================
    if (buscador) {

        buscador.addEventListener('input', function () {

            const termino = this.value
                .trim()
                .toLowerCase();

            if (!termino) {
                tablaHistorial.clearFilter();
                return;
            }

            tablaHistorial.setFilter((datos) => {

                const valores = [
                    datos.fecha,
                    datos.usuario_nombre,
                    datos.usuario_nick,
                    datos.modulo,
                    datos.registro_id,
                    datos.accion
                ];

                return valores.some(valor =>
                    String(valor ?? '')
                        .toLowerCase()
                        .includes(termino)
                );

            });

        });

    }

});
