  function abrirModalDetalhes(event) {
    document.getElementById('detalheSala').innerText = event.title || '';
    document.getElementById('detalheUnidade').innerText = event.extendedProps.unidade || '';
    document.getElementById('detalheHorario').innerText = `${event.extendedProps.hora_inicio} - ${event.extendedProps.hora_fim}` || '';
    document.getElementById('detalheResponsavel').innerText = event.extendedProps.responsavel || '';

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