<!doctype html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <title><?= NOMBRE_SISTEMA ?> | Verificar correo</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="preload" href="<?= BASE_URL ?>css/adminlte.css" as="style">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/adminlte.css">

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
    >

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
    >

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"
    >

    <style>
        .codigo-input {
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 12px;
            text-align: center;
        }

        .icono-correo {
            font-size: 55px;
            color: #0d6efd;
        }
    </style>

</head>

<body class="login-page bg-body-secondary">

    <div class="login-box">

        <div class="card card-outline card-primary">

            <div class="card-body login-card-body">

                <div class="text-center mb-4">

                    <i class="bi bi-envelope-check icono-correo"></i>

                    <h4 class="mt-3">
                        Verifica tu correo
                    </h4>

                </div>

                <p class="login-box-msg">

                    Hemos enviado un código de verificación a tu correo electrónico.

                    <br>

                    Ingresa el código de 6 dígitos para activar tu cuenta.

                </p>

                <!-- FORMULARIO VERIFICAR CÓDIGO -->

                <form
                    action="<?= BASE_URL ?>confirmar-codigo"
                    method="POST"
                >

                    <div class="mb-4">

                        <input
                            type="text"
                            name="codigo"
                            class="form-control codigo-input"
                            placeholder="000000"
                            maxlength="6"
                            minlength="6"
                            pattern="[0-9]{6}"
                            inputmode="numeric"
                            autocomplete="one-time-code"
                            required
                        >

                    </div>

                    <div class="d-grid">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="bi bi-shield-check me-1"></i>

                            Verificar correo

                        </button>

                    </div>

                </form>

                <hr>

                <!-- REENVIAR CÓDIGO -->

                <p class="text-center text-muted mb-2">

                    ¿No recibiste el código?

                </p>

                <form
                    action="<?= BASE_URL ?>reenviar-codigo"
                    method="POST"
                >

                    <div class="d-grid">

                        <button
                            type="submit"
                            class="btn btn-outline-primary"
                        >

                            <i class="bi bi-arrow-clockwise me-1"></i>

                            Reenviar código

                        </button>

                    </div>

                </form>

                <div class="text-center mt-4">

                    <a
                        href="<?= BASE_URL ?>login"
                        class="text-secondary text-decoration-none"
                    >

                        <i class="bi bi-arrow-left"></i>

                        Volver al inicio de sesión

                    </a>

                </div>

            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        <?= mostrar_mensaje() ?>
    </script>

</body>

</html>