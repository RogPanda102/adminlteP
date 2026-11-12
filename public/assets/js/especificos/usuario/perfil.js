document.addEventListener('DOMContentLoaded', function () {

    let grafica = null;

    const modal = document.getElementById('modalCotizaciones');
    const filtroAnio = document.getElementById('filtro-anio-cotizaciones');
    const filtroMes = document.getElementById('filtro-mes-cotizaciones');

    // ✅ CORREGIDO (IDs reales del modal)
    const elTotal = document.getElementById('total-anio');
    const elPromedio = document.getElementById('promedio-mensual');
    const elMejorMes = document.getElementById('mejor-mes');

    function cargarGraficaCotizaciones()
    {
        let anio = filtroAnio ? filtroAnio.value : '';
        let mes = filtroMes ? filtroMes.value : '';

        let url =
            BASE_URL +
            'perfil/estadisticasCotizaciones?anio=' + anio;

        if (mes !== '') {
            url += '&mes=' + mes;
        }

        fetch(url)
        .then(response => response.json())
        .then(data => {

            if (data.error) return;

            crearGrafica(data.grafica);
            pintarMetricas(data.metricas);

        })
        .catch(error => {
            console.error('Error cargando estadísticas:', error);
        });
    }

    function crearGrafica(datos)
    {
        const canvas = document.getElementById('graficaCotizaciones');
        if (!canvas) return;

        const meses = [
            'Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'
        ];

        const valores = [];
        for (let i = 1; i <= 12; i++) {
            valores.push(datos[i] ?? 0);
        }

        if (grafica) {
            grafica.destroy();
        }

        grafica = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: meses,
                datasets: [{
                    label: 'Cotizaciones realizadas',
                    data: valores
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    function pintarMetricas(metricas)
    {
        if (!metricas) return;

        if (elTotal) {
            elTotal.innerText = metricas.total ?? 0;
        }

        if (elPromedio) {
            elPromedio.innerText = metricas.promedio ?? 0;
        }

        if (elMejorMes) {
            elMejorMes.innerText = metricas.mejor_mes ?? '-';
        }
    }

    // abrir modal
    if (modal) {
        modal.addEventListener('shown.bs.modal', function () {
            cargarGraficaCotizaciones();
        });
    }

    // filtros
    if (filtroAnio) {
        filtroAnio.addEventListener('change', cargarGraficaCotizaciones);
    }

    if (filtroMes) {
        filtroMes.addEventListener('change', cargarGraficaCotizaciones);
    }
   
    // Mostrar brevemente el último carácter escrito en las contraseñas
    const camposPassword = document.querySelectorAll(
        'input[name="password_actual"], ' +
        'input[name="password"], ' +
        'input[name="password_confirmacion"]'
    );

    camposPassword.forEach(function (campo) {
        let valorAnterior = campo.value;
        let temporizador = null;
        let componiendo = false;
        let capaVisual = null;

        // Crear un contenedor sin modificar el formulario PHP.
        const contenedor = document.createElement('div');
        contenedor.style.position = 'relative';
        contenedor.style.width = '100%';

        campo.parentNode.insertBefore(contenedor, campo);
        contenedor.appendChild(campo);

        // Capa que imita los caracteres ocultos y revela solo el último.
        capaVisual = document.createElement('span');
        capaVisual.setAttribute('aria-hidden', 'true');

        Object.assign(capaVisual.style, {
            display: 'none',
            position: 'absolute',
            inset: '0',
            boxSizing: 'border-box',
            overflow: 'hidden',
            whiteSpace: 'pre',
            pointerEvents: 'none',
            zIndex: '2'
        });

        contenedor.appendChild(capaVisual);

        // Conservar el mensaje de validación dentro del contenedor.
        // Bootstrap necesita que .invalid-feedback siga siendo hermano
        // del input.is-invalid.
        const mensajeError = contenedor.nextElementSibling;

        if (
            mensajeError &&
            mensajeError.classList.contains('invalid-feedback')
        ) {
            contenedor.appendChild(mensajeError);
        }

        function ocultarCaracter() {
            if (temporizador !== null) {
                clearTimeout(temporizador);
                temporizador = null;
            }

            campo.style.color = '';
            campo.style.webkitTextFillColor = '';
            capaVisual.style.display = 'none';
            capaVisual.textContent = '';
        }

        function mostrarUltimoCaracter(caracter) {
            const estilo = getComputedStyle(campo);

            // Copiar la tipografía y el espaciado del campo original.
            capaVisual.style.fontFamily = estilo.fontFamily;
            capaVisual.style.fontSize = estilo.fontSize;
            capaVisual.style.fontWeight = estilo.fontWeight;
            capaVisual.style.fontStyle = estilo.fontStyle;
            capaVisual.style.letterSpacing = estilo.letterSpacing;
            capaVisual.style.lineHeight = estilo.lineHeight;
            capaVisual.style.textAlign = estilo.textAlign;
            capaVisual.style.paddingTop = estilo.paddingTop;
            capaVisual.style.paddingRight = estilo.paddingRight;
            capaVisual.style.paddingBottom = estilo.paddingBottom;
            capaVisual.style.paddingLeft = estilo.paddingLeft;
            capaVisual.style.color = estilo.color;

            // Ocultar el texto nativo solo mientras se muestra la capa.
            campo.style.color = 'transparent';
            campo.style.webkitTextFillColor = 'transparent';

            capaVisual.textContent =
                '•'.repeat(campo.value.length - 1) + caracter;

            capaVisual.style.display = 'block';

            // Mantener la posición correcta si el campo tiene desplazamiento.
            capaVisual.scrollLeft = campo.scrollLeft;

            temporizador = setTimeout(function () {
                ocultarCaracter();
                temporizador = null;
            }, 700);
        }

        campo.addEventListener('input', function (evento) {
            const valorNuevo = campo.value;
            const tipoEntrada = evento.inputType || '';

            ocultarCaracter();

            // No revelar caracteres durante la composición de texto.
            if (componiendo) {
                valorAnterior = valorNuevo;
                return;
            }

            const esCaracterAgregadoAlFinal =
                tipoEntrada === 'insertText' &&
                valorNuevo.length === valorAnterior.length + 1 &&
                valorNuevo.startsWith(valorAnterior) &&
                campo.selectionStart === valorNuevo.length &&
                campo.selectionEnd === valorNuevo.length;

            if (esCaracterAgregadoAlFinal) {
                mostrarUltimoCaracter(valorNuevo.slice(-1));
            }

            // Actualizar la referencia para el siguiente evento.
            valorAnterior = valorNuevo;
        });

        campo.addEventListener('compositionstart', function () {
            componiendo = true;
            ocultarCaracter();
        });

        campo.addEventListener('compositionend', function () {
            componiendo = false;
            valorAnterior = campo.value;
            ocultarCaracter();
        });

        campo.addEventListener('blur', ocultarCaracter);

        campo.addEventListener('scroll', function () {
            if (capaVisual.style.display !== 'none') {
                capaVisual.scrollLeft = campo.scrollLeft;
            }
        });

        // No conservar una capa visible si el formulario se restablece.
        const formulario = campo.form;

        if (formulario) {
            formulario.addEventListener('reset', function () {
                ocultarCaracter();

                setTimeout(function () {
                    valorAnterior = campo.value;
                }, 0);
            });
        }
    });

    // Vista previa y Drag & Drop de foto de perfil
    const inputFoto = document.getElementById('input-foto-perfil');
    const previewFoto = document.getElementById('preview-foto-perfil');
    const zonaDrag = document.getElementById('zona-drag-foto');
    const mensajeDrag = document.getElementById('mensaje-drag-foto');

    if (inputFoto && previewFoto && zonaDrag) {

        const tiposPermitidos = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        let contadorDrag = 0;
        let animacionDrag = null;

        function procesarFoto(archivo) {

            if (!archivo) return;

            if (!tiposPermitidos.includes(archivo.type)) {
                alert('Formato no permitido. Usa JPG, PNG o WEBP.');
                return;
            }

            if (archivo.size > 5 * 1024 * 1024) {
                alert('La imagen no debe superar los 5 MB.');
                return;
            }

            const lector = new FileReader();

            lector.onload = function (evento) {
                previewFoto.src = evento.target.result;
            };

            lector.readAsDataURL(archivo);
        }

        function activarEfectoDrag() {

            zonaDrag.style.outline = '3px dashed #0d6efd';
            zonaDrag.style.outlineOffset = '5px';
            zonaDrag.style.borderRadius = '50%';

            previewFoto.style.transform = 'scale(1.12)';
            previewFoto.style.filter = 'brightness(0.55)';

            if (mensajeDrag) {
                mensajeDrag.classList.remove('d-none');
                mensajeDrag.classList.add('d-flex');
            }

            if (previewFoto.animate) {
                animacionDrag = previewFoto.animate([
                    { transform: 'scale(1.12)' },
                    { transform: 'scale(1.18)' },
                    { transform: 'scale(1.12)' }
                ], {
                    duration: 900,
                    iterations: Infinity
                });
            }
        }

        function desactivarEfectoDrag() {

            zonaDrag.style.outline = '';
            zonaDrag.style.outlineOffset = '';
            zonaDrag.style.borderRadius = '';

            previewFoto.style.transform = '';
            previewFoto.style.filter = '';

            if (mensajeDrag) {
                mensajeDrag.classList.add('d-none');
                mensajeDrag.classList.remove('d-flex');
            }

            if (animacionDrag) {
                animacionDrag.cancel();
                animacionDrag = null;
            }
        }

        // Selección manual
        inputFoto.addEventListener('change', function () {
            procesarFoto(this.files[0]);
        });

        // Clic sobre la imagen
        zonaDrag.addEventListener('click', function () {
            inputFoto.click();
        });

        // Evitar comportamiento predeterminado
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(evento => {
            zonaDrag.addEventListener(evento, function (e) {
                e.preventDefault();
                e.stopPropagation();
            });
        });

        // Entrada del archivo
        zonaDrag.addEventListener('dragenter', function () {

            contadorDrag++;

            if (contadorDrag === 1) {
                activarEfectoDrag();
            }
        });

        // Mantener activo mientras se arrastra
        zonaDrag.addEventListener('dragover', function (e) {
            e.dataTransfer.dropEffect = 'copy';
        });

        // Salida del área
        zonaDrag.addEventListener('dragleave', function () {

            contadorDrag--;

            if (contadorDrag <= 0) {
                contadorDrag = 0;
                desactivarEfectoDrag();
            }
        });

        // Soltar imagen
        zonaDrag.addEventListener('drop', function (e) {

            contadorDrag = 0;
            desactivarEfectoDrag();

            const archivo = e.dataTransfer.files[0];

            if (!archivo) return;

            const transferencia = new DataTransfer();
            transferencia.items.add(archivo);

            inputFoto.files = transferencia.files;

            procesarFoto(archivo);
        });
    }

});