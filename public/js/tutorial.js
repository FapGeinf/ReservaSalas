document.addEventListener("DOMContentLoaded", function () {
    const tutorialJaVisto = window.tutorialJaVisto;
    const tutorialUrl = window.tutorialUrl;
    const csrfToken = window.csrfToken;

    const steps = [
        {
            element: '[data-help="cards-salas"]',
            text: "📋 Aqui estão listadas todas as salas com seus estados (disponível ou em manutenção). Você pode ver o nome, imagem e a cor identificadora.",
        },
        {
            element: '[data-help="mini-calendario"]',
            text: "🗓️ Este é o mini calendário. Use-o para navegar rapidamente entre os meses.",
        },
        {
            element: '[data-help="calendario-principal"]',
            text: "📆 Este é o calendário principal. Clique em uma data para ver ou fazer uma reserva.",
        },
    ];

    let currentStep = 0;

    function showStep(index) {
        if (index >= steps.length) {
            // Marcar tutorial como visto no banco
            fetch(tutorialUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({})
            });
            return;
        }

        const step = steps[index];
        const el = document.querySelector(step.element);
        if (!el) return;

        const tooltip = document.createElement('div');
        tooltip.innerText = step.text;
        tooltip.className = 'tutorial-tooltip';
        Object.assign(tooltip.style, {
            position: 'absolute',
            background: '#fff',
            border: '1px solid #ccc',
            padding: '10px',
            borderRadius: '8px',
            boxShadow: '0 2px 10px rgba(0,0,0,0.1)',
            zIndex: 1000,
            maxWidth: '300px',
        });

        document.body.appendChild(tooltip);
        const rect = el.getBoundingClientRect();
        tooltip.style.top = (rect.top + window.scrollY + 20) + 'px';
        tooltip.style.left = (rect.left + window.scrollX + 20) + 'px';

        el.style.outline = '3px solid #0d6efd';

        const next = document.createElement('button');
        next.innerText = (index === steps.length - 1) ? 'Finalizar' : 'Próximo';
        next.className = 'btn btn-primary btn-sm mt-2';
        next.onclick = () => {
            tooltip.remove();
            el.style.outline = '';
            showStep(index + 1);
        };
        tooltip.appendChild(document.createElement('br'));
        tooltip.appendChild(next);
    }

    if (!tutorialJaVisto) {
        showStep(currentStep);
    }
});
