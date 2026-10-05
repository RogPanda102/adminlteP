<?php

$navbarNotificaciones =
    $navbarNotificaciones ?? [];

$notificaciones =
    $navbarNotificaciones['notificaciones'] ?? [];

?>

<style>

/* =========================================================
   DROPDOWN DE NOTIFICACIONES
   Todo queda limitado a este menú para no afectar AdminLTE
   ========================================================= */

#navbar-notificaciones-menu {

    width: 380px;
    max-width: 90vw;
    padding: 0;

}


/* =========================
   ENCABEZADO
   ========================= */

#navbar-notificaciones-menu .notificaciones-header {

    padding: 12px 16px;

    font-size: 0.9rem;
    font-weight: 600;

    color: var(--bs-body-color);

}


/* =========================
   CONTENEDOR DE CADA NOTIFICACIÓN
   ========================= */

#navbar-notificaciones-menu .navbar-notificacion {

    display: flex;

    align-items: flex-start;

    gap: 10px;

    width: 100%;

    padding: 11px 14px;

    text-decoration: none;

    color: inherit;

    white-space: normal;

    background-color: transparent;

    transition:
        background-color 0.15s ease;

}


/* Hover */

#navbar-notificaciones-menu .navbar-notificacion:hover {

    background-color: rgba(0, 0, 0, 0.04);

}


/* =========================
   ICONO
   ========================= */

#navbar-notificaciones-menu .navbar-notificacion-icono {

    flex: 0 0 28px;

    width: 28px;
    height: 28px;

    display: flex;

    align-items: center;
    justify-content: center;

    margin-top: 1px;

    font-size: 1rem;

}


/* =========================
   CONTENIDO
   ========================= */

#navbar-notificaciones-menu .navbar-notificacion-contenido {

    min-width: 0;

    flex: 1;

}


/* =========================
   TITULO
   ========================= */

#navbar-notificaciones-menu .navbar-notificacion-titulo {

    display: block;

    margin: 0;

    font-size: 0.875rem;

    line-height: 1.25rem;

    color: var(--bs-body-color);

}


/* No leída */

#navbar-notificaciones-menu
.navbar-notificacion.no-leida
.navbar-notificacion-titulo {

    font-weight: 600;

}


/* Leída */

#navbar-notificaciones-menu
.navbar-notificacion.leida
.navbar-notificacion-titulo {

    font-weight: 400;

}


/* =========================
   MENSAJE
   ========================= */

#navbar-notificaciones-menu .navbar-notificacion-mensaje {

    display: -webkit-box;

    -webkit-box-orient: vertical;

    -webkit-line-clamp: 2;

    overflow: hidden;

    margin-top: 2px;

    font-size: 0.8rem;

    line-height: 1.15rem;

    color: var(--bs-secondary-color);

}


/* =========================
   FECHA
   ========================= */

#navbar-notificaciones-menu .navbar-notificacion-fecha {

    display: block;

    margin-top: 3px;

    font-size: 0.72rem;

    line-height: 1rem;

    color: var(--bs-secondary-color);

}


/* =========================
   INDICADOR NO LEÍDA
   ========================= */

#navbar-notificaciones-menu .navbar-notificacion.no-leida {

    position: relative;

}


/*
 * Pequeño indicador lateral.
 * No dependemos solamente del color para indicar
 * que la notificación está pendiente de leer.
 */

#navbar-notificaciones-menu
.navbar-notificacion.no-leida::before {

    content: "";

    position: absolute;

    left: 4px;
    top: 13px;

    width: 4px;
    height: 4px;

    border-radius: 50%;

    background-color: var(--bs-primary);

}


/* =========================
   NOTIFICACIÓN LEÍDA
   ========================= */

#navbar-notificaciones-menu
.navbar-notificacion.leida {

    opacity: 0.78;

}


/* =========================
   DIVISORES
   ========================= */

#navbar-notificaciones-menu .notificacion-divider {

    margin: 0;

}


/* =========================
   SIN NOTIFICACIONES
   ========================= */

#navbar-notificaciones-menu .notificaciones-vacio {

    padding: 22px 16px;

    text-align: center;

    color: var(--bs-secondary-color);

    font-size: 0.875rem;

}


/* =========================
   FOOTER
   ========================= */

#navbar-notificaciones-menu .notificaciones-footer {

    padding: 11px 16px;

    text-align: center;

    font-size: 0.85rem;

    font-weight: 500;

    text-decoration: none;

}


/* =========================
   AJUSTE EN PANTALLAS PEQUEÑAS
   ========================= */

@media (max-width: 576px) {

    #navbar-notificaciones-menu {

        width: 340px;

    }

}

</style>


<div
    id="navbar-notificaciones-menu"
    class="dropdown-menu dropdown-menu-lg dropdown-menu-end"
>

    <!-- =========================
         ENCABEZADO
         ========================= -->

    <div class="notificaciones-header">

        Últimas
        <?php echo count($notificaciones); ?>
        notificaciones

    </div>


    <div class="dropdown-divider"></div>


    <!-- =========================
         SIN NOTIFICACIONES
         ========================= -->

    <?php if (empty($notificaciones)) : ?>

        <div class="notificaciones-vacio">

            No hay notificaciones.

        </div>


    <?php else : ?>


        <!-- =========================
             LISTADO
             ========================= -->

        <?php foreach ($notificaciones as $notificacion) : ?>

            <?php

            $color = notificacionColor(
                $notificacion['tipo']
            );

            $icono = notificacionIcono(
                $notificacion['tipo']
            );

            $estado = (
                (int)$notificacion['leida'] === 0
            )
                ? 'no-leida'
                : 'leida';

            ?>


            <a
                href="<?php echo BASE_URL; ?>notificaciones/abrir?id=<?php echo $notificacion['id']; ?>"
                class="navbar-notificacion <?php echo $estado; ?>"
            >

                <!-- ICONO -->

                <div
                    class="navbar-notificacion-icono text-<?php echo $color; ?>"
                >

                    <i class="<?php echo $icono; ?>"></i>

                </div>


                <!-- CONTENIDO -->

                <div class="navbar-notificacion-contenido">

                    <!-- TITULO -->

                    <span
                        class="navbar-notificacion-titulo"
                    >

                        <?php echo htmlspecialchars(
                            $notificacion['titulo']
                        ); ?>

                    </span>


                    <!-- MENSAJE -->

                    <?php if (
                        !empty($notificacion['mensaje'])
                    ) : ?>

                        <span
                            class="navbar-notificacion-mensaje"
                        >

                            <?php echo htmlspecialchars(
                                $notificacion['mensaje']
                            ); ?>

                        </span>

                    <?php endif; ?>


                    <!-- FECHA -->

                    <span
                        class="navbar-notificacion-fecha"
                    >

                        <?php echo notificacionFecha(
                            $notificacion['fecha_creacion']
                        ); ?>

                    </span>

                </div>

            </a>


            <div class="dropdown-divider notificacion-divider"></div>


        <?php endforeach; ?>


    <?php endif; ?>


    <!-- =========================
         FOOTER
         ========================= -->

    <a
        href="<?php echo BASE_URL; ?>notificaciones"
        class="notificaciones-footer"
    >

        Ver todas las notificaciones

    </a>

</div>