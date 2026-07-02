<?php

$nombre_usuario = $nombre_usuario ?? '';
$foto_usuario = $foto_usuario ?? '';
$usuario = $usuario ?? [];
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
                            <p class="text-secondary mb-3">Estatus de cotizaciones</p>
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

                                    <!-- COTIZACIONES -->
                                    <h6 class="text-muted mb-3 fw-bold">
                                        <i class="bi bi-file-earmark-text me-1"></i>
                                        Cotizaciones
                                    </h6>

                                    <div class="row g-3 mb-4">

                                        <!-- TOTAL -->
                                        <div class="col-xl-3 col-md-6">

                                            <div class="small-box text-bg-primary shadow-sm">

                                                <div class="inner">

                                                    <h3>
                                                        <?= $estadisticas['total_cotizaciones'] ?? 0 ?>
                                                    </h3>

                                                    <p>
                                                        Total realizadas
                                                    </p>

                                                </div>

                                                <a 
                                                    href="#"
                                                    class="small-box-footer"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalCotizaciones">

                                                    Ver detalle

                                                    <i class="bi bi-arrow-right-circle ms-1"></i>

                                                </a>

                                            </div>

                                        </div>


                                        <!-- ENVIADAS -->
                                        <div class="col-xl-3 col-md-6">

                                            <div class="small-box text-bg-success shadow-sm">

                                                <div class="inner">

                                                    <h3>
                                                        <?= $estadisticas['total_enviadas'] ?? 0 ?>
                                                    </h3>

                                                    <p>
                                                        Enviadas
                                                    </p>

                                                </div>

                                                <a href="#" class="small-box-footer">

                                                    Ver detalle
                                                    <i class="bi bi-arrow-right-circle ms-1"></i>

                                                </a>

                                            </div>

                                        </div>


                                        <!-- PENDIENTES -->
                                        <div class="col-xl-3 col-md-6">

                                            <div class="small-box text-bg-warning shadow-sm">

                                                <div class="inner">

                                                    <h3>
                                                        <?= $estadisticas['total_pendientes'] ?? 0 ?>
                                                    </h3>

                                                    <p>
                                                        Pendientes
                                                    </p>

                                                </div>

                                                <a href="#" class="small-box-footer">

                                                    Ver detalle
                                                    <i class="bi bi-arrow-right-circle ms-1"></i>

                                                </a>

                                            </div>

                                        </div>


                                        <!-- RECHAZADAS -->
                                        <div class="col-xl-3 col-md-6">

                                            <div class="small-box text-bg-danger shadow-sm">

                                                <div class="inner">

                                                    <h3>
                                                        <?= $estadisticas['total_rechazadas'] ?? 0 ?>
                                                    </h3>

                                                    <p>
                                                        Rechazadas
                                                    </p>

                                                </div>

                                                <a href="#" class="small-box-footer">

                                                    Ver detalle
                                                    <i class="bi bi-arrow-right-circle ms-1"></i>

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                    <!-- ADJUDICADOS -->
                                    <h6 class="text-muted mb-3">Adjudicados</h6>

                                    <div class="row g-3 mb-4">

                                        <div class="col-md-6">
                                            <div class="small-box text-bg-info">
                                                <div class="inner">
                                                    <h3>58</h3>
                                                    <p>Total adjudicados</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="bi bi-trophy"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="small-box text-bg-primary">
                                                <div class="inner">
                                                    <h3>12</h3>
                                                    <p>Adjudicados este año</p>
                                                </div>
                                                <div class="icon">
                                                    <i class="bi bi-calendar-check"></i>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <!-- RESUMEN -->
                                    <div class="card shadow-sm">

                                        <div class="card-header">
                                            <h3 class="card-title">
                                                Resumen general
                                            </h3>
                                        </div>

                                        <div class="card-body p-0">

                                            <table class="table table-striped mb-0">

                                                <tbody>

                                                    <tr>
                                                        <th>Cotizaciones totales</th>
                                                        <td class="text-end">
                                                            <?= $estadisticas['total_cotizaciones'] ?? 0 ?>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Enviadas</th>
                                                        <td class="text-end">
                                                            <?= $estadisticas['total_enviadas'] ?? 0 ?>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Pendientes</th>
                                                        <td class="text-end">
                                                            <?= $estadisticas['total_pendientes'] ?? 0 ?>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <th>Rechazadas</th>
                                                        <td class="text-end">
                                                            <?= $estadisticas['total_rechazadas'] ?? 0 ?>
                                                        </td>
                                                    </tr>

                                                </tbody>

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
                                                            class="form-control"
                                                            value="<?= htmlspecialchars($usuario['nombre'] ?? '') ?>">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">
                                                            Apellido paterno
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="apellido_paterno"
                                                            class="form-control"
                                                            value="<?= htmlspecialchars($usuario['apellido_paterno'] ?? '') ?>">
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label class="form-label">
                                                            Apellido materno
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="apellido_materno"
                                                            class="form-control"
                                                            value="<?= htmlspecialchars($usuario['apellido_materno'] ?? '') ?>">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            Usuario
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="usuario"
                                                            class="form-control"
                                                            readonly
                                                            value="<?= htmlspecialchars($usuario['usuario'] ?? '') ?>">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            Correo electrónico
                                                        </label>

                                                        <input
                                                            type="email"
                                                            name="correo"
                                                            class="form-control"
                                                            value="<?= htmlspecialchars($usuario['correo'] ?? '') ?>">
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label class="form-label">
                                                            Teléfono
                                                        </label>

                                                        <input
                                                            type="text"
                                                            name="telefono"
                                                            class="form-control"
                                                            value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>">
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


<!-- MODAL DETALLE COTIZACIONES -->
<div class="modal fade" id="modalCotizaciones" tabindex="-1" aria-labelledby="modalCotizacionesLabel" aria-hidden="true">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="modalCotizacionesLabel">
                    Detalle de cotizaciones
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- FILTROS -->
                <div class="d-flex gap-2 mb-3">

                    <!-- AÑO (DINÁMICO) -->
                    <select id="filtro-anio-cotizaciones" class="form-select form-select-sm">
                        <?php foreach ($anios as $anio): ?>
                            <option value="<?= $anio['anio'] ?>">
                                <?= $anio['anio'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- MES -->
                    <select id="filtro-mes-cotizaciones" class="form-select form-select-sm">
                        <option value="">Todos los meses</option>
                        <option value="1">Enero</option>
                        <option value="2">Febrero</option>
                        <option value="3">Marzo</option>
                        <option value="4">Abril</option>
                        <option value="5">Mayo</option>
                        <option value="6">Junio</option>
                        <option value="7">Julio</option>
                        <option value="8">Agosto</option>
                        <option value="9">Septiembre</option>
                        <option value="10">Octubre</option>
                        <option value="11">Noviembre</option>
                        <option value="12">Diciembre</option>
                    </select>

                </div>

                <!-- RESUMEN -->
                <div class="row g-3 mb-4">

                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                               
                            <small class="text-muted">Total año</small>
                                <h3 class="fw-bold" id="total-anio">0</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <small class="text-muted">Promedio mensual</small>
                                <h3 class="fw-bold" id="promedio-mensual">0</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <small class="text-muted">Mejor mes</small>
                                <h3 class="fw-bold" id="mejor-mes">-</h3>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- GRÁFICA -->
                <div class="card shadow-sm">

                    <div class="card-header">
                        <h5 class="mb-0">Cotizaciones por mes</h5>
                    </div>

                    <div class="card-body">

                        <div style="height: 250px;">
                            <canvas id="graficaCotizaciones"></canvas>
                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cerrar
                </button>
            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const BASE_URL = '<?= BASE_URL ?>';
</script>
<script src="<?= BASE_URL ?>assets/js/especificos/usuario/perfil.js"></script>