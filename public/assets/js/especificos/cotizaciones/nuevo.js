document.addEventListener('DOMContentLoaded', function () {

    crearAutocomplete({
        input: '#analista',
        resultados: '#lista-analista',
        url: 'cotizaciones/buscarCatalogoAjax',
        campo: 'analista'
    });

    crearAutocomplete({
        input: '#proveedor',
        resultados: '#lista-proveedor',
        url: 'cotizaciones/buscarCatalogoAjax',
        campo: 'proveedor'
    });

});