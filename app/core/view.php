<?php

class View
{
    // =========================
    // Render con plantilla
    // =========================
    public static function render($viewPath, $data = [])
    {
        extract($data);

        $view = VIEWS_PATH . $viewPath . '.php';

        require_once VIEWS_PATH . 'layouts/master.php';
    }

    // =========================
    // Render simple
    // =========================
    public static function renderLogin($viewPath, $data = [])
    {
        extract($data);

        require_once VIEWS_PATH . $viewPath . '.php';
    }
}