/* =====================================================
   NOTAS RÁPIDAS - COMPONENTE GLOBAL
===================================================== */

document.addEventListener('DOMContentLoaded', () => {

    /* =====================================================
       ELEMENTOS PRINCIPALES
    ===================================================== */

    const contenedor = document.getElementById('notas-rapidas');
    const botonAbrir = document.getElementById('notas-rapidas-toggle');
    const botonCerrar = document.getElementById('notas-rapidas-cerrar');
    const panel = document.getElementById('notas-rapidas-panel');

    if (!contenedor || !botonAbrir || !botonCerrar || !panel) {
        return;
    }

    /* =====================================================
       ELEMENTOS DEL LISTADO Y EDITOR
    ===================================================== */

    const vistaListado = document.getElementById('notas-rapidas-vista-listado');
    const vistaEditor = document.getElementById('notas-rapidas-vista-editor');

    const listado = document.getElementById('notas-rapidas-listado');
    const buscar = document.getElementById('notas-rapidas-buscar');

    const botonNueva = document.getElementById('notas-rapidas-nueva');
    const botonVolver = document.getElementById('notas-rapidas-volver');
    const botonFijar = document.getElementById('notas-rapidas-fijar');
    const botonEliminar = document.getElementById('notas-rapidas-eliminar');

    const inputTitulo = document.getElementById('notas-rapidas-titulo');
    const inputTexto = document.getElementById('notas-rapidas-texto');
    const estado = document.getElementById('notas-rapidas-estado');

    const botonesColor = document.querySelectorAll('.nota-color');

    if (
        !vistaListado || !vistaEditor || !listado ||
        !buscar || !botonNueva || !botonVolver ||
        !botonFijar || !botonEliminar ||
        !inputTitulo || !inputTexto || !estado
    ) {
        return;
    }

    /* =====================================================
       DATOS Y CONFIGURACIÓN
    ===================================================== */

    let notas = [];
    let notaActual = null;

    // Cargar notas desde la base de datos.
    async function cargarNotas() {

        try {

            const respuesta = await fetch(
                BASE_URL + 'notas/listar'
            );

            const datos = await respuesta.json();

            if (!respuesta.ok || !datos.success) {
                throw new Error(
                    datos.mensaje || 'No se pudieron cargar las notas.'
                );
            }

            notas = datos.notas.map(nota => ({
                ...nota,
                id: Number(nota.id),
                fijada: Number(nota.fijada) === 1
            }));

            renderizarNotas();

        } catch (error) {

            console.error('Error al cargar notas:', error);

            listado.innerHTML = `
                <div class="notas-rapidas-vacio">
                    <i class="bi bi-exclamation-circle"></i>
                    <p>No se pudieron cargar las notas.</p>
                    <small>Intenta nuevamente.</small>
                </div>
            `;

        }

    }

    /* =====================================================
       APERTURA Y CIERRE DEL PANEL
    ===================================================== */

    function abrirPanel() {

        contenedor.classList.add('panel-abierto');

        botonAbrir.setAttribute('aria-expanded', 'true');
        panel.setAttribute('aria-hidden', 'false');

    }

    function cerrarPanel() {

        contenedor.classList.remove('panel-abierto');

        botonAbrir.setAttribute('aria-expanded', 'false');
        panel.setAttribute('aria-hidden', 'true');

    }

    botonAbrir.addEventListener('click', abrirPanel);
    botonCerrar.addEventListener('click', cerrarPanel);

    document.addEventListener('keydown', (e) => {

        if (e.key === 'Escape' && contenedor.classList.contains('panel-abierto')) {
            cerrarPanel();
            botonAbrir.focus();
        }

    });

    /* =====================================================
       CAMBIAR ENTRE LISTADO Y EDITOR
    ===================================================== */

    function mostrarListado() {

        vistaEditor.classList.add('d-none');
        vistaListado.classList.remove('d-none');

        notaActual = null;

        renderizarNotas();

    }

    function mostrarEditor() {

        vistaListado.classList.add('d-none');
        vistaEditor.classList.remove('d-none');

    }

    /* =====================================================
       CREAR NOTA
    ===================================================== */

    function nuevaNota() {

        notaActual = {
            id: null,
            titulo: '',
            contenido: '',
            color: 'amarillo',
            fijada: false
        };

        inputTitulo.value = '';
        inputTexto.value = '';

        seleccionarColor('amarillo');
        actualizarBotonFijar();

        estado.textContent = 'Nueva nota';

        mostrarEditor();

        inputTitulo.focus();

    }

    botonNueva.addEventListener('click', nuevaNota);

    /* =====================================================
       ABRIR NOTA EXISTENTE
    ===================================================== */

    function abrirNota(id) {

        const nota = notas.find(n => n.id === id);

        if (!nota) return;

        notaActual = nota;

        inputTitulo.value = nota.titulo;
        inputTexto.value = nota.contenido;

        seleccionarColor(nota.color);
        actualizarBotonFijar();

        estado.textContent = 'Nota guardada';

        mostrarEditor();

    }

    /* =====================================================
       GUARDAR NOTA
    ===================================================== */

    async function guardarNota() {

        if (!notaActual) return false;

        notaActual.titulo = inputTitulo.value.trim();
        notaActual.contenido = inputTexto.value;

        // Evitar guardar notas completamente vacías.
        if (
            !notaActual.titulo &&
            !notaActual.contenido.trim()
        ) {

            estado.textContent = 'Nueva nota';

            return true;

        }

        const formData = new FormData();

        formData.append('titulo', notaActual.titulo);
        formData.append('contenido', notaActual.contenido);
        formData.append('color', notaActual.color);
        formData.append('fijada', notaActual.fijada ? 1 : 0);

        const esNueva = !notaActual.id;

        if (!esNueva) {
            formData.append('id', notaActual.id);
        }

        estado.textContent = 'Guardando...';

        try {

            const respuesta = await fetch(
                BASE_URL + (
                    esNueva
                        ? 'notas/guardar'
                        : 'notas/actualizar'
                ),
                {
                    method: 'POST',
                    body: formData
                }
            );

            const datos = await respuesta.json();

            if (!respuesta.ok || !datos.success) {
                throw new Error(
                    datos.mensaje || 'No se pudo guardar la nota.'
                );
            }

            if (esNueva) {
                notaActual.id = Number(datos.id);
                notas.push(notaActual);
            } else {

                const indice = notas.findIndex(
                    nota => nota.id === notaActual.id
                );

                if (indice !== -1) {
                    notas[indice] = notaActual;
                }

            }

            estado.textContent = 'Cambios guardados';

            renderizarNotas();

            return true;

        } catch (error) {

            console.error('Error al guardar nota:', error);

            estado.textContent = 'Error al guardar';

            alert(error.message);

            return false;

        }

    }

    /* =====================================================
       VOLVER AL LISTADO
    ===================================================== */

    botonVolver.addEventListener('click', async () => {

        const guardado = await guardarNota();

        if (!guardado) return;

        mostrarListado();

    });

    /* =====================================================
       CAMBIOS EN EL EDITOR
    ===================================================== */

    inputTitulo.addEventListener('input', () => {

        if (notaActual) {
            notaActual.titulo = inputTitulo.value;
            estado.textContent = 'Cambios pendientes';
        }

    });

    inputTexto.addEventListener('input', () => {

        if (notaActual) {
            notaActual.contenido = inputTexto.value;
            estado.textContent = 'Cambios pendientes';
        }

    });

    /* =====================================================
       SELECCIÓN DE COLOR
    ===================================================== */

    function seleccionarColor(color) {

        if (!notaActual) return;

        notaActual.color = color;

        botonesColor.forEach(boton => {

            const activo = boton.dataset.color === color;

            boton.classList.toggle('activo', activo);
            boton.setAttribute(
                'aria-pressed',
                activo ? 'true' : 'false'
            );

        });

    }

    botonesColor.forEach(boton => {

        boton.addEventListener('click', async () => {

            if (!notaActual) return;

            const colorAnterior = notaActual.color;

            seleccionarColor(boton.dataset.color);

            if (notaActual.id) {

                const guardado = await guardarNota();

                if (!guardado) {
                    seleccionarColor(colorAnterior);
                }

            }

        });

    });

    /* =====================================================
       FIJAR NOTA
    ===================================================== */

    function actualizarBotonFijar() {

        if (!notaActual) return;

        botonFijar.classList.toggle(
            'btn-primary',
            notaActual.fijada
        );

        botonFijar.classList.toggle(
            'btn-outline-secondary',
            !notaActual.fijada
        );

        botonFijar.innerHTML = notaActual.fijada
            ? '<i class="bi bi-pin-fill"></i>'
            : '<i class="bi bi-pin"></i>';

        botonFijar.title = notaActual.fijada
            ? 'Desfijar nota'
            : 'Fijar nota';

    }

    botonFijar.addEventListener('click', async () => {

        if (!notaActual) return;

        const fijadaAnterior = notaActual.fijada;

        notaActual.fijada = !notaActual.fijada;

        actualizarBotonFijar();

        if (notaActual.id) {

            const guardado = await guardarNota();

            if (!guardado) {

                notaActual.fijada = fijadaAnterior;

                actualizarBotonFijar();

            }

        }

    });

    /* =====================================================
       ELIMINAR NOTA
    ===================================================== */

    botonEliminar.addEventListener('click', async () => {

        if (!notaActual) return;

        const confirmar = confirm(
            '¿Deseas eliminar esta nota?'
        );

        if (!confirmar) return;

        // Si todavía no existe en la base de datos,
        // simplemente se descarta.
        if (!notaActual.id) {

            mostrarListado();

            return;

        }

        const formData = new FormData();

        formData.append('id', notaActual.id);

        try {

            const respuesta = await fetch(
                BASE_URL + 'notas/eliminar',
                {
                    method: 'POST',
                    body: formData
                }
            );

            const datos = await respuesta.json();

            if (!respuesta.ok || !datos.success) {
                throw new Error(
                    datos.mensaje || 'No se pudo eliminar la nota.'
                );
            }

            notas = notas.filter(
                nota => nota.id !== notaActual.id
            );

            mostrarListado();

        } catch (error) {

            console.error('Error al eliminar nota:', error);

            alert(error.message);

        }

    });

    /* =====================================================
       RENDERIZAR LISTADO
    ===================================================== */

    function renderizarNotas() {

        const termino = buscar.value.trim().toLowerCase();

        const filtradas = notas.filter(nota => {

            const titulo = (nota.titulo || '').toLowerCase();
            const contenido = (nota.contenido || '').toLowerCase();

            return (
                titulo.includes(termino) ||
                contenido.includes(termino)
            );

        });

        // Las notas fijadas aparecen primero.
        filtradas.sort(
            (a, b) => Number(b.fijada) - Number(a.fijada)
        );

        listado.innerHTML = '';

        if (filtradas.length === 0) {

            listado.innerHTML = `
                <div class="notas-rapidas-vacio">
                    <i class="bi bi-journal-text"></i>
                    <p>${termino ? 'No se encontraron notas.' : 'No tienes notas todavía.'}</p>
                    <small>${termino ? 'Prueba con otra búsqueda.' : 'Crea una nota para comenzar.'}</small>
                </div>
            `;

            return;

        }

        filtradas.forEach(nota => {

            const tarjeta = document.createElement('button');

            tarjeta.type = 'button';
            tarjeta.className = `nota-rapida-item nota-color-${nota.color}`;

            const titulo = document.createElement('strong');
            titulo.textContent = nota.titulo || 'Sin título';

            const contenido = document.createElement('span');
            contenido.textContent = nota.contenido || 'Sin contenido';

            tarjeta.appendChild(titulo);
            tarjeta.appendChild(contenido);

            if (nota.fijada) {

                const icono = document.createElement('i');
                icono.className = 'bi bi-pin-fill nota-rapida-fijada';

                tarjeta.appendChild(icono);

            }

            tarjeta.addEventListener('click', () => {
                abrirNota(nota.id);
            });

            listado.appendChild(tarjeta);

        });

    }

    /* =====================================================
       BÚSQUEDA
    ===================================================== */

    buscar.addEventListener('input', renderizarNotas);

    /* =====================================================
       INICIALIZACIÓN
    ===================================================== */

    cargarNotas();

});