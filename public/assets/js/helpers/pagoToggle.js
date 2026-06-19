function configurarControlPago(config)
{
    const pago = document.querySelector(config.pago);
    const total = document.querySelector(config.total);
    const diaPago = document.querySelector(config.diaPago);

    if (!pago || !total || !diaPago) return;

    function aplicarEstado()
    {
        const esPendiente = pago.value === 'pendiente';

        total.disabled = esPendiente;
        diaPago.disabled = esPendiente;

        if (esPendiente) {

            total.value = '';
            diaPago.value = '';

            total.classList.add('bg-light');
            diaPago.classList.add('bg-light');

        } else {

            total.classList.remove('bg-light');
            diaPago.classList.remove('bg-light');
        }
    }

    pago.addEventListener('change', aplicarEstado);

    aplicarEstado();
}