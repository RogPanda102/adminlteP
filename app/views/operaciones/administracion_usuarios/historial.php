
<?php

$historial = $historial ?? [];
$usuarios = $usuarios ?? [];
$usuario_id = $usuario_id ?? null;

?>

<div class="container-fluid">

    <!-- ENCABEZADO -->
    <div class="mb-4">

        <h4 class="fw-semibold mb-1">
            <i class="bi bi-clock-history me-2 text-primary"></i>
            Historial de actividad
        </h4>

        <p class="text-body-secondary mb-0">
            Consulta los movimientos y cambios registrados en el sistema.
        </p>

    </div>

    <!-- TABLA -->
    <div class="card shadow-sm border-0">

        <div class="card-header bg-body border-bottom py-3">

            <div class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center gap-3">

                <div>
                    <h5 class="card-title fw-semibold mb-1">
                        <i class="bi bi-list-ul me-2 text-primary"></i>
                        Registro de movimientos
                    </h5>

                    <small class="text-body-secondary">
                        <?= $usuario_id !== null
                            ? 'Movimientos realizados por el usuario seleccionado.'
                            : 'Consulta general de la actividad registrada.' ?>
                    </small>
                </div>

                <div class="position-relative"
                     style="width: min(100%, 320px);">

                    <i class="bi bi-search position-absolute top-50
                              start-0 translate-middle-y ms-3
                              text-body-secondary"></i>

                    <input
                        type="search"
                        id="buscar-historial"
                        class="form-control ps-5"
                        placeholder="Buscar movimiento..."
                        autocomplete="off"
                    >

                </div>

            </div>

        </div>

        <div class="card-body">

            <div id="tabla-historial" class="erp-tabla"></div>

        </div>

    </div>

</div>


<!-- MODAL DETALLE DEL MOVIMIENTO -->
<div class="modal fade" id="modalDetalleHistorial" tabindex="-1"
     aria-labelledby="modalDetalleHistorialLabel"
     aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <div>
                    <h5 class="modal-title fw-semibold"
                        id="modalDetalleHistorialLabel">
                        <i class="bi bi-clock-history me-2 text-primary"></i>
                        Detalle del movimiento
                    </h5>

                    <small class="text-body-secondary"
                           id="detalle-historial-subtitulo">
                    </small>
                </div>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Cerrar">
                </button>

            </div>

            <div class="modal-body">

                <div id="detalle-historial-contenido"></div>

            </div>

            <div class="modal-footer">

                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                    Cerrar
                </button>

            </div>

        </div>

    </div>

</div>


<script>
window.historial = <?= json_encode(
    $historial,
    JSON_UNESCAPED_UNICODE |
    JSON_HEX_TAG |
    JSON_HEX_APOS |
    JSON_HEX_QUOT |
    JSON_HEX_AMP
) ?>;
</script>
<script>
    window.usuarios = <?= json_encode(
        $usuarios,
        JSON_UNESCAPED_UNICODE |
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_QUOT |
        JSON_HEX_AMP
    ) ?>;
</script>

<link
    href="https://unpkg.com/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
    rel="stylesheet"
>

<script src="https://unpkg.com/tabulator-tables@6.4.0/dist/js/tabulator.min.js"></script>

<script src="<?= BASE_URL ?>assets/js/especificos/administracion_usuarios/historial.js"></script>
