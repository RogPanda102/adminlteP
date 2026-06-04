<?php

class Router
{
    private $routes = [];

    // =========================
    // Registrar ruta GET
    // =========================
    public function get($ruta, $accion)
    {
        $this->routes['GET'][$ruta] = $accion;
    }

    // =========================
    // Registrar ruta POST
    // =========================
    public function post($ruta, $accion)
    {
        $this->routes['POST'][$ruta] = $accion;
    }

    // =========================
    // Ejecutar rutas
    // =========================
    public function dispatch()
    {

        $metodo = $_SERVER['REQUEST_METHOD'];

        $url = $_GET['url'] ?? '/';

        $url = '/' . trim($url, '/');

        // Ruta no encontrada
        if (!isset($this->routes[$metodo][$url])) {

            require_once VIEWS_PATH . 'errors/404.php';
            return;

        }

        $accion = $this->routes[$metodo][$url];

        // =========================
        // Separar controlador y método
        // =========================
        $partes = explode('@', $accion);

        $controladorCompleto = $partes[0];
        $metodoControlador = $partes[1];

        // =========================
        // Convertir namespace a ruta
        // =========================
        $controladorRuta = str_replace('\\', '/', $controladorCompleto);

        $controllerFile = CONTROLLERS_PATH . $controladorRuta . '.php';

        // =========================
        // Validar existencia
        // =========================
        if (!file_exists($controllerFile)) {

            require_once VIEWS_PATH . 'errors/404.php';
            return;

        }

        require_once $controllerFile;

        // =========================
        // Obtener nombre clase
        // =========================
        $segmentos = explode('\\', $controladorCompleto);

        $nombreClase = end($segmentos);

        // =========================
        // Instanciar controlador
        // =========================
        $controller = new $nombreClase();

        // =========================
        // Validar método
        // =========================
        if (!method_exists($controller, $metodoControlador)) {

            require_once VIEWS_PATH . 'errors/404.php';
            return;

        }

        // =========================
        // Ejecutar método
        // =========================
        $controller->$metodoControlador();

    }
}