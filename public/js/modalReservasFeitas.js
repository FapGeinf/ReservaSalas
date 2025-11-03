jQuery.extend(jQuery.fn.dataTableExt.oSort, {
  "date-euro-pre": function(a) {
    if ($.trim(a) !== '') {
      var parts = a.split(' | ');
      var dateParts = parts[0].split('/');
      var timeParts = parts[1].split(':');
      return new Date(dateParts[2], dateParts[1] - 1, dateParts[0], timeParts[0], timeParts[1]).getTime();
    }
    return 0;
  },
  "date-euro-asc": function(a, b) { return a - b; },
  "date-euro-desc": function(a, b) { return b - a; }
});

// 🔹 Variável global para armazenar as reservas carregadas
let reservas = [];

$(document).ready(function() {
  const $salaSelect = $('#salaSelecionada');
  const $dataInput = $('#dataSelecionada');
  const $reservasContainer = $('#reservasContainer');

  // Preenche select com salas disponíveis
  if (typeof salasDisponiveis !== 'undefined' && salasDisponiveis.length > 0) {
    salasDisponiveis.forEach(sala => $salaSelect.append(`<option value="${sala.id}">${sala.nome}</option>`));
  } else {
    $salaSelect.append('<option value="">Nenhuma sala disponível</option>');
  }

  // Data padrão para hoje
  $dataInput.val(new Date().toISOString().split('T')[0]);

  // Função para carregar reservas
  function carregarReservasFixas() {
    const salaId = $salaSelect.val();
    const data = $dataInput.val();

    if (!salaId) {
      $reservasContainer.html('<div class="text-center fs-13 fw-medium text-secondary my-2"><i class="bi bi-info-circle me-1"></i>Nenhuma sala selecionada.</div>');
      return;
    }

    $reservasContainer.html('<div class="text-center fs-13 fw-medium text-secondary my-2"><i class="bi bi-arrow-repeat me-1 text-primary"></i>Carregando reservas...</div>');

    $.ajax({
      url: `/reservas/sala/${salaId}`,
      method: 'GET',
      data: { data: data },
      success: function(res) {
        reservas = res || [];
        if (!reservas.length) {
          $reservasContainer.html('<div class="text-center fs-13 text-secondary fw-medium my-2"><i class="bi bi-exclamation-triangle me-1"></i>Nenhuma reunião agendada.<br><span class="text-success"><i class="bi bi-check-circle me-1"></i>Sala disponível para reserva.</span></div>');
          return;
        }

        const reservasHtml = reservas.map(r => {
          const horaInicio = r.data_inicio ? r.data_inicio.split(' ')[1]?.slice(0,5) || '??:??' : '??:??';
          const horaFim = r.data_fim ? r.data_fim.split(' ')[1]?.slice(0,5) || '??:??' : '??:??';
          const usuario = r.user?.name || 'Usuário desconhecido';
          const unidade = r.user?.unidade?.nome || 'Unidade desconhecida';
          return `
            <div class="border rounded shadow-sm p-2 mb-2" style="background-color: #f7f7f7;">
              <div class="d-flex align-items-start px-1 mb-1" style="gap: 30px;">
                <span class="fw-bold fs-13" style="color: #374151; width: 100px;">Horário:</span>
                <span class="fs-13" style="color: #374151;"><i class="bi bi-clock"></i> ${horaInicio} - ${horaFim}</span>
              </div>
              <div class="d-flex align-items-start px-1 mb-1" style="gap: 30px;">
                <span class="fw-bold fs-13" style="color: #374151; width: 100px; white-space: nowrap;">Reservado por:</span>
                <span class="fs-13" style="color: #374151;"><i class="bi bi-person"></i> ${usuario}</span>
              </div>
              <div class="px-1 mt-2">
                <button class="btn btn-sm btn-primary btn-view-reserva" data-id="${r.id}"><i class="bi bi-eye"></i> Visualizar</button>
              </div>
            </div>
          `;
        }).join('');

        $reservasContainer.html(reservasHtml);
      },
      error: function() {
        $reservasContainer.html('<p class="text-center text-danger">Erro ao carregar reservas.</p>');
      }
    });
  }

  // 🔹 Clique no botão "Visualizar" na listagem
  $(document).on('click', '.btn-view-reserva', function() {
    const reservaId = $(this).data('id');
    const reserva = reservas.find(r => String(r.id) === String(reservaId));
    if (!reserva) return;

    $('#reservaSala').text(reserva.sala?.nome || $('#salaSelecionada option:selected').text() || 'Sala desconhecida');
    $('#reservaData').text(reserva.data_inicio?.split(' ')[0] ?? '??/??/????');
    $('#reservaHoraInicio').text(reserva.data_inicio?.split(' ')[1]?.slice(0,5) ?? '??:??');
    $('#reservaHoraFim').text(reserva.data_fim?.split(' ')[1]?.slice(0,5) ?? '??:??');
    $('#reservaUnidade').text(reserva.user?.unidade?.nome ?? 'Unidade desconhecida');
    $('#reservaResponsavel').text(reserva.user?.name ?? 'Usuário desconhecido');

    $('#btnEditarReservaUnica').data('id', reserva.id);
    $('#btnExcluirReservaUnica').data('id', reserva.id).data('url', `/reservas/${reserva.id}/delete`);

    new bootstrap.Modal(document.getElementById('modalReservaUnica')).show();
  });

  // 🔹 Editar reserva (fecha o modal de visualização antes)
  $('#modalReservaUnica').off('click', '#btnEditarReservaUnica').on('click', '#btnEditarReservaUnica', function() {
    const id = $(this).data('id');
    const reserva = reservas.find(r => String(r.id) === String(id));
    if (!reserva) return;

    // Fecha o modal de visualização
    const modalVisualizacao = bootstrap.Modal.getInstance(document.getElementById('modalReservaUnica'));
    if (modalVisualizacao) modalVisualizacao.hide();

    abrirModalEdicao(
      reserva.id,
      reserva.data_inicio?.split(' ')[1]?.slice(0,5) ?? '',
      reserva.data_fim?.split(' ')[1]?.slice(0,5) ?? '',
      reserva.data_inicio?.split(' ')[0] ?? '',
      reserva.sala?.id || $salaSelect.val()
    );
  });

  // 🔹 Excluir reserva
  $('#modalReservaUnica').off('click', '#btnExcluirReservaUnica').on('click', '#btnExcluirReservaUnica', function() {
    const id = $(this).data('id');
    if (!id) return;

    const deleteAction = `/reservas/${id}/delete`;
    $('#deleteForm').attr('action', deleteAction);

    const modalExcluirEl = document.getElementById('modalExcluirReserva');
    if (modalExcluirEl) {
      new bootstrap.Modal(modalExcluirEl).show();
    } else if (confirm('Confirmar exclusão da reserva?')) {
      $('#deleteForm').submit();
    }
  });

  // Carrega reservas iniciais
  carregarReservasFixas();

  // Atualiza ao trocar sala ou data
  $salaSelect.on('change', carregarReservasFixas);
  $dataInput.on('change', carregarReservasFixas);
});
