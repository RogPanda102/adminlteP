<?php
function old($campo, $default = '')
{
    $valor = $default;

    if (
        isset($_SESSION['old']) &&
        array_key_exists($campo, $_SESSION['old'])
    ) {
        $valor = $_SESSION['old'][$campo];
    }

    return htmlspecialchars(
        $valor,
        ENT_QUOTES,
        'UTF-8'
    );
}
function limpiarOld()
{
    unset($_SESSION['old']);
}
function guardarOld($datos)
{
    $_SESSION['old'] = $datos;
}
function guardarTab($tab)
{
    $_SESSION['old_tab'] = $tab;
}
function guardarErrores($errores)
{
    $_SESSION['errores'] = $errores;
}
function error($campo)
{
    return $_SESSION['errores'][$campo] ?? null;
}
function limpiarErrores()
{
    unset($_SESSION['errores']);
}
function abrirTab($tab)
{
    $_SESSION['tab_activo'] = $tab;
}
function tabActivo($default = 'activity')
{
    $tab = $_SESSION['tab_activo'] ?? $default;

    unset($_SESSION['tab_activo']);

    return $tab;
}
function oldTab()
{
    if (!empty($_SESSION['old_tab'])) {

        $tab = $_SESSION['old_tab'];

        unset($_SESSION['old_tab']);

        return $tab;
    }

    return 'activity';
}
