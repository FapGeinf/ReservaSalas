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

$(document).ready(function() {
  $('#dataSelecionada').on('change', function() {
    const salaId = $('#verReservasModal').data('sala-id');
    carregarReservas(salaId);
  });

  $('#verReservasModal').on('show.bs.modal', function(event) {
    const button = $(event.relatedTarget);
    const salaId = button.data('sala-id');
    $('#verReservasModal').data('sala-id', salaId);

    const hoje = new Date().toISOString().split('T')[0];
    $('#dataSelecionada').val(hoje);

    carregarReservas(salaId);
  });
});
