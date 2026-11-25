
/* =====================================================
   ADMINISTRACIÓN DE USUARIOS
   TABLA GENERAL
===================================================== */

let tablaUsuarios;

document.addEventListener('DOMContentLoaded', function () {

    // =====================================================
    // ELEMENTOS
    // =====================================================

    const contenedor = document.getElementById('tabla-usuarios');
    const buscador = document.getElementById('buscar-usuarios');

    if (!contenedor || typeof Tabulator === 'undefined') {
        console.error('No se pudo inicializar la tabla de usuarios.');
        return;
    }

    // =====================================================
    // DATOS
    // =====================================================

    const usuarios = window.usuarios || [];

    // =====================================================
    // FORMATO DE FECHA
    // =====================================================

    function formatearFecha(fecha) {

        if (!fecha) return '';

        const fechaValida = new Date(
            String(fecha).replace(' ', 'T')
        );

        if (isNaN(fechaValida.getTime())) return '';

        return fechaValida.toLocaleString('es-MX', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // =====================================================
    // CREAR ELEMENTO DE TEXTO SEGURO
    // =====================================================

    function crearTexto(texto, clase = '') {

        const elemento = document.createElement('span');

        elemento.className = clase;
        elemento.textContent = texto ?? '';

        return elemento;
    }

    // =====================================================
    // ESCAPAR TEXTO PARA TARJETAS MÓVILES
    // =====================================================

    function escaparHTML(valor) {

        return String(valor ?? '').replace(/[&<>"']/g, caracter => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        })[caracter]);
    }

    // =====================================================
    // TABLA USUARIOS
    // =====================================================

    tablaUsuarios = new Tabulator('#tabla-usuarios', {

        layout: 'fitColumns',

        pagination: true,
        paginationSize: 10,
        paginationSizeSelector: [10, 25, 50],

        movableColumns: true,

        responsiveLayout: false,

        placeholder: 'No hay usuarios registrados.',
                // =====================================================
        // VISTA RESPONSIVE
        // =====================================================

        rowFormatter: function (row) {

            const data = row.getData();
            const el = row.getElement();

            el.classList.remove('usuarios-mobile-card');

            // =============================================
            // VISTA MÓVIL
            // =============================================

            if (window.innerWidth <= 767) {

                el.classList.add('usuarios-mobile-card');

                const nick = escaparHTML(data.nick || 'Sin usuario');
                const nombre = escaparHTML(data.nombre_completo || 'Sin nombre');
                const rol = escaparHTML(data.rol || 'Sin rol asignado');

                const fecha = escaparHTML(
                    formatearFecha(data.fecha_registro) || 'Sin fecha registrada'
                );

                el.innerHTML = `

                    <div class="usuarios-mobile-header">

                        <div class="usuarios-mobile-title">

                            <div class="usuarios-mobile-icon">
                                <i class="bi bi-person-circle"></i>
                            </div>

                            <div>

                                <div class="usuarios-mobile-label">
                                    USUARIO
                                </div>

                                <div class="usuarios-mobile-nick">
                                    ${nick}
                                </div>

                            </div>

                        </div>

                        <span class="badge bg-primary rounded-pill px-3 py-2">
                            ${rol}
                        </span>

                    </div>

                    <div class="usuarios-mobile-body">

                        <div class="usuarios-mobile-row">

                            <span class="usuarios-mobile-label-data">
                                <i class="bi bi-person"></i>
                                Nombre
                            </span>

                            <span class="usuarios-mobile-value">
                                ${nombre}
                            </span>

                        </div>

                        <div class="usuarios-mobile-row">

                            <span class="usuarios-mobile-label-data">
                                <i class="bi bi-calendar-check"></i>
                                Fecha de registro
                            </span>

                            <span class="usuarios-mobile-value">
                                ${fecha}
                            </span>

                        </div>

                    </div>

                    <div class="usuarios-mobile-footer">

                        <button
                            type="button"
                            class="btn btn-sm btn-outline-primary rounded-pill px-3 btn-historial-usuario"
                        >
                            <i class="bi bi-clock-history me-1"></i>
                            Ver historial
                        </button>

                    </div>

                `;

                const botonHistorial = el.querySelector('.btn-historial-usuario');

                botonHistorial?.addEventListener('click', function (e) {

                    e.stopPropagation();

                    console.log('Consultar historial del usuario:', data.id);

                });

            }

        },

        columns: [

            // =============================================
            // NICK
            // =============================================

            {
                title: 'NICK',
                field: 'nick',
                minWidth: 110,
                hozAlign: 'center',

                formatter: function (cell) {

                    return crearTexto(
                        cell.getValue() || '—',
                        'fw-semibold'
                    );
                }
            },

            // =============================================
            // NOMBRE DEL USUARIO
            // =============================================

            {
                title: 'NOMBRE DEL USUARIO',
                field: 'nombre_completo',
                minWidth: 180,

                formatter: function (cell) {

                    return crearTexto(
                        cell.getValue() || 'Sin nombre',
                        'fw-semibold'
                    );
                }
            },

            // =============================================
            // ROL
            // =============================================

            {
                title: 'ROL',
                field: 'rol',
                hozAlign: 'center',
                minWidth: 120,

                formatter: function (cell) {

                    const rol = cell.getValue() || 'Sin rol asignado';

                    const normalizado = rol.toLowerCase();

                    let clase = 'bg-secondary';

                    if (normalizado === 'administrador') {
                        clase = 'bg-primary';
                    } else if (normalizado === 'usuario') {
                        clase = 'bg-info text-dark';
                    }

                    const badge = document.createElement('span');

                    badge.className = `badge ${clase} rounded-pill px-3 py-2`;
                    badge.textContent = rol;

                    return badge;
                }
            },

            // =============================================
            // FECHA DE REGISTRO
            // =============================================

            {
                title: 'FECHA DE REGISTRO',
                field: 'fecha_registro',
                minWidth: 170,

                formatter: function (cell) {

                    const fecha = cell.getValue();

                    const contenedor = document.createElement('div');
                    contenedor.className = 'd-flex align-items-center gap-2';

                    const icono = document.createElement('i');
                    icono.className = 'bi bi-calendar-check text-primary';

                    const texto = crearTexto(
                        formatearFecha(fecha) || 'Sin fecha registrada',
                        'text-body-secondary'
                    );

                    contenedor.appendChild(icono);
                    contenedor.appendChild(texto);

                    return contenedor;
                }
            },

            // =============================================
            // ACCIONES
            // =============================================

            {
                title: 'ACCIONES',
                field: 'id',
                hozAlign: 'center',
                headerSort: false,
                minWidth: 140,

                formatter: function (cell) {

                    const boton = document.createElement('button');

                    boton.type = 'button';
                    boton.className = 'btn btn-sm btn-outline-primary rounded-pill px-3';

                    boton.innerHTML = `
                        <i class="bi bi-clock-history me-1"></i>
                        Ver historial
                    `;

                    boton.addEventListener('click', function (e) {

                        e.stopPropagation();

                        const usuario = cell.getRow().getData();

                        // Preparar navegación al historial
                        console.log('Consultar historial del usuario:', usuario.id);

                    });

                    return boton;
                }
            }

        ],

        data: usuarios

    });

    // =====================================================
    // REDIBUJAR AL CAMBIAR ENTRE MÓVIL Y ESCRITORIO
    // =====================================================

    let vistaMovilAnterior = window.innerWidth <= 767;

    window.addEventListener('resize', function () {

        const vistaMovilActual = window.innerWidth <= 767;

        if (vistaMovilActual !== vistaMovilAnterior) {

            vistaMovilAnterior = vistaMovilActual;

            tablaUsuarios.redraw(true);

        }

    });

    // =====================================================
    // BUSCADOR GENERAL
    // =====================================================

    buscador?.addEventListener('input', function () {

        const termino = this.value
            .trim()
            .toLowerCase();

        if (!termino) {
            tablaUsuarios.clearFilter();
            return;
        }

        tablaUsuarios.setFilter(function (data) {

            return [
                data.nick,
                data.nombre_completo,
                data.rol,
                data.fecha_registro
            ].some(valor =>
                String(valor ?? '')
                    .toLowerCase()
                    .includes(termino)
            );

        });

    });

});
