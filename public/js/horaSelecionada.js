// Não consigo ter certeza se é necessário. Posteriormente será excluída essa função.

document.addEventListener('DOMContentLoaded', function () {
  const campoHora = document.getElementById('horaSelecionada');
  if (campoHora) {
    campoHora.addEventListener('change', function () {
      const dataSelecionada = new Date(this.value);
      const hoje = new Date();

      hoje.setHours(0, 0, 0, 0);
      dataSelecionada.setHours(0, 0, 0, 0);

      if (dataSelecionada < hoje) {
        Swal.fire({
          title: 'Erro!',
          text: 'A data selecionada já passou. Escolha uma data futura.',
          icon: 'error',
          confirmButtonText: 'OK',
          customClass: {
          confirmButton: 'button-red'
          }
        });
      }
    });
  }
});