<?php
    $total_cotizaciones = $total_cotizaciones ?? '';
    $total_enviadas = $total_enviadas ?? '';
    $total_respaldo = $total_respaldo ?? '';
    $total_reenviar = $total_reenviar ?? '';
?>
<div class="row mb-3">
    <div class="col-12">
        <h4 class="fw-bold">
            Resumen de Cotizaciones 2026
        </h4>
        <p class="text-muted">
            Estadísticas generales del año 2026.
        </p>
    </div>
</div>

<div class="row">

    <!-- TOTAL -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-primary">
            <div class="inner">
                <h3><?= $total_cotizaciones ?></h3>
                <p>Total de cotizaciones</p>
            </div>
            <div class="icon">
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path clip-rule="evenodd" fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z"></path>
                <path clip-rule="evenodd" fill-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z"></path>
            </svg>
            </div>
        </div>
    </div>
    

    <!-- ENVIADAS -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3><?= $total_enviadas ?></h3>
                <p>Cotizaciones enviadas</p>
            </div>
            <div class="icon">
                <div class="icon">
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-2.814a.75.75 0 10-1.22-.872l-3.236 4.53-1.545-1.545a.75.75 0 10-1.06 1.06l2.167 2.167a.75.75 0 001.14-.094l3.754-5.246z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- RESPALDO -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner">
                <h3><?= $total_respaldo ?></h3>
                <p>Cotizaciones de respaldo</p>
            </div>
            <div class="icon">
                <div class="icon">
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M7.5 3.75A2.25 2.25 0 005.25 6v12A2.25 2.25 0 007.5 20.25h9A2.25 2.25 0 0018.75 18V8.56a2.25 2.25 0 00-.659-1.591l-2.56-2.56A2.25 2.25 0 0013.94 3.75H7.5z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- REENVIAR -->
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3><?= $total_reenviar ?></h3>
                <p>Pendientes de reenviar</p>
            </div>
            <div class="icon">
                <div class="icon">
                    <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M12 3.75a8.25 8.25 0 017.846 5.682.75.75 0 01-1.423.47A6.75 6.75 0 105.25 12a6.75 6.75 0 0011.53 4.773H15a.75.75 0 010-1.5h3.75A.75.75 0 0119.5 16v3.75a.75.75 0 01-1.5 0v-1.406A8.25 8.25 0 1112 3.75z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

</div>


