document.getElementById('diaInteiro').addEventListener('change', function () {
  const inicio = document.getElementById('hora_inicio')._flatpickr;
  const termino = document.getElementById('hora_termino')._flatpickr;

  if (this.checked) {
    inicio.setDate("08:00");
    termino.setDate("20:00");
    inicio.input.readOnly = true;
    termino.input.readOnly = true;
  } else {
    inicio.clear();
    termino.clear();
    inicio.input.readOnly = false;
    termino.input.readOnly = false;
  }
});

document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('modal-editar-reserva');
  if (!modal) return;

  const chk = modal.querySelector('#diaInteiro');
  if (!chk) return;

  chk.addEventListener('change', function () {
    const inicioEl = modal.querySelector('#hora_inicio') || document.getElementById('hora_inicio');
    const terminoEl = modal.querySelector('#data_fim') || document.getElementById('data_fim');

    const inicio = inicioEl && inicioEl._flatpickr;
    const termino = terminoEl && terminoEl._flatpickr;
    if (!inicio || !termino) return;

    if (this.checked) {
      inicio.setDate("08:00");
      termino.setDate("20:00");
      inicio.input.readOnly = true;
      termino.input.readOnly = true;
    } else {
      inicio.clear();
      termino.clear();
      inicio.input.readOnly = false;
      termino.input.readOnly = false;
    }
  });
});
