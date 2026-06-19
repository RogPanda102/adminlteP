<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>404 - Página no encontrada</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            height: 100vh;
            background: #2f3e4e;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .container {
            text-align: center;
            max-width: 500px;
            padding: 20px;
        }

        .code {
            font-size: 7rem;
            font-weight: bold;
            line-height: 1;
        }

        h1 {
            font-size: 1.8rem;
            margin: 15px 0;
        }

        p {
            color: rgba(255,255,255,0.7);
            margin-bottom: 30px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        a, button {
            padding: 10px 18px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
        }

        .btn-primary {
            background: #ffffff;
            color: #2f3e4e;
        }

        .btn-secondary {
            background: transparent;
            border: 1px solid #ffffff;
            color: #ffffff;
        }

        .btn-secondary:hover {
            background: rgba(255,255,255,0.1);
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="code">404</div>

        <h1>Página no encontrada</h1>

        <p>
            El recurso que intentas acceder no existe o fue movido.
        </p>

        <div class="buttons">

            <a href="<?= BASE_URL ?>login" class="btn-primary">
                Inicio
            </a>

            <button class="btn-secondary" onclick="history.back()">
                Regresar
            </button>

        </div>

    </div>

</body>
</html>