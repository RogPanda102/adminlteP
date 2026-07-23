<div class="card">

    <div class="card-header">

        <h3 class="card-title">
            <i class="fas fa-bell me-2"></i>
            Notificaciones
        </h3>

    </div>

    <div class="card-body p-0">

        <?php if (empty($notificaciones)) : ?>

            <div class="p-4 text-center text-muted">

                <i class="fas fa-bell-slash fa-2x mb-3"></i>

                <p class="mb-0">
                    No tienes notificaciones.
                </p>

            </div>

        <?php else : ?>

            <div class="list-group list-group-flush">

                <?php foreach ($notificaciones as $notificacion) : ?>

                    <?php

                        $color = colorNotificacion($notificacion['tipo']);

                        $icono = iconoNotificacion($notificacion['tipo']);

                        $esLeida = (int)$notificacion['leida'] === 1;

                        $itemClass = $esLeida
                            ? 'list-group-item bg-light'
                            : 'list-group-item border-start border-' . $color . ' border-4';

                        $tituloClass = $esLeida
                            ? ''
                            : 'fw-bold';

                    ?>
                    <div class="<?php echo $itemClass; ?>">

                        <div class="list-group-item">

                            <div class="d-flex justify-content-between align-items-start">

                                <div class="me-3">

                                    <h5 class="mb-1 <?php echo $tituloClass; ?>">

                                        <i class="<?php echo $icono; ?> text-<?php echo $color; ?> me-2"></i>

                                        <?php echo htmlspecialchars($notificacion['titulo']); ?>

                                        <?php if (!$esLeida) : ?>

                                            <span class="badge bg-<?php echo $color; ?> ms-2">

                                                Nueva

                                            </span>

                                        <?php endif; ?>

                                    </h5>

                                    <p class="mb-1 text-muted">

                                        <?php echo htmlspecialchars(
                                            $notificacion['mensaje']
                                        ); ?>

                                    </p>

                                    <small class="text-muted">

                                        <i class="far fa-clock me-1"></i>

                                        <?php echo fechaNotificacion(
                                            $notificacion['fecha_creacion']
                                        ); ?>

                                    </small>

                                </div>

                                <?php if (!empty($notificacion['url'])) : ?>

                                    <a
                                        href="<?php echo BASE_URL; ?>notificaciones/abrir?id=<?php echo $notificacion['id']; ?>"
                                        class="btn btn-sm btn-outline-primary">

                                        Abrir

                                        <i class="fas fa-arrow-right ms-1"></i>

                                    </a>

                                <?php endif; ?>

                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>

            </div>

        <?php endif; ?>

    </div>

</div>