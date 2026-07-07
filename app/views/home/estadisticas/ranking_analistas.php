<div class="card shadow-sm">

    <div class="card-header">

        <h3 class="card-title fw-bold">
            🏆 Ranking de Analistas
        </h3>

        <div class="card-tools">
            <span id="ranking-analistas-anio" class="badge bg-primary">
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
                    <th class="text-end">Adjudicados</th>
                    <th class="text-end">Cotizaciones</th>
                </tr>

            </thead>

            <tbody id="ranking-analistas-body">

            <?php

            $cotizaciones = [];

            foreach ($top_analistas_cotizaciones as $item) {

                $cotizaciones[$item['analista']] = $item['total'];

            }

            ?>

            <?php foreach ($top_analistas_adjudicados as $i => $analista): ?>

                <?php

                $nombre = $analista['analista'];

                $adjudicados = $analista['total'];

                $cot = $cotizaciones[$nombre] ?? 0;

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

                        <span class="badge bg-success fs-6">

                            <?= $adjudicados ?>

                        </span>

                    </td>

                    <td class="text-end">

                        <span class="badge bg-primary fs-6">

                            <?= $cot ?>

                        </span>

                    </td>

                </tr>

            <?php endforeach; ?>

            <?php if (empty($top_analistas_adjudicados)): ?>

                <tr>

                    <td colspan="4" class="text-center text-muted py-4">

                        No existen registros.

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>