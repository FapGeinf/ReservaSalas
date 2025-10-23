  function abrirModalDetalhes(event) {
    document.getElementById('detalheSala').innerText = event.title || '';
    document.getElementById('detalheUnidade').innerText = event.extendedProps.unidade || '';
    // document.getElementById('detalheHorario').innerText = `${event.extendedProps.hora_inicio} - ${event.extendedProps.hora_fim}` || '';
    document.getElementById('detalheHoraInicio').innerText = event.extendedProps.hora_inicio || '';
    document.getElementById('detalheHoraFim').innerText = event.extendedProps.hora_fim || '';
    document.getElementById('detalheResponsavel').innerText = event.extendedProps.responsavel || '';

    const dataReserva = event.extendedProps.data_inicio 
      ? new Date(event.extendedProps.data_inicio).toLocaleDateString('pt-BR', { timeZone: 'UTC' })
      : '';
    document.getElementById('detalheData').innerText = dataReserva;

    document.getElementById('btnEditar').onclick = function() {
      abrirModalEdicao(
        event.id,
        event.extendedProps.hora_inicio,
        event.extendedProps.hora_fim,
        event.extendedProps.data_inicio,
        event.extendedProps.sala_id
      );

      var modal = bootstrap.Modal.getInstance(document.getElementById('modalDetalhesReserva'));
      modal.hide();
    };

    document.getElementById('btnExcluir').onclick = function() {
      setDeleteId(event.id);
      var modal = bootstrap.Modal.getInstance(document.getElementById('modalDetalhesReserva'));
      modal.hide();
      var confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
      confirmModal.show();
    };

    var modalDetalhes = new bootstrap.Modal(document.getElementById('modalDetalhesReserva'));
    modalDetalhes.show();
  }