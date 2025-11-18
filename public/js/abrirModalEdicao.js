function abrirModalEdicao(id, horaInicio, horaFim, dataInicio, salaId) {
  if (!id) {
    alert('Erro: ID da reserva ausente!');
    return;
  }

  const modalEl = document.getElementById('modal-editar-reserva');
  const form = document.getElementById('form-editar-reserva');
  const selectSala = document.getElementById('sala_id');

  // limpa e popula options
  selectSala.innerHTML = '';
  const optionPadrao = document.createElement('option');
  optionPadrao.value = '';
  optionPadrao.textContent = 'Selecione uma opção';
  optionPadrao.disabled = true;
  selectSala.appendChild(optionPadrao);

  salasDisponiveis.forEach(sala => {
    const option = document.createElement('option');
    option.value = String(sala.id); // force string
    option.textContent = sala.nome;
    selectSala.appendChild(option);
  });

  // atualiza action do form e número da reserva
  document.getElementById('reserva-numero').textContent = `Reserva ${id}`;
  form.action = `/reservas/${id}`;

  // cria e abre modal
  const modal = new bootstrap.Modal(modalEl);
  modal.show();

  // quando modal estiver totalmente visível, aplicar valores (evita override do flatpickr)
  const onShown = function () {
    // remove listener para não executar múltiplas vezes
    modalEl.removeEventListener('shown.bs.modal', onShown);

    // set select (força string)
    if (salaId !== undefined && salaId !== null) {
      selectSala.value = String(salaId);
      // se precisar garantir que a opção exista:
      if (!selectSala.value) {
        // seleciona a opção correspondente manualmente
        const opt = Array.from(selectSala.options).find(o => o.value == salaId);
        if (opt) selectSala.value = opt.value;
      }
    }

    // inputs (escreve direto nos inputs)
    const dataInput = modalEl.querySelector('#data_inicio');
    const horaInicioInput = modalEl.querySelector('#hora_inicio');
    const horaFimInput = modalEl.querySelector('#data_fim');

    if (dataInput) dataInput.value = dataInicio || '';
    if (horaInicioInput) horaInicioInput.value = horaInicio || '';
    if (horaFimInput) horaFimInput.value = horaFim || '';

    // se o flatpickr já tiver sido inicializado sobre esses inputs, use setDate
    try {
      const fpData = dataInput && dataInput._flatpickr;
      const fpHoraInicio = horaInicioInput && horaInicioInput._flatpickr;
      const fpHoraFim = horaFimInput && horaFimInput._flatpickr;

      if (fpData && dataInicio) {
        // data no formato Y-m-d esperado pelo seu fp
        fpData.setDate(dataInicio, true, "Y-m-d");
      }
      if (fpHoraInicio && horaInicio) {
        fpHoraInicio.setDate(horaInicio, true, "H:i");
      }
      if (fpHoraFim && horaFim) {
        fpHoraFim.setDate(horaFim, true, "H:i");
      }
    } catch (e) {
      // não interrompe execução em caso de erro; valores já foram escritos nos inputs
      // console.warn(e);
    }
  };

  modalEl.addEventListener('shown.bs.modal', onShown);
}
