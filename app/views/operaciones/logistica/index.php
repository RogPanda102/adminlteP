<main class="app-main">

    <div class="app-content">
        <div class="container-fluid">

            <!-- ENCABEZADO -->
            <div class="row mb-4">
                <div class="col-12">
                    <h3 class="fw-semibold mb-1">
                        Logística
                    </h3>

                    <p class="text-muted mb-0">
                        Panel de control del área de logística.
                    </p>
                </div>
            </div>

            <!-- TARJETAS PRINCIPALES -->
            <div class="row justify-content-center g-4 mt-3">

                <!-- INVENTARIO PENDIENTE -->
                <div class="col-12 col-md-6 col-xl-3">

                    <div class="card shadow-sm border-0 logistica-card h-100">

                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center py-5">

                            <div class="logistica-icon mb-3">
                                <i class="bi bi-box-seam"></i>
                            </div>

                            <h5 class="fw-semibold mb-2">
                                Inventario pendiente
                            </h5>

                            <p class="text-muted small mb-0">
                                Consulta el inventario que se encuentra pendiente.
                            </p>

                        </div>

                    </div>

                </div>

                <!-- ESPACIO 2 -->
                <div class="col-12 col-md-6 col-xl-3">

                    <div class="card shadow-sm border-0 logistica-card h-100">

                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center py-5">

                            <div class="logistica-icon mb-3">
                                <i class="bi bi-grid"></i>
                            </div>

                            <h5 class="fw-semibold mb-2">
                                Próximamente
                            </h5>

                            <p class="text-muted small mb-0">
                                Nuevo módulo de logística.
                            </p>

                        </div>

                    </div>

                </div>

                <!-- ESPACIO 3 -->
                <div class="col-12 col-md-6 col-xl-3">

                    <div class="card shadow-sm border-0 logistica-card h-100">

                        <div class="card-body text-center d-flex flex-column justify-content-center align-items-center py-5">

                            <div class="logistica-icon mb-3">
                                <i class="bi bi-clipboard-data"></i>
                            </div>

                            <h5 class="fw-semibold mb-2">
                                Próximamente
                            </h5>

                            <p class="text-muted small mb-0">
                                Nuevo módulo de logística.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

</main>

<style>

/* TARJETAS DE LOGÍSTICA */
.logistica-card {
    min-height: 230px;
    border-radius: 12px;
    transition: transform 0.25s ease,
                box-shadow 0.25s ease,
                background-color 0.25s ease;
}

/* EFECTO HOVER */
.logistica-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 22px rgba(0, 0, 0, 0.10) !important;
    background-color: #fcfdff;
}

/* ICONOS */
.logistica-icon {
    width: 65px;
    height: 65px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(13, 110, 253, 0.08);
    color: #0d6efd;
    font-size: 30px;
}

</style>