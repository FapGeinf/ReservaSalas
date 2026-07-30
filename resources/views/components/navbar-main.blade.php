@php
    $horarios = [];
    for ($h = 8; $h <= 20; $h++) {
        for ($m = 0; $m < 60; $m += 30) {
            if ($h == 20 && $m == 30) continue;
            $horario = sprintf('%02d:%02d', $h, $m);
            $horarios[] = $horario;
        }
    }
@endphp

<head>
  <link rel="stylesheet" href="{{ asset('css/navbar-main.css') }}">
</head>

<div class="modal fade" id="modalReserva" tabindex="-1" aria-labelledby="modalReservaLabel" aria-hidden="true">
  <div class="modal-dialog">
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
            <div class="col-5">
              <label for="sala_fk" class="fw-medium">Sala:</label>
              <select name="sala_fk" id="sala_fk" class="form-select input-custom pointer" required>
                <option value="" disabled selected>Selecione uma sala</option>
                @foreach($salas as $sala)
                  <option value="{{ $sala->id }}">{{ $sala->nome }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-7">
              <label for="tipo_reserva" class="fw-medium">Tipo de Reserva:</label>
              <select name="tipo_reserva" id="tipo_reserva" class="form-select pointer" required>
                <option value="" selected disabled>Selecione uma opção</option>
                <option value="interno">Reunião Interna</option>
                <option value="pesquisador">Atendimento ao pesquisador</option>
              </select>
            </div>
          </div>

          {{-- @if(auth()->user()->is_admin)
            <div class="mb-3 row">
                <div class="col-12">
                    <label class="fw-medium">Unidade:</label>
                    <select name="unidade_fk" class="form-select pointer" required>
                        <option value="" disabled selected>Selecione a unidade</option>
                        @foreach($unidades as $unidade)
                            <option value="{{ $unidade->id }}">
                                {{ $unidade->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
          @endif --}}

          <div class="row align-items-end mb-3">
            <div class="col-6">
              <label for="hora_inicio" class="fw-medium">Hora de Início:</label>
              <select name="hora_inicio" id="hora_inicio" class="form-select input-custom pointer" required>
                <option value="" disabled selected>Selecione</option>
                @foreach($horarios as $horario)
                  <option value="{{ $horario }}">{{ $horario }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-6">
              <label for="hora_termino" class="fw-medium">Hora de Término:</label>
              <select name="hora_termino" id="hora_termino" class="form-select input-custom pointer" required>
                <option value="" disabled selected>Selecione</option>
                @foreach($horarios as $horario)
                  <option value="{{ $horario }}">{{ $horario }}</option>
                @endforeach
              </select>
            </div>

            {{-- <div class="col-4 d-flex align-items-center" style="margin-bottom: 20px;">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="diaInteiro">
                <label class="form-check-label fw-medium" for="diaInteiro">Dia inteiro</label>
              </div>
            </div> --}}
          </div>

          <div class="row">
            <div class="col-12">
              <label for="data_reserva_modal" class="fw-medium">Data da Reserva:</label>
              <input type="date" name="data_reserva" id="data_reserva_modal" class="input-custom w-100 form-select" 
              placeholder="00/00/0000" required>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer bg-modal-footer">
        <button type="button" class="button-grey" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i>
          Cancelar
        </button>

        <button type="submit" form="reservaForm" class="button-green">
          <i class="bi bi-check-circle me-1"></i>
          Salvar Reserva
        </button>
      </div>
    </div>
  </div>
</div>

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