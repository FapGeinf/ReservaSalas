const btn = document.getElementById('toggleSidebar');
const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('overlay');
const helpBtn = document.querySelector('.help-btn');

function toggleSidebar() {
    const isOpen = sidebar.classList.toggle('open');
    btn.classList.toggle('moved', isOpen);
    helpBtn.classList.toggle('moved', isOpen);
    btn.textContent = isOpen ? '×' : '☰';
    overlay.classList.toggle('active', isOpen);
    document.body.classList.toggle('sidebar-open', isOpen);
}

btn.addEventListener('click', toggleSidebar);
overlay.addEventListener('click', toggleSidebar);

document.addEventListener('DOMContentLoaded', function () {
    const salaSelect = document.getElementById('salaSelecionada');
    const dataInput = document.getElementById('dataSelecionada');
    const container = document.getElementById('reservasContainer');
    const itensReserva = container.querySelectorAll('.reserva-item');
    const semReservasMsg = document.getElementById('semReservasMsg');

    function filtrarReservas() {
        const salaId = salaSelect.value;
        const dataSelecionada = dataInput.value.trim().substring(0, 10);

        let algumItemVisivel = false;

        itensReserva.forEach(item => {
            const itemSalaId = item.getAttribute('data-sala-id');
            const inicio = item.getAttribute('data-inicio');
            const fim = item.getAttribute('data-fim');

            const matchSala = !salaId || itemSalaId == salaId;

            let matchData = true;
            if (dataSelecionada) {
                matchData = (dataSelecionada >= inicio && dataSelecionada <= fim);
            }

            if (matchSala && matchData) {
                item.style.display = 'block';
                algumItemVisivel = true;
            } else {
                item.style.display = 'none';
            }
        });

        if (semReservasMsg) {
            semReservasMsg.style.display = algumItemVisivel ? 'none' : 'block';
        }
    }

    filtrarReservas();


    salaSelect.addEventListener('change', filtrarReservas);

    if (window.flatpickr && dataInput._flatpickr) {
        dataInput._flatpickr.set('onChange', function (selectedDates, dateStr) {
            dataInput.value = dateStr;
            filtrarReservas();
        });
    } else {
        dataInput.addEventListener('change', filtrarReservas);
        dataInput.addEventListener('input', filtrarReservas);
    }
});