function abrirModalDetalhes(event) {
  // Preenche os campos do modal
  document.getElementById('detalheSala').innerText = event.title || '';
  document.getElementById('detalheUnidade').innerText = event.extendedProps.unidade || '';
  document.getElementById('detalheHoraInicio').innerText = event.extendedProps.hora_inicio || '';
  document.getElementById('detalheHoraFim').innerText = event.extendedProps.hora_fim || '';
  document.getElementById('detalheResponsavel').innerText = event.extendedProps.responsavel || '';

  const dataReserva = event.extendedProps.data_inicio
    ? new Date(event.extendedProps.data_inicio).toLocaleDateString('pt-BR', { timeZone: 'UTC' })
    : '';
  document.getElementById('detalheData').innerText = dataReserva;

  // Botão editar
  document.getElementById('btnEditar').onclick = function () {
    abrirModalEdicao(
      event.id,
      event.extendedProps.hora_inicio,
      event.extendedProps.hora_fim,
      event.extendedProps.data_inicio,
      event.extendedProps.sala_id
    );

    const modal = bootstrap.Modal.getInstance(document.getElementById('modalDetalhesReserva'));
    modal.hide();
  };

  // Botão excluir
  document.getElementById('btnExcluir').onclick = function () {
    const deleteForm = document.getElementById('deleteForm');
    if (!deleteForm) return;

    // Define o action correto para exclusão
    deleteForm.action = `/reservas/${event.id}`;

    // Fecha o modal de detalhes e abre o de confirmação
    const modalDetalhes = bootstrap.Modal.getInstance(document.getElementById('modalDetalhesReserva'));
    if (modalDetalhes) modalDetalhes.hide();

    const confirmModal = new bootstrap.Modal(document.getElementById('modalConfirmarExclusao'));
    confirmModal.show();

    // Garante que o botão confirmar do modal de exclusão funcione
    const btnConfirmar = document.getElementById('btnConfirmarExclusao');
    btnConfirmar.onclick = function () {
      deleteForm.submit();
    };
  };

  // Exibe o modal de detalhes
  const modalDetalhes = new bootstrap.Modal(document.getElementById('modalDetalhesReserva'));
  modalDetalhes.show();
}
