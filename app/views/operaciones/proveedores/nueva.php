<div class="card">

    <div class="card-header">
        <h3 class="card-title">
            Nuevo Proveedor
        </h3>
    </div>

    <form action="<?= BASE_URL ?>proveedores/guardar" method="POST">

        <div class="card-body">

            <div class="row">

                <!-- PROVEEDOR -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Proveedor
                    </label>
                    <input
                        type="text"
                        name="proveedor"
                        class="form-control"
                        maxlength="255"
                        required
                    >
                </div>

                <!-- CONTACTO -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Contacto
                    </label>
                    <input
                        type="text"
                        name="contacto"
                        class="form-control"
                        maxlength="255"
                    >
                </div>

            </div>

            <div class="row">

                <!-- SERVICIOS -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Servicios
                    </label>
                    <input
                        type="text"
                        name="servicios"
                        class="form-control"
                        maxlength="255"
                    >
                </div>

                <!-- UBICACION -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Ubicación
                    </label>
                    <input
                        type="text"
                        name="ubicacion"
                        class="form-control"
                        maxlength="255"
                    >
                </div>

            </div>

            <div class="row">

                <!-- TELEFONO -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Teléfono
                    </label>
                    <input
                        type="text"
                        name="telefono"
                        class="form-control"
                        maxlength="50"
                    >
                </div>

                <!-- EMAIL -->
                <div class="col-md-6 mb-3">
                    <label class="form-label">
                        Email
                    </label>
                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        maxlength="255"
                    >
                </div>

            </div>

            <div class="row">

                <!-- ENLACE -->
                <div class="col-md-12 mb-3">
                    <label class="form-label">
                        Enlace
                    </label>
                    <input
                        type="url"
                        name="enlace"
                        class="form-control"
                        maxlength="500"
                        placeholder="https://..."
                    >
                </div>

            </div>

        </div>

        <div class="card-footer d-flex justify-content-between">

            <a
                href="<?= BASE_URL ?>proveedores"
                class="btn btn-secondary"
            >
                <i class="bi bi-arrow-left"></i>
                Regresar
            </a>

            <button
                type="submit"
                class="btn btn-success"
            >
                <i class="bi bi-save"></i>
                Guardar proveedor
            </button>

        </div>

    </form>

</div>