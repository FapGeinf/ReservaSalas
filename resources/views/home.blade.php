@extends('layouts.app')
@section('content')
@section('title') {{ 'Início' }} @endsection

<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/calendar-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/form-custom.css') }}">
{{-- <script src="{{ asset('js/tutorial.js') }}"></script> --}}

{{-- Não sei se esse botão é necessário --}}
<script src="{{ asset('js/toggleDropdown.js') }}"></script>

<script src="{{ asset('js/miniCalendar.js') }}"></script>
<script src="{{ asset('js/abrirModalEdicao.js') }}"></script>
<script src="{{ asset('js/abrirModalDetalhes.js') }}"></script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<style>
  .fc-timegrid-col-events {
    display: flex !important;
    flex-direction: column !important;
  }

  .fc-timegrid-event-harness {
    position: relative !important;
    width: 100% !important;
    left: 0 !important;
    right: 0 !important;
    transform: none !important;
    margin-bottom: 5rem !important;
  }

  .fc-timegrid-event-harness + .fc-timegrid-event-harness {
    margin-top: 4px !important;
  }

  /* Deixa a cor da hora branca, escondendo-a, pois a mesma não é necessária */
  .fc-timegrid-slot-label-cushion {
    color: #fff;
  }

  .swal2-icon {
    font-size: 8px !important;
  }

  .swal2-title {
    font-size: 16px;
  }

  .swal2-html-container {
    font-size: 14px;
  }
</style>

<script>
  const homeUrl = "{{ route('home') }}";
</script>


<x-alert-toast/>
<x-navbar-main :salas="$salas"/>

<div class="pagina-container">
  <div class="d-flex flex-wrap flex-lg-nowrap">
    <div class="col-lg-3 col-12 mb-3">
      <div class="salas-grid d-flex flex-lg-column gap-2 flex-column flex-md-row">
        <div class="ver-reservas-container border shadow-sm flex-grow-1 d-flex flex-column mt-3 mt-md-0"
          style="background-color: #fff; padding: 1rem;" data-help="pesquisa-reservas">

          <h5 class="fw-bold mb-3">Reservas</h5>

          <div class="mb-3">
            <label for="salaSelecionada" class="fw-bold">Selecione a Sala:</label>
            <select id="salaSelecionada" class="input-custom form-select w-100"></select>
          </div>

          <div class="mb-3">
            <label for="dataSelecionada" class="fw-bold">Selecione a Data:</label>
            <input type="date" id="dataSelecionada" class="input-custom w-100">
          </div>

          <div id="reservasContainer" class="reservas-container flex-grow-1 overflow-auto"
            style="max-height: 450px;">
            <p class="text-center text-muted">
              <i class="bi bi-arrow-repeat" style="color: #2a64e7;"></i> Carregando reservas...
            </p>
          </div>

        </div>
      </div>
    </div>

    <div class="col-xl-9 col-lg-8 col-md-12 px-lg-3">
      <div class="caixa-calendario" data-help="calendario-principal">
        <div class="area-calendario">
          <div id="calendar" class="calendar-container main-calendar mt-3" style="min-height: 650px;"></div>
        </div>

        <div class="mt-2">
          <span style="font-size: 14px; color: #374151;">
            <i class="bi bi-lightbulb-fill text-warning"></i>
            Clique em uma data para reservar uma sala ou visualizar agendamentos.
          </span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Reserva -->
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

          <input type="hidden" name="data_reserva" id="data_reserva">
          <input type="hidden" id="sala_fk_hidden">

          <div class="row mb-3">
            <div class="col-8">
              <label for="sala_fk" class="fw-semibold">Sala:</label>
              <select name="sala_fk" id="sala_fk" class="form-select input-custom pointer" required>
                <option value="" disabled selected>Selecione uma sala</option>

                @foreach($salas as $sala)
                  <option value="{{ $sala->id }}">{{ $sala->nome }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-8">
              <label for="tipo_reserva" class="fw-semibold">Tipo de Reserva:</label>
              <select name="tipo_reserva" id="tipo_reserva" class="form-select pointer" required>
                <option value="" selected disabled>Selecione uma opção</option>
                <option value="interno">Uso interno</option>
                <option value="pesquisador">Pesquisador externo</option>
              </select>
            </div>
          </div>

          <div class="row align-items-end">
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

<!-- Modal de Edição de Reserva -->
<div class="modal fade" id="modal-editar-reserva" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="modal-editar-reserva-label">Editar Reserva</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body pb-4">
        <form method="POST" id="form-editar-reserva">
          @csrf

          <input type="hidden" name="_method" value="PUT">

          <div class="text-center">
            <!-- Inserido d-none, remover em caso de bug -->
            <span id="reserva-numero" class="fw-semibold d-none" style="color: #374151;"></span>
          </div>

          <div class="row mb-4">
            <div class="col-7">
              <label for="sala_id" class="fw-semibold">Sala:</label>
              <select name="sala_id" id="sala_id" class="form-select pointer" required>
                <!-- opções preenchidas via JavaScript -->
              </select>
            </div>

            <div class="col-5">
              <label for="data_inicio" class="fw-semibold">Data:</label>
              <input type="date" name="data_inicio" id="data_inicio" class="input-custom pointer" required>
            </div>
          </div>

          <div class="row align-items-end">
            <div class="col-4">
              <label for="hora_inicio" class="fw-semibold">Hora Início:</label>
              <input type="time" name="hora_inicio" id="hora_inicio" class="input-custom pointer" step="60" required>
            </div>

            <div class="col-4">
              <label for="data_fim" class="fw-semibold">Hora Término:</label>
              <input type="time" name="data_fim" id="data_fim" class="input-custom pointer" step="60" required>
            </div>

            <div class="col-4 d-flex align-items-center" style="margin-bottom: 20px;">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="diaInteiro">
                <label class="form-check-label fw-semibold" for="diaInteiro">Dia inteiro</label>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="button-grey" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i>
          Cancelar
        </button>

        <button type="submit" form="form-editar-reserva" class="button-green">
          <i class="bi bi-save me-1"></i>
          Salvar Alterações
        </button>
      </div>
    </div>
  </div>
</div>
                   
<!-- Modal Detalhes da Reserva -->
<div class="modal fade" id="modalDetalhesReserva" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Detalhes da Reserva</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        <div class="row pb-3">
          <div class="col-5">
            <label class="fw-semibold">Sala:</label>
            <span id="detalheSala" class="input-custom-disabled"></span>
          </div>

          <div class="col-7">
            <label class="fw-semibold">Unidade:</label>
            <span id="detalheUnidade" class="input-custom-disabled"></span>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-5">
            <label class="fw-semibold">Horário:</label>
            <span id="detalheHorario" class="input-custom-disabled"></span>
          </div>

          <div class="col-7">
            <label class="fw-semibold">Responsável:</label>
            <span id="detalheResponsavel" class="input-custom-disabled"></span> 
          </div>
        </div>  
      </div>

      <div class="modal-footer">
        <button type="button" id="btnEditar" class="button-blue">
          <i class="bi bi-pencil-square fs-icon me-1"></i>
          Editar
        </button>

        <button type="button" id="btnExcluir" class="button-red">
          <i class="bi bi-trash fs-icon me-1"></i>
          Excluir
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-top">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body" style="font-size: 14px;">
        Tem certeza de que deseja excluir esta reserva? Essa ação não pode ser desfeita.
      </div>

      <div class="modal-footer">
        <button type="button" class="button-grey" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i>
          Cancelar
        </button>

        <form id="deleteForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="button-red">
            <i class="bi bi-trash fs-icon me-1"></i>
            Excluir
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>const salasDisponiveis = @json($salas);</script>
<script src="{{ asset('js/horaSelecionada.js') }}"></script>
<script src="{{ asset('js/diaInteiro.js') }}"></script>
<script src="{{ asset('js/setDeleteId.js') }}"></script>
<script src="{{ asset('js/abrirModalCalendario.js') }}"></script>
<script src="{{ asset('js/modalReservasFeitas.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
@endsection