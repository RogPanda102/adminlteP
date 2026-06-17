document.addEventListener('DOMContentLoaded', () => {

    const pandaObject =
        document.getElementById('panda-svg');

    pandaObject.addEventListener('load', () => {

        const svgDoc =
            pandaObject.contentDocument;

        const pupilaIzquierda =
            svgDoc.getElementById('pupilaIzquierda');

        const pupilaDerecha =
            svgDoc.getElementById('pupilaDerecha');

        const ojoIzquierdo =
            svgDoc.getElementById('ojoIzquierdo');

        const ojoDerecho =
            svgDoc.getElementById('ojoDerecho');

        console.log('SVG cargado');

        let ojoX = 0;
        let ojoY = 0;

        document.addEventListener('mousemove', (e) => {

            // Pupilas
            const pupilaX =
                (e.clientX / window.innerWidth - 0.5) * 20;

            const pupilaY =
                (e.clientY / window.innerHeight - 0.5) * 20;

            // Ojos
            ojoX =
                (e.clientX / window.innerWidth - 0.5) * 14;

            ojoY =
                (e.clientY / window.innerHeight - 0.5) * 14;

            pupilaIzquierda.setAttribute(
                'transform',
                `translate(${pupilaX} ${pupilaY})`
            );

            pupilaDerecha.setAttribute(
                'transform',
                `translate(${pupilaX} ${pupilaY})`
            );

            ojoIzquierdo.setAttribute(
                'transform',
                `translate(${ojoX} ${ojoY}) scale(1 1)`
            );

            ojoDerecho.setAttribute(
                'transform',
                `translate(${ojoX} ${ojoY}) scale(1 1)`
            );

        });

function parpadear() {

    // Ocultar pupilas
    pupilaIzquierda.style.opacity = '0';
    pupilaDerecha.style.opacity = '0';

    // Cerrar ojos
    ojoIzquierdo.setAttribute(
        'transform',
        `translate(${ojoX} ${ojoY}) scale(1 0.08)`
    );

    ojoDerecho.setAttribute(
        'transform',
        `translate(${ojoX} ${ojoY}) scale(1 0.08)`
    );

    setTimeout(() => {

        // Abrir ojos
        ojoIzquierdo.setAttribute(
            'transform',
            `translate(${ojoX} ${ojoY}) scale(1 1)`
        );

        ojoDerecho.setAttribute(
            'transform',
            `translate(${ojoX} ${ojoY}) scale(1 1)`
        );

        // Mostrar pupilas
        pupilaIzquierda.style.opacity = '1';
        pupilaDerecha.style.opacity = '1';

    }, 150);

}

        // Primer parpadeo aleatorio
        setTimeout(() => {

            parpadear();

        }, 2000);

        // Parpadeo automático
        setInterval(() => {

            parpadear();

        }, 4000);

    });

});