function abrirModalDetalhes(event) {
  // Preenche os campos do modal
  const detalheSala = document.getElementById('detalheSala');
  const detalheUnidade = document.getElementById('detalheUnidade');
  const detalheHoraInicio = document.getElementById('detalheHoraInicio');
  const detalheHoraFim = document.getElementById('detalheHoraFim');
  const detalheResponsavel = document.getElementById('detalheResponsavel');
  const detalheData = document.getElementById('detalheData');

  if (detalheSala) detalheSala.innerText = event.title || '';
  if (detalheUnidade) detalheUnidade.innerText = event.extendedProps?.unidade || '';
  if (detalheHoraInicio) detalheHoraInicio.innerText = event.extendedProps?.hora_inicio || '';
  if (detalheHoraFim) detalheHoraFim.innerText = event.extendedProps?.hora_fim || '';
  if (detalheResponsavel) detalheResponsavel.innerText = event.extendedProps?.responsavel || '';

  const dataReserva = event.extendedProps?.data_inicio
    ? new Date(event.extendedProps.data_inicio).toLocaleDateString('pt-BR', { timeZone: 'UTC' })
    : '';
  if (detalheData) detalheData.innerText = dataReserva;

  const formEncerrar = document.getElementById('formEncerrar');
  if (formEncerrar) {
    formEncerrar.action = `/reservas/${event.id}/encerrar`;
  }

  // Botões (podem não existir para usuários sem permissão)
  const btnEditar = document.getElementById('btnEditar');
  if (btnEditar) {
    btnEditar.onclick = function () {
      abrirModalEdicao(
        event.id,
        event.extendedProps?.hora_inicio,
        event.extendedProps?.hora_fim,
        event.extendedProps?.data_inicio,
        event.extendedProps?.sala_id
      );

      const instance = bootstrap.Modal.getInstance(document.getElementById('modalDetalhesReserva'));
      if (instance) instance.hide();
    };
  }

  const btnExcluir = document.getElementById('btnExcluir');
  if (btnExcluir) {
    btnExcluir.onclick = function () {
      const deleteForm = document.getElementById('deleteForm');
      if (!deleteForm) return;

      deleteForm.action = `/reservas/${event.id}`;

      const modalDetalhesInstance = bootstrap.Modal.getInstance(document.getElementById('modalDetalhesReserva'));
      if (modalDetalhesInstance) modalDetalhesInstance.hide();

      const confirmEl = document.getElementById('modalConfirmarExclusao');
      if (!confirmEl) return;

      const confirmInstance = bootstrap.Modal.getOrCreateInstance(confirmEl);
      confirmInstance.show();

      const btnConfirmar = document.getElementById('btnConfirmarExclusao');
      if (btnConfirmar) {
        btnConfirmar.onclick = function () {
          deleteForm.submit();
        };
      }
    };
  }

  // Exibe o modal de detalhes
  const modalEl = document.getElementById('modalDetalhesReserva');
  if (!modalEl) return;

  bootstrap.Modal.getOrCreateInstance(modalEl).show();
}
