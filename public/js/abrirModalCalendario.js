function abrirModalCalendario(salaId) {
  console.log("Sala selecionada:", salaId);
  $('#sala_fk').val(salaId); // Define a sala no formulário
  $('#modalCalendario').modal('show');
}

$(document).ready(function() {
  $('#reservaForm').submit(function(e) {
    e.preventDefault();

    const submitBtn = $(this).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html(
      '<span class="spinner-border spinner-border-sm" role="status"></span> Salvando...'
    );

    $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      data: $(this).serialize(),

      success: function(response) {
        if (response.success) {
          // Fecha o modal de reserva

          $('#modalReserva').modal('hide');
          // Mostra mensagem de sucesso
          Swal.fire({
            position: 'top',
            title: 'Sucesso!',
            text: 'Reserva realizada com sucesso!',
            icon: 'success',
            confirmButtonText: 'Fechar',
            customClass: {
              confirmButton: 'button-green'
            }

          }).then((result) => {
            // Redireciona para a home após clicar em OK
            window.location.href = homeUrl;
          });
        }
      },

      error: function(xhr) {
        Swal.fire({
          position: 'top',
          title: 'Desculpe!',
          text: xhr.responseJSON?.message ||
            'Erro ao realizar reserva',
          icon: 'error',
          confirmButtonText: 'Fechar',
          customClass: {
            confirmButton: 'button-red'
          }
        });
      },

      complete: function() {
        submitBtn.prop('disabled', false).html('Salvar Reserva');
      }
    });
  });
});

// Verificação em tempo real
$('#hora_inicio, #hora_termino').change(function() {
  verificarDisponibilidade();
});

function verificarDisponibilidade() {
  const salaId = $('#sala_fk').val();
  const data = $('#data_reserva').val();
  const horaInicio = $('#hora_inicio').val();
  const horaTermino = $('#hora_termino').val();

  if (!salaId || !data || !horaInicio || !horaTermino) return;

  $.ajax({
    url: '/verificar-disponibilidade',
    type: 'POST',
    data: {
      sala_id: salaId,
      data_reserva: data,
      hora_inicio: horaInicio,
      hora_termino: horaTermino,
      _token: $('meta[name="csrf-token"]').attr('content')
    },

    success: function(response) {
      if (response.disponivel) {
        $('#disponibilidade-status').html(
          '<span class="text-success">Horário disponível</span>');
        $('.btn-submit').prop('disabled', false);
      } 
      
      else {
        $('#disponibilidade-status').html('<span class="text-danger">' + response
          .mensagem +
          '</span>');
        $('.btn-submit').prop('disabled', true);
      }
    }
  });
}
