
<?php

$usuarios = $usuarios ?? [];

?>

<div class="container-fluid">

    <!-- ENCABEZADO -->
    <div class="mb-4">

        <h4 class="fw-semibold mb-1">
            <i class="bi bi-people me-2 text-primary"></i>
            Usuarios registrados
        </h4>

        <p class="text-body-secondary mb-0">
                Consulta la información y fecha de registro de los usuarios.
        </p>

    </div>

    <!-- REGISTRO GENERAL -->
    <div class="card shadow-sm border-0">

        <!-- HEADER -->
        <div class="card-header bg-body border-bottom py-3">

            <div class="d-flex flex-column flex-md-row
                        justify-content-between align-items-md-center gap-3">

                <div>
                    <h5 class="card-title fw-semibold mb-1">
                        <i class="bi bi-person-lines-fill me-2 text-primary"></i>
                        Registro general
                    </h5>

                    <small class="text-body-secondary">
                        Consulta la información y fecha de registro de los usuarios.
                    </small>
                </div>

                <!-- BUSCADOR -->
                <div class="position-relative" style="width: min(100%, 320px);">

                    <i class="bi bi-search position-absolute top-50 start-0
                              translate-middle-y ms-3 text-body-secondary"></i>

                    <input
                        type="search"
                        id="buscar-usuarios"
                        class="form-control ps-5"
                        placeholder="Buscar usuario..."
                        autocomplete="off"
                    >

                </div>

            </div>

        </div>

        <!-- BODY -->
        <div class="card-body">

            <div id="tabla-usuarios" class="erp-tabla"></div>

        </div>

    </div>

</div>

<!-- =====================================================
     DATOS PARA TABULATOR
===================================================== -->

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

<script>
    window.BASE_URL = <?= json_encode(BASE_URL) ?>;
</script>

<!-- =====================================================
     TABULATOR 6.4.0
===================================================== -->

<link
    href="https://unpkg.com/tabulator-tables@6.4.0/dist/css/tabulator_bootstrap5.min.css"
    rel="stylesheet"
>

<script src="https://unpkg.com/tabulator-tables@6.4.0/dist/js/tabulator.min.js"></script>

<!-- JAVASCRIPT DE USUARIOS -->

<script src="<?= BASE_URL ?>assets/js/especificos/administracion_usuarios/registro.js"></script>
