document.getElementById('diaInteiro').addEventListener('change', function () {
  const inicio = document.getElementById('hora_inicio');
  const termino = document.getElementById('hora_termino');

    if (this.checked) {
      // marca com o dia inteiro: define horários e desativa Edição
      inicio.value = '08:00';
      termino.value = '20:00';
      inicio.readOnly = true;
      termino.readOnly = true;
      }
      
      else {
      // permite editar novamente
      inicio.readOnly = false;
      termino.readOnly = false;
      inicio.value = '';
      termino.value = "";
    }
  }
);