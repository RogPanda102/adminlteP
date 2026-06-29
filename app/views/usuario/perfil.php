<?php

$nombre_usuario = $nombre_usuario ?? '';
$foto_usuario = $foto_usuario ?? '';
$estadisticas = $estadisticas ?? [];
$actividad = $actividad ?? [];

?>

<main class="app-main">

    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-3">
                <!-- Profile sidebar -->
                <div class="col-md-3">
                    <!-- About card -->
                    <div class="card">
                        <div class="card-body text-center">
                            <img
                                src="<?= $foto_usuario ?>"
                                class="img-fluid rounded-circle mb-3"
                                width="100"
                                height="100"
                                alt="Foto de perfil" />
                            <h3 class="h5 mb-0">
                                <?= $nombre_usuario ?>
                                
                            </h3>
                            <p class="text-secondary mb-3">Product Designer</p>
                            <ul class="list-group list-group-flush text-start small">
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-secondary">Pendientes</span>
                                    <span class="fw-semibold">12</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-secondary">En proceso</span>
                                    <span class="fw-semibold">8</span>
                                </li>

                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span class="text-secondary">Completados</span>
                                    <span class="fw-semibold">154</span>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>

                <!-- Tabbed content -->
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header p-0 border-bottom-0">
                            <ul class="nav nav-tabs" id="profile-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link active"
                                        id="activity-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#activity"
                                        type="button"
                                        role="tab"
                                        aria-selected="true">
                                        Actividad Reciente
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link"
                                        id="timeline-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#timeline"
                                        type="button"
                                        role="tab"
                                        aria-selected="false">
                                        Estadisticas
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button
                                        class="nav-link"
                                        id="settings-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#settings"
                                        type="button"
                                        role="tab"
                                        aria-selected="false">
                                        Configuración
                                    </button>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <!-- Activity tab -->
                                <div
                                    class="tab-pane fade show active"
                                    id="activity"
                                    role="tabpanel"
                                    aria-labelledby="activity-tab">
                                    <article class="d-flex gap-3 mb-4">
                                        <article class="d-flex gap-3 mb-4">

                                            <div class="flex-grow-1">

                                                <div class="d-flex justify-content-between">
                                                    <h4 class="h6 mb-0">
                                                        Rodrigo Díaz
                                                    </h4>

                                                    <small class="text-secondary">
                                                        23/06/2026 10:35 AM
                                                    </small>
                                                </div>

                                                <p class="mb-0">
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                                </p>

                                            </div>

                                        </article>

                                </div>

                                >
                            </div>

                            <!-- Estadisticas Tab -->

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="small-box text-bg-primary">
                                        <div class="inner">
                                            <h3>125</h3>
                                            <p>Cotizaciones</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="small-box text-bg-success">
                                        <div class="inner">
                                            <h3>58</h3>
                                            <p>Pedidos</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="small-box text-bg-warning">
                                        <div class="inner">
                                            <h3>24</h3>
                                            <p>Servicios</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Resumen anual
                                    </h3>
                                </div>

                                <div class="card-body">

                                    <table class="table table-sm">

                                        <tr>
                                            <th>Cotizaciones 2026</th>
                                            <td>125</td>
                                        </tr>

                                        <tr>
                                            <th>Pedidos 2026</th>
                                            <td>58</td>
                                        </tr>

                                        <tr>
                                            <th>Servicios registrados</th>
                                            <td>24</td>
                                        </tr>

                                        <tr>
                                            <th>Monto gestionado</th>
                                            <td>$1,250,000.00</td>
                                        </tr>

                                    </table>

                                </div>
                            </div>

                            <!-- Ajustes tab -->
                            <div
                                class="tab-pane fade"
                                id="settings"
                                role="tabpanel"
                                aria-labelledby="settings-tab">
                                <form class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label" for="profile-first"> Nombre </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="profile-first"
                                            value="Jane" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="profile-last"> Apellido </label>
                                        <input type="text" class="form-control" id="profile-last" value="Doe" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="profile-email"> Email </label>
                                        <input
                                            type="email"
                                            class="form-control"
                                            id="profile-email"
                                            value="jane@example.com" />
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="profile-role"> Contraseña </label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="profile-role"
                                            value="Product Designer" />
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label" for="profile-bio">Confirmar Contraseña</label>
                                        <textarea class="form-control" id="profile-bio" rows="4">
Designer with a soft spot for design tokens and accessibility.</textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                        <button type="reset" class="btn btn-outline-secondary ms-1">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>