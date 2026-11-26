
<div class="container-fluid">

    <!-- ENCABEZADO -->
    <div class="mb-4">
        <h4 class="fw-semibold mb-1">
            <i class="bi bi-people me-2 text-primary"></i>
            Administración de usuarios
        </h4>

        <p class="text-body-secondary mb-0">
            Consulta y supervisa los usuarios registrados en el sistema.
        </p>
    </div>

    <!-- OPCIONES -->
    <div class="row g-3">

        <!-- USUARIOS REGISTRADOS -->
        <div class="col-12 col-md-6 col-xl-4">

            <a
                href="<?= BASE_URL ?>administracion/usuarios/registro"
                class="text-decoration-none text-reset"
            >

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center justify-content-center
                                    rounded-3 bg-primary bg-opacity-10
                                    text-primary mb-3"
                             style="width: 55px; height: 55px;">

                            <i class="bi bi-people fs-3"></i>

                        </div>

                        <h5 class="fw-semibold mb-2">
                            Usuarios registrados
                        </h5>

                        <p class="text-body-secondary mb-3">
                            Consulta el registro general de usuarios,
                            sus roles y su fecha de registro.
                        </p>

                        <span class="text-primary fw-semibold small">
                            Consultar usuarios
                            <i class="bi bi-arrow-right ms-1"></i>
                        </span>

                    </div>

                </div>

            </a>

        </div>

        <!-- HISTORIAL DE ACTIVIDAD -->
        <div class="col-12 col-md-6 col-xl-4">

            <a
                href="<?= BASE_URL ?>administracion/historial"
                class="text-decoration-none text-reset"
            >

                <div class="card shadow-sm border-0 h-100">

                    <div class="card-body p-4">

                        <div class="d-flex align-items-center justify-content-center
                                    rounded-3 bg-warning bg-opacity-10
                                    text-warning mb-3"
                            style="width: 55px; height: 55px;">

                            <i class="bi bi-clock-history fs-3"></i>

                        </div>

                        <h5 class="fw-semibold mb-2">
                            Historial de actividad
                        </h5>

                        <p class="text-body-secondary mb-3">
                            Consulta los movimientos y cambios registrados
                            por los usuarios dentro del sistema.
                        </p>

                        <span class="text-warning fw-semibold small">
                            Consultar historial
                            <i class="bi bi-arrow-right ms-1"></i>
                        </span>

                    </div>

                </div>

            </a>

        </div>

    </div>

</div>
