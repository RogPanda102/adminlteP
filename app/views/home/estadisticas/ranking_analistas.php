<?php

$moduloActual = $modulo_actual ?? 'cotizaciones';

$ranking = [];

$tituloRanking = 'Cotizaciones';

if ($moduloActual === 'cotizaciones') {

    $ranking = $top_analistas_cotizaciones ?? [];

    $tituloRanking = 'Cotizaciones';

}

if ($moduloActual === 'adjudicados') {

    $ranking = $top_analistas_adjudicados ?? [];

    $tituloRanking = 'Adjudicados';

}

if ($moduloActual === 'servicios') {

    $ranking = $top_analistas_servicios ?? [];

    $tituloRanking = 'Servicios';

}

?>

<div class="card shadow-sm">

    <div class="card-header">

        <h3 class="card-title fw-bold">
            🏆 Ranking de Analistas
        </h3>

        <div class="card-tools">

            <span
                id="ranking-analistas-anio"
                class="badge bg-primary">
                <?= $anio_actual ?>
            </span>

        </div>

    </div>

    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>

                    <th width="60">#</th>

                    <th>Analista</th>

                    <th
                        id="ranking-analistas-titulo"
                        class="text-end">
                        <?= $tituloRanking ?>
                    </th>

                </tr>

            </thead>

            <tbody id="ranking-analistas-body">

                <?php foreach ($ranking as $i => $analista): ?>

                    <?php

                    $nombre = $analista['analista'] ?? '';

                    $total = $analista['total'] ?? 0;

                    ?>

                    <tr>

                        <td>

                            <?php
                            switch ($i + 1) {

                                case 1:
                                    echo "🥇";
                                    break;

                                case 2:
                                    echo "🥈";
                                    break;

                                case 3:
                                    echo "🥉";
                                    break;

                                default:
                                    echo $i + 1;
                            }
                            ?>

                        </td>

                        <td class="fw-semibold">

                            <?= htmlspecialchars($nombre) ?>

                        </td>

                        <td class="text-end">

                            <span class="badge bg-primary fs-6">

                                <?= $total ?>

                            </span>

                        </td>

                    </tr>

                <?php endforeach; ?>

                <?php if (empty($ranking)): ?>

                    <tr>

                        <td
                            colspan="3"
                            class="text-center text-muted py-4">

                            No existen registros.

                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>