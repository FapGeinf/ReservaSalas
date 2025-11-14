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
<script> const homeUrl = "{{ route('home') }}"; </script>

<x-alert-toast/>
<x-navbar-main :salas="$salas"/>
<x-sidebar/>

<div class="pagina-container container">
  <div class="row">
    <div class="col-12 col-lg-4 mb-3">

      <div class="ver-reservas-container border rounded shadow-sm d-flex flex-column mb-3"
        style="background-color: #fff; padding: 1rem 1rem 1.5rem 1rem;">

        <div class="d-flex align-items-center justify-content-center mb-1">
          <x-tooltip/>
          <h6 class="fw-bold mb-0 ms-2">Reservar Salas</h6>
        </div>

        <div class="text-muted text-center mb-3" style="font-size: 13px;">
          Selecione a sala desejada para realizar a reserva.
        </div>        

        <div class="d-flex flex-wrap align-items-center justify-content-center gap-2">
          @foreach($salas as $sala)
            @php
              $situacao = strtolower(trim($sala->situacao));
              $statusColor = $situacao === 'inativa' ? 'bg-danger' : 'bg-success';
            @endphp

            <div class="d-flex align-items-center justify-content-between px-2 py-1 border rounded shadow-sm w-100">
              <div class="d-flex flex-column bg-light p-2 rounded">
                <div class="d-flex align-items-center gap-1 mb-1">
                  <i class="bi bi-building text-secondary" style="font-size: 14px;"></i>
                  <span class="rounded-circle {{ $statusColor }} status-ball"
                    style="width: 7px; height: 7px; display: inline-block;">
                  </span>

                  <span class="fw-semibold text-uppercase fs-12">
                    {{ $sala->nome }}
                  </span>
                </div>
              </div>

              @if($situacao !== 'inativa')
                <button 
                  type="button"
                  class="button-blue btn-agendar fs-12 px-2 py-1 ms-2" 
                  data-bs-toggle="modal" 
                  data-bs-target="#modalReserva"
                  data-sala-id="{{ $sala->id }}"
                  data-sala-nome="{{ $sala->nome }}">
                  <i class="bi bi-calendar-plus me-1 fs-11"></i>
                  Reservar
                </button>
              @else
                <x-tool-tip-rooms/>
              @endif

            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-8 px-lg-3">
      <div class="caixa-calendario" data-help="calendario-principal">
        <div class="area-calendario border-bottom rounded my-3">
          <div id="calendar" class="calendar-container main-calendar"></div>
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
            <div class="col-12 col-sm-8">
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
            <div class="col-12 col-sm-8">
              <label for="tipo_reserva" class="fw-semibold">Tipo de Reserva:</label>
              <select name="tipo_reserva" id="tipo_reserva" class="form-select pointer" required>
                <option value="" selected disabled>Selecione uma opção</option>
                <option value="interno">Uso interno</option>
                <option value="pesquisador">Pesquisador externo</option>
              </select>
            </div>
          </div>

          <div class="row align-items-end">
            <div class="col-12 col-sm-4">
              <label for="hora_inicio" class="fw-semibold">Hora de Início:</label>
              <input type="time" name="hora_inicio" id="hora_inicio" class="input-custom" required>
            </div>

            <div class="col-12 col-sm-4">
              <label for="hora_termino" class="fw-semibold">Hora de Término:</label>
              <input type="time" name="hora_termino" id="hora_termino" class="input-custom" required>
            </div>

            <div class="col-12 col-sm-4 d-flex align-items-center" style="margin-bottom: 20px;">
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
            <div class="col-12 col-sm-7">
              <label for="sala_id" class="fw-semibold">Sala:</label>
              <select name="sala_id" id="sala_id" class="form-select pointer" required>
                <!-- opções preenchidas via JavaScript -->
              </select>
            </div>

            <div class="col-12 col-sm-5">
              <label for="data_inicio" class="fw-semibold">Data:</label>
              <input type="date" name="data_inicio" id="data_inicio" class="input-custom pointer" required>
            </div>
          </div>

          <div class="row align-items-end">
            <div class="col-12 col-sm-4">
              <label for="hora_inicio" class="fw-semibold">Hora Início:</label>
              <input type="time" name="hora_inicio" id="hora_inicio" class="input-custom pointer" step="60" required>
            </div>

            <div class="col-12 col-sm-4">
              <label for="data_fim" class="fw-semibold">Hora Término:</label>
              <input type="time" name="data_fim" id="data_fim" class="input-custom pointer" step="60" required>
            </div>

            <div class="col-12 col-sm-4 d-flex align-items-center" style="margin-bottom: 20px;">
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
        <div class="row g-3">
          <div class="col-12 col-sm-6">
            <label class="fw-semibold d-block">Sala:</label>
            <span id="detalheSala" class="input-custom-disabled"></span>
          </div>
      
          <div class="col-12 col-sm-4">
            <label class="fw-semibold d-block">Data da reserva:</label>
            <span id="detalheData" class="input-custom-disabled"></span>
          </div>
        </div>

        <div class="row g-3 mt-1 mb-3">
          <div class="col-12 col-sm-4">
            <label class="fw-bold d-block">Hora início:</label>
            <span id="detalheHoraInicio" class="input-custom-disabled"></span>
          </div>

          <div class="col-12 col-sm-4">
            <label class="fw-bold d-block">Hora término:</label>
            <span id="detalheHoraFim" class="input-custom-disabled"></span>
          </div>
        </div>

        <div class="row g-3">
          <div class="col-12 col-sm-8">
            <label class="fw-semibold">Unidade responsável:</label>
            <span id="detalheUnidade" class="input-custom-disabled"></span>
          </div>
        </div>

        <div class="row g-3 mt-1">
          <div class="col-12 col-sm-7">
            <label class="fw-semibold">Pessoa responsável:</label>
            <span id="detalheResponsavel" class="input-custom-disabled"></span> 
          </div>
        </div>
      </div>

      <div class="modal-footer">
        @if(Auth::check() && in_array(Auth::user()->unidade_fk, [12, 14]))
          <button type="button" id="btnEditar" class="button-blue">
            <i class="bi bi-pencil-square fs-icon me-1"></i>
            Editar
          </button>

          <button type="button" id="btnExcluir" class="button-red">
            <i class="bi bi-trash fs-icon me-1"></i>
            Excluir
          </button>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Container onde serão listadas as reservas -->
<div id="reservasContainer"></div>

<!-- Modal Detalhes de Reservas Feitas do bloco da esquerda -->
<div class="modal fade" id="modalReservaUnica" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Detalhes da Reserva</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12 col-sm-6">
            <label for="" class="fw-semibold">Sala:</label>
            <span id="reservaSala" class="input-custom-disabled"></span>
          </div>

          <div class="col-12 col-sm-4">
            <label for="" class="fw-semibold">Data da reserva:</label>
            <span id="reservaData" class="input-custom-disabled"></span>
          </div>
        </div>

        <div class="row g-3 mt-1">
          <div class="col-12 col-sm-4">
            <label for="" class="fw-semibold">Hora início:</label>
            <span id="reservaHoraInicio" class="input-custom-disabled"></span>
          </div>

          <div class="col-12 col-sm-4">
            <label for="" class="fw-semibold">Hora término:</label>
            <span id="reservaHoraFim" class="input-custom-disabled"></span>
          </div>
        </div>

        <div class="row g-3 mt-1">
          <div class="col-12 col-sm-8">
            <label for="" class="fw-semibold">Unidade responsável:</label>
            <span id="reservaUnidade" class="input-custom-disabled"></span>
          </div>
        </div>

        <div class="row g-3 mt-1">
          <div class="col-12 col-sm-7">
            <label for="" class="fw-semibold">Pessoa responsável:</label>
            <span id="reservaResponsavel" class="input-custom-disabled"></span>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        @if(Auth::check() && in_array(Auth::user()->unidade_fk, [12, 14]))
          <button type="button" id="btnEditarReservaUnica" class="button-blue">
            <i class="bi bi-pencil-square fs-icon me-1"></i>
            Editar
          </button>
        
          <button type="button" id="btnExcluirReservaUnica" class="button-red">
            <i class="bi bi-trash fs-icon me-1"></i>
            Excluir
          </button>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Formulário para exclusão (pode estar oculto) -->
<form id="deleteForm" method="POST" style="display:none;">
  @csrf
  @method('DELETE')
</form>

<!-- Modal de Confirmação de Exclusão -->
<div class="modal fade" id="modalConfirmarExclusao" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Confirmar Exclusão</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        <p class="mb-0 fs-14">Tem certeza de que deseja excluir esta reserva? Essa ação não poderá ser desfeita.</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="button-grey" data-bs-dismiss="modal">
          <i class="bi bi-x me-1"></i>Cancelar
        </button>

        <button type="button" id="btnConfirmarExclusao" class="button-red">
          <i class="bi bi-trash me-1"></i>Excluir
        </button>
      </div>
    </div>
  </div>
</div>


<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body fs-14">
        <p>Tem certeza de que deseja excluir esta reserva? Essa ação não pode ser desfeita.</p>
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

<!-- Modal de Erro - Data Passada -->
<div class="modal fade" id="modalErroDataPassada" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Data inválida</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body text-center pb-0 fs-14">
        <p>Não é possível agendar reserva em uma data anterior à atual.</p>
      </div>

      <div class="modal-footer py-2">
        <button type="button" class="button-grey" data-bs-dismiss="modal">
          <i class="bi bi-x me-1"></i>
          Fechar
        </button>
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