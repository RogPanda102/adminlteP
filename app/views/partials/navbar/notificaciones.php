<?php

            $navbarNotificaciones =
                $navbarNotificaciones ?? [];

            $total =
                $navbarNotificaciones['total'] ?? 0;

            $notificaciones =
                $navbarNotificaciones['notificaciones'] ?? [];

            ?>
<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">

    <span class="dropdown-item dropdown-header">

        Últimas

        <?php echo count($notificaciones); ?>

        notificaciones

    </span>

    <div class="dropdown-divider"></div>

    <?php if (empty($notificaciones)) : ?>

        <span class="dropdown-item text-center text-muted">

            No hay notificaciones.

        </span>

    <?php else : ?>

        <?php foreach ($notificaciones as $notificacion) : ?>

            <?php

            $color = notificacionColor(
                $notificacion['tipo']
            );

            $icono = notificacionIcono(
                $notificacion['tipo']
            );

            ?>

            <a
                href="<?php echo BASE_URL; ?>notificaciones/abrir?id=<?php echo $notificacion['id']; ?>"
                class="dropdown-item">

                <i class="<?php echo $icono; ?> text-<?php echo $color; ?> me-2"></i>

                <?php echo htmlspecialchars(
                    $notificacion['titulo']
                ); ?>

                <span class="float-end text-secondary fs-7">

                    <?php echo notificacionFecha(
                        $notificacion['fecha_creacion']
                    ); ?>

                </span>

            </a>

            <div class="dropdown-divider"></div>

        <?php endforeach; ?>

    <?php endif; ?>

    <a
        href="<?php echo BASE_URL; ?>notificaciones"
        class="dropdown-item dropdown-footer">

        Ver todas las notificaciones

    </a>

</div>