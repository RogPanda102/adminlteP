<div class="card">

    <div class="card-header">

        <div class="d-flex justify-content-between align-items-center">

            <h3 class="card-title mb-0">

                <i class="bi bi-bell me-2"></i>

                Notificaciones

            </h3>


            <?php
                $hayNoLeidas = false;

                foreach ($notificaciones as $notificacion) {

                    if ((int)$notificacion['leida'] === 0) {

                        $hayNoLeidas = true;

                        break;

                    }

                }
            ?>


            <?php if ($hayNoLeidas) : ?>

                <button
                    type="button"
                    id="btn-marcar-todas"
                    class="btn btn-sm btn-outline-secondary"
                    title="Marcar todas como leídas"
                >

                    <i class="bi bi-check-double me-1"></i>

                    Marcar todas como leídas

                </button>

            <?php endif; ?>

        </div>

    </div>


    <div class="card-body p-0">

        <?php if (empty($notificaciones)) : ?>

            <div class="p-4 text-center text-muted">

                <i class="bi bi-bell-slash fa-2x mb-3"></i>

                <p class="mb-0">

                    No tienes notificaciones.

                </p>

            </div>


        <?php else : ?>

            <div
                id="lista-notificaciones"
                class="list-group list-group-flush"
            >

                <?php foreach ($notificaciones as $notificacion) : ?>

                    <?php

                        $color = notificacionColor(
                            $notificacion['tipo']
                        );

                        $icono = notificacionIcono(
                            $notificacion['tipo']
                        );

                        $esLeida =
                            (int)$notificacion['leida'] === 1;

                        $itemClass = $esLeida

                            ? 'list-group-item bg-light'

                            : 'list-group-item border-start border-' .
                              $color .
                              ' border-4';

                        $tituloClass = $esLeida

                            ? ''

                            : 'fw-bold';

                    ?>


                    <div
                        id="notificacion-<?php echo $notificacion['id']; ?>"
                        class="<?php echo $itemClass; ?>"
                        data-notificacion-id="<?php echo $notificacion['id']; ?>"
                    >

                        <div class="list-group-item">


                            <div
                                class="d-flex justify-content-between align-items-start"
                            >


                                <!-- =========================
                                     CONTENIDO
                                     ========================= -->

                                <div class="me-3">


                                    <h5
                                        class="mb-1 <?php echo $tituloClass; ?>"
                                    >

                                        <i
                                            class="<?php echo $icono; ?> text-<?php echo $color; ?> me-2"
                                        ></i>


                                        <span
                                            class="notificacion-titulo"
                                        >

                                            <?php echo htmlspecialchars(
                                                $notificacion['titulo']
                                            ); ?>

                                        </span>


                                        <?php if (!$esLeida) : ?>

                                            <span
                                                class="badge bg-<?php echo $color; ?> ms-2 notificacion-nueva"
                                            >

                                                Nueva

                                            </span>

                                        <?php endif; ?>


                                    </h5>


                                    <?php if (
                                        !empty($notificacion['mensaje'])
                                    ) : ?>

                                        <p class="mb-1 text-muted">

                                            <?php echo htmlspecialchars(
                                                $notificacion['mensaje']
                                            ); ?>

                                        </p>

                                    <?php endif; ?>


                                    <small class="text-muted">

                                        <i class="bi bi-clock me-1"></i>

                                        <?php echo notificacionFecha(
                                            $notificacion['fecha_creacion']
                                        ); ?>

                                    </small>


                                </div>


                                <!-- =========================
                                     ACCIONES
                                     ========================= -->

                                <div
                                    class="d-flex align-items-center gap-2 flex-shrink-0"
                                >


                                    <?php if (!$esLeida) : ?>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-success btn-marcar-leida"
                                            data-id="<?php echo $notificacion['id']; ?>"
                                            title="Marcar como leída"
                                        >

                                            <i class="bi bi-check-lg"></i>

                                            <span class="d-none d-md-inline ms-1">
                                                Leída
                                            </span>

                                        </button>

                                    <?php endif; ?>


                                    <?php if (!empty($notificacion['url'])) : ?>

                                        <a
                                            href="<?php echo BASE_URL; ?>notificaciones/abrir?id=<?php echo $notificacion['id']; ?>"
                                            class="btn btn-sm btn-outline-primary"
                                        >

                                            Abrir

                                            <i class="bi bi-arrow-right ms-1"></i>

                                        </a>

                                    <?php endif; ?>


                                </div>


                            </div>


                        </div>


                    </div>


                <?php endforeach; ?>


            </div>

        <?php endif; ?>

    </div>

</div>


<script>

document.addEventListener(
    'DOMContentLoaded',
    function () {


        // =====================================================
        // MARCAR UNA NOTIFICACIÓN COMO LEÍDA
        // =====================================================

        document.querySelectorAll(
            '.btn-marcar-leida'
        ).forEach(function (boton) {


            boton.addEventListener(
                'click',
                async function () {


                    const id =
                        this.dataset.id;


                    if (!id) {

                        return;

                    }


                    try {


                        const respuesta = await fetch(

                            APP.baseUrl +
                            'notificaciones/marcar-leida',

                            {

                                method: 'POST',

                                headers: {

                                    'Content-Type':
                                        'application/x-www-form-urlencoded'

                                },

                                body:
                                    'id=' +
                                    encodeURIComponent(id)

                            }

                        );


                        const datos =
                            await respuesta.json();


                        if (!datos.ok) {

                            console.error(
                                'No se pudo marcar la notificación como leída.'
                            );

                            return;

                        }


                        // =====================================
                        // ACTUALIZAR ELEMENTO VISUALMENTE
                        // =====================================

                        const elemento =
                            document.getElementById(
                                'notificacion-' + id
                            );


                        if (!elemento) {

                            return;

                        }


                        // Cambiar fondo
                        elemento.classList.remove(
                            'border-start',
                            'border-4'
                        );

                        elemento.classList.add(
                            'bg-light'
                        );


                        // Quitar negritas
                        const titulo =
                            elemento.querySelector(
                                'h5'
                            );

                        if (titulo) {

                            titulo.classList.remove(
                                'fw-bold'
                            );

                        }


                        // Quitar "Nueva"
                        const nueva =
                            elemento.querySelector(
                                '.notificacion-nueva'
                            );

                        if (nueva) {

                            nueva.remove();

                        }


                        // Quitar botón "Leída"
                        const botonLeida =
                            elemento.querySelector(
                                '.btn-marcar-leida'
                            );

                        if (botonLeida) {

                            botonLeida.remove();

                        }


                        // =====================================
                        // COMPROBAR SI QUEDAN NO LEÍDAS
                        // =====================================

                        actualizarBotonMarcarTodas();


                    } catch (error) {


                        console.error(
                            'Error al marcar notificación:',
                            error
                        );


                    }

                }
            );


        });


        // =====================================================
        // MARCAR TODAS COMO LEÍDAS
        // =====================================================

        const botonTodas =
            document.getElementById(
                'btn-marcar-todas'
            );


        if (botonTodas) {


            botonTodas.addEventListener(
                'click',
                async function () {


                    try {


                        const respuesta = await fetch(

                            APP.baseUrl +
                            'notificaciones/marcar-todas',

                            {

                                method: 'POST'

                            }

                        );


                        const datos =
                            await respuesta.json();


                        if (!datos.ok) {

                            console.error(
                                'No se pudieron marcar todas las notificaciones como leídas.'
                            );

                            return;

                        }


                        // =====================================
                        // ACTUALIZAR TODAS VISUALMENTE
                        // =====================================

                        document
                            .querySelectorAll(
                                '#lista-notificaciones > div'
                            )
                            .forEach(function (elemento) {


                                // Quitar borde de no leída
                                elemento.classList.remove(
                                    'border-start',
                                    'border-4'
                                );


                                // Fondo de leída
                                elemento.classList.add(
                                    'bg-light'
                                );


                                // Quitar negrita
                                const titulo =
                                    elemento.querySelector(
                                        'h5'
                                    );

                                if (titulo) {

                                    titulo.classList.remove(
                                        'fw-bold'
                                    );

                                }


                                // Quitar "Nueva"
                                const nueva =
                                    elemento.querySelector(
                                        '.notificacion-nueva'
                                    );

                                if (nueva) {

                                    nueva.remove();

                                }


                                // Quitar botón individual
                                const boton =
                                    elemento.querySelector(
                                        '.btn-marcar-leida'
                                    );

                                if (boton) {

                                    boton.remove();

                                }


                            });


                        // =====================================
                        // QUITAR BOTÓN GLOBAL
                        // =====================================

                        botonTodas.remove();


                    } catch (error) {


                        console.error(
                            'Error al marcar todas las notificaciones:',
                            error
                        );


                    }

                }
            );


        }


        // =====================================================
        // ACTUALIZAR BOTÓN "MARCAR TODAS"
        // =====================================================

        function actualizarBotonMarcarTodas() {


            const pendientes =
                document.querySelectorAll(
                    '.btn-marcar-leida'
                );


            const boton =
                document.getElementById(
                    'btn-marcar-todas'
                );


            if (!boton) {

                return;

            }


            if (pendientes.length === 0) {

                boton.remove();

            }


        }


    }

);

</script>