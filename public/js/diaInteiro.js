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
