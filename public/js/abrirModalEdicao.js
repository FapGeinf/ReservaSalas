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
  selectSala.innerHTML = '';

  const optionPadrao = document.createElement('option');
  optionPadrao.value = '';
  optionPadrao.textContent = 'Selecione uma opção';
  optionPadrao.disabled = true;
  selectSala.appendChild(optionPadrao);

  salasDisponiveis.forEach(sala => {
    const option = document.createElement('option');
    option.value = sala.id;
    option.textContent = sala.nome;
    selectSala.appendChild(option);
  });

  // <<< AQUI ESTÁ A CORREÇÃO >>>
  selectSala.value = salaId;

  // Abre o modal
  new bootstrap.Modal(document.getElementById('modal-editar-reserva')).show();
}
