<?php
    $total_cotizaciones = $total_cotizaciones ?? '';
    $total_enviadas     = $total_enviadas ?? '';
    $total_respaldo     = $total_respaldo ?? '';
    $total_reenviar     = $total_reenviar ?? '';
    $anio_actual        = $anio_actual ?? date('Y');
    $anios              = $anios ?? [];
?>

<!-- =========================
     ENCABEZADO
========================== -->
<div class="row mb-3">

    <div class="col-12">

        <div class="d-flex justify-content-between align-items-center">

            <div>

                <h4 class="fw-bold mb-1">
                    Resumen de Cotizaciones
                </h4>

                <p id="descripcion-anio" class="text-muted mb-0">
                    Estadísticas generales del año <?= $anio_actual ?>.
                </p>

            </div>

            <div class="dropdown">

                <button
                    id="btn-anio"
                    class="btn btn-outline-secondary btn-sm dropdown-toggle fw-bold"
                    type="button"
                    data-bs-toggle="dropdown">

                    <?= $anio_actual ?>

                </button>

                <ul class="dropdown-menu">

                    <?php foreach ($anios as $item): ?>

                        <li>

                            <a
                                class="dropdown-item anio-selector"
                                data-anio="<?= $item['anio'] ?>">

                                <?= $item['anio'] ?>

                            </a>

                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        </div>

    </div>

</div>

<!-- =========================
     CARDS
========================== -->
<div class="row mb-4">

    <div class="col-md-6 col-xl-3">

        <div class="small-box text-bg-primary">

            <div class="inner">

                <h3 id="total_cotizaciones"><?= $total_cotizaciones ?></h3>

                <p>Total de cotizaciones</p>

            </div>

            <div class="icon">

                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z"/>
                </svg>

            </div>

        </div>

    </div>

    <div class="col-md-6 col-xl-3">

        <div class="small-box text-bg-success">

            <div class="inner">

                <h3 id="total_enviadas"><?= $total_enviadas ?></h3>

                <p>Cotizaciones enviadas</p>

            </div>

            <div class="icon">

                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-2.814a.75.75 0 10-1.22-.872l-3.236 4.53-1.545-1.545a.75.75 0 10-1.06 1.06l2.167 2.167a.75.75 0 001.14-.094l3.754-5.246z"/>
                </svg>

            </div>

        </div>

    </div>

    <div class="col-md-6 col-xl-3">

        <div class="small-box text-bg-warning">

            <div class="inner">

                <h3 id="total_respaldo"><?= $total_respaldo ?></h3>

                <p>Cotizaciones respaldo</p>

            </div>

            <div class="icon">

                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M7.5 3.75A2.25 2.25 0 005.25 6v12A2.25 2.25 0 007.5 20.25h9A2.25 2.25 0 0018.75 18V8.56a2.25 2.25 0 00-.659-1.591l-2.56-2.56A2.25 2.25 0 0013.94 3.75H7.5z"/>
                </svg>

            </div>

        </div>

    </div>

    <div class="col-md-6 col-xl-3">

        <div class="small-box text-bg-danger">

            <div class="inner">

                <h3 id="total_reenviar"><?= $total_reenviar ?></h3>

                <p>Pendientes</p>

            </div>

            <div class="icon">

                <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M12 3.75a8.25 8.25 0 017.846 5.682.75.75 0 01-1.423.47A6.75 6.75 0 105.25 12a6.75 6.75 0 0011.53 4.773H15a.75.75 0 010-1.5h3.75A.75.75 0 0119.5 16v3.75a.75.75 0 01-1.5 0v-1.406A8.25 8.25 0 1112 3.75z"/>
                </svg>

            </div>

        </div>

    </div>

</div>

<!-- =========================
     ESTADÍSTICAS
========================== -->
<div class="row">

    <div class="col-lg-12 mb-4">

        <?php require __DIR__ . '/estadisticas/ranking_analistas.php'; ?>

    </div>

    <div class="col-lg-6 mb-4">

        <!-- Próximo módulo -->

    </div>

</div>

<script>
    const BASE_URL = '<?= BASE_URL ?>';
</script>

<script src="<?= BASE_URL ?>assets/js/especificos/home/dashboard.js"></script>