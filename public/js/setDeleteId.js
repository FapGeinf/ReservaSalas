// Função para definir o ID da reserva a ser excluída
function setDeleteId(reservaId) {
  // Define a ação do formulário com a rota correta
  $('#deleteForm').attr('action', `/reservas/${reservaId}`);

  // Armazena o ID para uso posterior se necessário
  $('#confirmDeleteModal').data('reserva-id', reservaId);
}

// Evento de submit do formulário de exclusão
$('#deleteForm').on('submit', function(e) {
  e.preventDefault();

  const form = this;

  $(form).find('button[type="submit"]').prop('disabled', true).html(
    '<span class="spinner-border spinner-border-sm" role="status"></span> Excluindo...'
  );

  $.ajax({
    url: form.action,
    type: 'POST',
    data: $(form).serialize(),
    success: function(response) {
      // Fecha o modal
      $('#confirmDeleteModal').modal('hide');

      // Mostra mensagem de sucesso
      Swal.fire({
        title: 'Sucesso!',
        text: 'Reserva excluída com sucesso!',
        icon: 'success',
        confirmButtonText: 'OK',
        customClass: {
          confirmButton: 'button-green'
        }

      }).then(() => {
        // Recarrega a página ou atualiza a tabela
        location.reload();
      });
    },

    error: function(xhr) {
      Swal.fire({
        title: 'Erro!',
        text: xhr.responseJSON?.message || 'Erro ao excluir reserva',
        icon: 'error'
      });
    },

    complete: function() {
      // Reativa o botão
      $(form).find('button[type="submit"]').prop('disabled', false).text('Excluir');
    }
  });
});