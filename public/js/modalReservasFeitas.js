jQuery.extend(jQuery.fn.dataTableExt.oSort, {
  "date-euro-pre": function(a) {
    if ($.trim(a) !== '') {
      var parts = a.split(' | ');
      var dateParts = parts[0].split('/');
      var timeParts = parts[1].split(':');

      return new Date(
        dateParts[2], // ano
        dateParts[1] - 1, // mês (0-11)
        dateParts[0], // dia
        timeParts[0], // horas
        timeParts[1] // minutos
      ).getTime();
    }
    return 0;
  },

  "date-euro-asc": function(a, b) {
    return a - b;
  },

  "date-euro-desc": function(a, b) {
    return b - a;
  }
});

$(document).ready(function() {
  // Inicialização única da DataTable
  var table = $('#reservas').DataTable({
    order: [
      [0, 'desc']
    ], // Ordena pela coluna de Hora Início (índice 2)

    columnDefs: [{
      targets: [2, 3], // Colunas de data/hora
      type: 'date-euro' // Usa nosso tipo de ordenação personalizado
    }],

    language: {
      url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
      search: "Procurar:",
      lengthMenu: "Mostrar _MENU_ registros por página",
      info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
      infoEmpty: "Nenhum registro disponível",
      infoFiltered: "(filtrado de _MAX_ registros totais)",
      zeroRecords: "Nenhum registro encontrado",
      paginate: {
        first: "Primeira",
        last: "Última",
        next: "Próximo",
        previous: "Anterior"
      }
    },

    scrollCollapse: true,
    responsive: true,
    paging: true,
    searching: true,
    lengthChange: true
  });

  // Função para o modal de confirmação de exclusão
  function setDeleteAction(action) {
    $('#deleteForm').attr('action', action);
  }

  // Exemplo de como você poderia usar (adaptar conforme necessário)
  $('.btn-delete').on('click', function() {
    var deleteUrl = $(this).data('url');
    setDeleteAction(deleteUrl);
  });
});

function selecionarSala(salaId) {
  console.log('Sala selecionada:', salaId); // Depuração
  document.getElementById('sala_fk').value = salaId;
}

function carregarReservas(salaId) {
  const dataSelecionada = document.getElementById('dataSelecionada').value;

  $('#reservasContainer').html(
    '<p class="text-center"><i class="bi bi-arrow-repeat" style="color: #2a64e7;"></i> Carregando reservas...</p>'
  );

  $.ajax({
    url: '/reservas/sala/' + salaId, // Rota para buscar as reservas da sala
    type: 'GET',
    data: {
      data: dataSelecionada
    },

    success: function(reservas) {
      let html = '';

      if (reservas.length === 0) {
        html = '<p class="reserva-vazia">Nenhuma reserva para esta data.</p>';
      } else {

        html += '<div class="reservas-grid">';
        reservas.forEach(reserva => {
          const unidade = reserva.user?.unidade?.nome ??
            'Unidade Desconhecida';
          const usuario = reserva.user ? reserva.user.name : 'N/A';
          const horaInicio = reserva.data_inicio.split(' ')[1];
          const horaFim = reserva.data_fim.split(' ')[1];

          html += `
          <div class="reserva-card">
            <span class="reserva-info">
              <i class="bi bi-building"></i>
              <strong>Unidade:</strong> ${unidade}
            </span>

            <span class="reserva-info">
              <i class="bi bi-clock"></i>
              <strong>Hora:</strong> ${horaInicio} - ${horaFim}
            </span>

            <span class="reserva-info">
              <i class="bi bi-person"></i>
              <strong>Reservado por:</strong> ${usuario}
            </span>
          </div>
          `;
        });
      html += '</div>';
    }

    $('#reservasContainer').html(html);
  },

    error: function() {
      $('#reservasContainer').html(
        '<p class="text-center"><i class="bi bi-exclamation-circle-fill me-1" style="color: #b22720;"></i> Erro ao carregar reservas.</p>'
      );
    }
  });
}

// CODIGO ANTIGO
// $(document).ready(function() {
//   $('#dataSelecionada').on('change', function() {
//     const salaId = $('#verReservasModal').data('sala-id');
//     carregarReservas(salaId);
//   });

//   $('#verReservasModal').on('show.bs.modal', function(event) {
//     const button = $(event.relatedTarget);
//     const salaId = button.data('sala-id');
//     $('#verReservasModal').data('sala-id', salaId);

//     const hoje = new Date().toISOString().split('T')[0];
//     $('#dataSelecionada').val(hoje);

//     carregarReservas(salaId);
//   });
// });

// CODIGO NOVO FUNCIONAL
// $(document).ready(function() {
//   const primeiraSala = salasDisponiveis.length > 0 ? salasDisponiveis[0].id : null;
  
//   if (primeiraSala) {
//     const hoje = new Date().toISOString().split('T')[0];
//     $('#dataSelecionada').val(hoje);
//     carregarReservas(primeiraSala);
//   }

//   $('#dataSelecionada').on('change', function() {
//     if (primeiraSala) {
//       carregarReservas(primeiraSala);
//     }
//   });
// });

$(document).ready(function() {
  const $salaSelect = $('#salaSelecionada');
  const $dataInput = $('#dataSelecionada');
  const $reservasContainer = $('#reservasContainer');

  // Preenche o select com as salas disponíveis
  if (typeof salasDisponiveis !== 'undefined' && salasDisponiveis.length > 0) {
    salasDisponiveis.forEach(sala => {
      $salaSelect.append(`<option value="${sala.id}">${sala.nome}</option>`);
    });
  } else {
    $salaSelect.append('<option value="">Nenhuma sala disponível</option>');
  }

  // Define a data atual como padrão
  const hoje = new Date().toISOString().split('T')[0];
  $dataInput.val(hoje);

  // Função para carregar reservas
  function carregarReservasFixas() {
    const salaId = $salaSelect.val();
    const data = $dataInput.val();

    if (!salaId) {
      $reservasContainer.html('<p class="text-center text-muted">Nenhuma sala selecionada.</p>');
      return;
    }

    $reservasContainer.html(`
      <p class="text-center text-muted">
        <i class="bi bi-arrow-repeat" style="color: #2a64e7;"></i> Carregando reservas...
      </p>
    `);

    $.ajax({
      url: `/reservas/sala/${salaId}`, // endpoint para buscar reservas
      method: 'GET',
      data: { data: data },
      success: function(reservas) {
        if (!reservas || reservas.length === 0) {
          $reservasContainer.html('<p class="text-center text-muted">Nenhuma reserva para esta data.</p>');
          return;
        }

        const reservasHtml = reservas.map(r => {
          // Extrai horas de data_inicio/data_fim
          const horaInicio = r.data_inicio ? r.data_inicio.split(' ')[1] : '??:??';
          const horaFim = r.data_fim ? r.data_fim.split(' ')[1] : '??:??';

          // Pega o nome do usuário ou usa placeholder
          const usuario = r.user && r.user.name ? r.user.name : 'Usuário desconhecido';

          // Pega o nome da unidade, se existir
          const unidade = r.user && r.user.unidade && r.user.unidade.nome 
                          ? r.user.unidade.nome 
                          : 'Unidade desconhecida';

          return `
            <div class="border rounded p-2 mb-2">
              <span><strong>Unidade:</strong> ${unidade}</span><br>
              <span><strong>Horário:</strong> ${horaInicio} - ${horaFim}</span><br>
              <span><strong>Reservado por:</strong> ${usuario}</span>
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

  // Carrega reservas iniciais
  carregarReservasFixas();

  // Atualiza ao trocar sala ou data
  $salaSelect.on('change', carregarReservasFixas);
  $dataInput.on('change', carregarReservasFixas);
});
