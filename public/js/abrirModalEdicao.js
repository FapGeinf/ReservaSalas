function abrirModalEdicao(id, horaInicio, horaFim, dataInicioFull, salaFk, unidadeFk, finalidade) {
    if (!id) return;

    const modalEl = document.getElementById('modal-editar-reserva');
    const form = document.getElementById('form-editar-reserva');
    form.action = `/reservas/${id}`;

    const dataVisual = document.getElementById('data_visual_edit');
    const dataHidden = document.getElementById('data_reserva_edit');
    const hInicioInput = document.getElementById('hora_inicio_edit');
    const hTerminoInput = document.getElementById('hora_termino_edit');
    const selectSala = document.getElementById('sala_fk_edit');
    const selectUnidade = document.getElementById('unidade_fk_edit');
    const selectTipo = document.getElementById('tipo_reserva_edit');

    const apenasData = dataInicioFull ? dataInicioFull.split(' ')[0] : '';

    
    if (dataVisual && !dataVisual._flatpickr) {
        flatpickr(dataVisual, {
            dateFormat: "Y-m-d", 
            altInput: true,      
            altFormat: "d/m/Y",  
            allowInput: true,
            onChange: function(selectedDates, dateStr) {
                if (dataHidden) dataHidden.value = dateStr;
            }
        });
    }

    const configHora = {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        allowInput: true
    };

    if (hInicioInput && !hInicioInput._flatpickr) flatpickr(hInicioInput, configHora);
    if (hTerminoInput && !hTerminoInput._flatpickr) flatpickr(hTerminoInput, configHora);

    if (selectSala) selectSala.value = String(salaFk);
    if (selectUnidade && unidadeFk) selectUnidade.value = String(unidadeFk);
    if (selectTipo && finalidade) selectTipo.value = finalidade;

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();

    const onShown = function () {
        modalEl.removeEventListener('shown.bs.modal', onShown);

        if (dataVisual?._flatpickr) {
            dataVisual._flatpickr.setDate(apenasData, false);
        } else if (dataVisual) {
            dataVisual.value = apenasData;
        }
        if (dataHidden) dataHidden.value = apenasData;

        if (hInicioInput?._flatpickr) {
            hInicioInput._flatpickr.setDate(horaInicio, false);
        }
        if (hTerminoInput?._flatpickr) {
            hTerminoInput._flatpickr.setDate(horaFim, false);
        }
    };

    modalEl.addEventListener('shown.bs.modal', onShown);
}