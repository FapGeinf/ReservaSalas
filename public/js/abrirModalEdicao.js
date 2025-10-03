function abrirModalEdicao(id, horaInicio, horaFim, dataInicio, salaId) {
  if (!id) {
    alert('Erro: ID da reserva ausente!');
    return;
  }

  // Preenche os campos
  document.getElementById('data_inicio').value = dataInicio;
  document.getElementById('hora_inicio').value = horaInicio;
  document.getElementById('data_fim').value = horaFim;
  document.getElementById('reserva-numero').textContent = `Reserva ${id}`;
  document.getElementById('form-editar-reserva').action = `/reservas/${id}`;

  // Preenche o select de salas
  const selectSala = document.getElementById('sala_id');
  selectSala.innerHTML = ''; // limpa opções anteriores

  salasDisponiveis.forEach(sala => {
    const option = document.createElement('option');
    option.value = sala.id;
    option.textContent = sala.nome;

    if (sala.id === salaId) {
      option.selected = true;
    }
    selectSala.appendChild(option);
  });

  // Abre o modal
  const modal = new bootstrap.Modal(document.getElementById('modal-editar-reserva'));
  modal.show();
}