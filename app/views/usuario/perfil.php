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


                                <!-- Estadisticas Tab -->
                                <div
                                    class="tab-pane fade"
                                    id="timeline"
                                    role="tabpanel"
                                    aria-labelledby="timeline-tab">
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

                                    <div class="card mt-3">
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
                                </div>

                                <!-- Ajustes tab -->
                                <div
                                    class="tab-pane fade"
                                    id="settings"
                                    role="tabpanel"
                                    aria-labelledby="settings-tab">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">
                                                Información personal
                                            </h3>
                                        </div>

                                        <form action="" method="POST" enctype="multipart/form-data">

                                            <div class="card-body">

                                                <div class="row g-3">

                                                    <div class="col-md-4">
                                                        <label class="form-label">
                                                            Nombre
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="nombre"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">
                                                            Apellido paterno
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="apellido_paterno"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">
                                                            Apellido materno
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="apellido_materno"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            Usuario
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="usuario"
                                                            class="form-control"
                                                            readonly>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            Correo electrónico
                                                        </label>

                                                        <input
                                                            type="email"
                                                            name="correo"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            Teléfono
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="telefono"
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            Fotografía
                                                        </label>

                                                        <input
                                                            type="file"
                                                            name="foto"
                                                            class="form-control">
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="card-footer">

                                                <button
                                                    type="submit"
                                                    class="btn btn-primary">

                                                    Guardar cambios

                                                </button>

                                            </div>

                                        </form>

                                    </div>

                                    <!-- SEGUNDO FORMULARIO -->

                                        <div class="card mt-3">

                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    Seguridad
                                                </h3>
                                            </div>

                                            <form action="" method="POST">

                                                <div class="card-body">

                                                    <div class="row g-3">

                                                        <div class="col-md-4">

                                                            <label class="form-label">
                                                                Contraseña actual
                                                            </label>

                                                            <input
                                                                type="password"
                                                                name="password_actual"
                                                                class="form-control">

                                                        </div>

                                                        <div class="col-md-4">

                                                            <label class="form-label">
                                                                Nueva contraseña
                                                            </label>

                                                            <input
                                                                type="password"
                                                                name="password"
                                                                class="form-control">

                                                        </div>

                                                        <div class="col-md-4">

                                                            <label class="form-label">
                                                                Confirmar contraseña
                                                            </label>

                                                            <input
                                                                type="password"
                                                                name="password_confirmacion"
                                                                class="form-control">

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="card-footer">

                                                    <button
                                                        type="submit"
                                                        class="btn btn-warning">

                                                        Actualizar contraseña

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
        </div>
    </div>