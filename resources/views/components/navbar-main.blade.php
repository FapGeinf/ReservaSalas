<head>
  <link rel="stylesheet" href="{{ asset('css/navbar-main.css') }}">
</head>

<!-- NAVBAR COM SALAS -->
<nav class="navbar justify-content-center bg-white pt-4 py-2 border-bottom">
  <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
    @foreach($salas as $sala)
      @php
        $situacao = strtolower(trim($sala->situacao));
        $statusColor = $situacao === 'inativa' ? 'bg-danger' : 'bg-success';
      @endphp

      <div class="d-flex align-items-center justify-content-between px-3 py-2 border rounded shadow-sm" >
        <div class="d-flex flex-column">
          <span class="fw-semibold text-uppercase fs-13">{{ $sala->nome }}</span>
          <div class="d-flex align-items-center gap-1">
            <span class="rounded-circle {{ $statusColor }} status-ball"></span>
            @if($situacao === 'inativa')
              <span class="fw-medium fs-11 text-danger">Em manutenção</span>
            @else
              <span class="fw-medium fs-11 text-success">Disponível</span>
            @endif
          </div>
        </div>

        @if($situacao !== 'inativa')
          <button 
            type="button"
            class="button-blue btn-agendar fs-12 px-2 py-1 ms-3" 
            data-bs-toggle="modal" 
            data-bs-target="#modalReserva"
            data-sala-id="{{ $sala->id }}"
            data-sala-nome="{{ $sala->nome }}">
            <i class="bi bi-calendar-plus me-1" style="font-size: 12px;"></i>
            Reservar
          </button>
        @endif
      </div>
    @endforeach
  </div>
</nav>

<!-- MODAL DE RESERVA -->
<div class="modal fade" id="modalReserva" tabindex="-1" aria-labelledby="modalReservaLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="modalReservaLabel">Nova Reserva</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm" class="mb-3">
          @csrf

          <input type="hidden" id="sala_fk_hidden" name="sala_fk_hidden">

          <div class="row mb-3">
            <div class="col-6">
              <label for="sala_fk" class="fw-semibold">Sala:</label>
              <select name="sala_fk" id="sala_fk" class="form-select input-custom pointer" required>
                <option value="" disabled selected>Selecione uma sala</option>
                @foreach($salas as $sala)
                  <option value="{{ $sala->id }}">{{ $sala->nome }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-6">
              <label for="tipo_reserva" class="fw-semibold">Tipo de Reserva:</label>
              <select name="tipo_reserva" id="tipo_reserva" class="form-select pointer" required>
                <option value="" selected disabled>Selecione uma opção</option>
                <option value="interno">Uso interno</option>
                <option value="pesquisador">Pesquisador externo</option>
              </select>
            </div>
          </div>

          <!-- CAMPO VISÍVEL DE DATA -->
          <div class="row align-items-end mb-3">
            <div class="col-4">
              <label for="hora_inicio" class="fw-semibold">Hora de Início:</label>
              <input type="time" name="hora_inicio" id="hora_inicio" class="input-custom" required>
            </div>

            <div class="col-4">
              <label for="hora_termino" class="fw-semibold">Hora de Término:</label>
              <input type="time" name="hora_termino" id="hora_termino" class="input-custom" required>
            </div>

            <div class="col-4 d-flex align-items-center" style="margin-bottom: 20px;">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="diaInteiro">
                <label class="form-check-label fw-semibold" for="diaInteiro">Dia inteiro</label>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-8">
              <label for="data_reserva_modal" class="fw-semibold">Data da Reserva:</label>
              <input type="date" name="data_reserva" id="data_reserva_modal" class="input-custom w-100" required>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="button-grey" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i>
          Cancelar
        </button>

        <button type="submit" form="reservaForm" class="button-green">
          <i class="bi bi-save me-1"></i>
          Salvar Reserva
        </button>
      </div>
    </div>
  </div>
</div>

<!-- SCRIPT DE INTEGRAÇÃO -->
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const botoesAgendar = document.querySelectorAll('.btn-agendar');

    botoesAgendar.forEach(botao => {
      botao.addEventListener('click', () => {
        const salaId = botao.dataset.salaId;
        const salaNome = botao.dataset.salaNome;

        const selectSala = document.getElementById('sala_fk');
        const hiddenSala = document.getElementById('sala_fk_hidden');
        const inputData = document.getElementById('data_reserva_modal');
        const modalLabel = document.getElementById('modalReservaLabel');

        // Define sala selecionada
        if (selectSala) selectSala.value = salaId;
        if (hiddenSala) hiddenSala.value = salaId;

        // Atualiza título do modal
        if (modalLabel) modalLabel.textContent = `Nova Reserva - ${salaNome}`;

        // Limpa e foca campo de data
        if (inputData) {
          inputData.value = '';
          setTimeout(() => inputData.focus(), 300);
        }
      });
    });
  });
</script>