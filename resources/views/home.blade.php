@extends('layouts.app')
@section('title') {{ 'Início' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/calendar-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/form-custom.css') }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<style>
  .fc-dayGridMonth-view { background-color: #F3F7F2; }
  .fs-11 { font-size: 11px; }
  .fs-13 { font-size: 13px; }
  .fs-14 { font-size: 14px; }
  .input-custom-disabled { display: block; padding: 0.5rem; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; min-height: 38px; }
</style>

<script src="{{ asset('js/scripts/jquery-3.7.1.min.js') }}"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>

<script>
  const homeUrl = "{{ route('home') }}";
  const salasDisponiveis = @json($salas);
</script>

<x-alert-toast/>
<x-navbar-main :salas="$salas"/>
<x-sidebar :salas="$salas" :reservas="$reservas" />

<div class="pagina-container container">
  <div class="row">
    <div class="col-12 col-lg-4 mb-3">
      <div class="ver-reservas-container border rounded shadow-sm d-flex flex-column mb-3" style="background-color: #fff; padding: 1rem 1rem 1.5rem 1rem;">
        <div class="d-flex align-items-center justify-content-center mb-1">
          <x-tooltip/>
          <h6 class="fw-semibold mb-0 ms-2">Reservar Salas</h6>
        </div>
        <div class="text-muted text-center mb-3 fs-13">Selecione a sala desejada para realizar a reserva.</div>
        <div class="d-flex flex-wrap align-items-center justify-content-center gap-2">
          @foreach($salas as $sala)
            @php
              $situacao = strtolower(trim($sala->situacao));
              $statusColor = $situacao === 'inativa' ? 'bg-danger' : 'bg-success';
            @endphp
            <div class="d-flex align-items-center justify-content-between px-2 py-1 border rounded shadow-sm w-100">
              <div class="d-flex flex-column bg-light p-2 rounded">
                <div class="d-flex align-items-center gap-1 mb-1">
                  <i class="bi bi-building text-secondary fs-14"></i>
                  <span class="rounded-circle {{ $statusColor }}" style="width: 7px; height: 7px; display: inline-block;"></span>
                  <span class="fw-semibold text-capitalize fs-13" style="color: #374151">{{ $sala->nome }}</span>
                </div>
              </div>
              @if($situacao !== 'inativa')
                @if(strtolower(trim($sala->nome)) === 'presidência' || strtolower(trim($sala->nome)) === 'presidencia')
                  <button type="button" class="button-garden btn-agendar fs-13 px-2 py-1 ms-2"
                          data-bs-toggle="modal" data-bs-target="#modalAvisoPresidencia">
                    <i class="bi bi-calendar-plus me-1 fs-11"></i> Reservar
                  </button>
                @else
                  <button type="button" class="button-garden btn-agendar fs-13 px-2 py-1 ms-2"
                          data-bs-toggle="modal" data-bs-target="#modalReserva"
                          data-sala-id="{{ $sala->id }}" data-sala-nome="{{ $sala->nome }}">
                    <i class="bi bi-calendar-plus me-1 fs-11"></i> Reservar
                  </button>
                @endif
              @else
                <x-tool-tip-rooms/>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="col-12 col-lg-8 px-lg-3">
      <div class="caixa-calendario">
        <div class="area-calendario rounded my-3">
          <div id="calendar" class="calendar-container main-calendar"></div>
        </div>
      </div>
    </div>
  </div>
</div>


<div class="modal fade" id="modalReserva" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Nova Reserva</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
          @csrf
          <input type="hidden" name="data_reserva" id="data_reserva">
          <div class="mb-3">
            <label class="fw-medium">Sala:</label>
            <select name="sala_fk" id="sala_fk" class="form-select pointer" required>
              <option value="" disabled selected>Selecione uma sala</option>
              @foreach($salas as $sala)
                <option value="{{ $sala->id }}">{{ $sala->nome }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="fw-medium">Tipo de Reserva:</label>
            <select name="tipo_reserva" id="tipo_reserva" class="form-select pointer" required>
              <option value="" selected disabled>Selecione uma opção</option>
              <option value="interno">Reunião interna</option>
              <option value="pesquisador">Atendimento ao pesquisador</option>
            </select>
          </div>
          {{-- @if(auth()->user()->isAdmin())
          <div class="mb-3">
            <label class="fw-medium">Unidade:</label>
            <select name="unidade_fk" class="form-select pointer" required>
              <option value="" disabled selected>Selecione a unidade</option>
              @foreach($unidades as $unidade)
                <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
              @endforeach
            </select>
          </div>
          @endif --}}

          <div class="row g-2">
            <div class="col-6"><label class="fw-medium">Início:</label><input type="time" name="hora_inicio" id="hora_inicio" class="input-enabled" required></div>
            <div class="col-6"><label class="fw-medium">Término:</label><input type="time" name="hora_termino" id="hora_termino" class="input-enabled" required></div>
          </div>

          {{-- <div class="mt-3">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="diaInteiro">
              <label class="form-check-label fw-medium" for="diaInteiro">Dia inteiro</label>
            </div>
          </div> --}}
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="button-grey" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="reservaForm" class="button-green">Salvar Reserva</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalDetalhesReserva" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h6>Detalhes da Reserva</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-6"><label class="fw-medium">Sala:</label><span id="detalheSala" class="input-custom-disabled"></span></div>
          <div class="col-6"><label class="fw-medium">Data:</label><span id="detalheData" class="input-custom-disabled"></span></div>
          <div class="col-6"><label class="fw-medium">Início:</label><span id="detalheHoraInicio" class="input-custom-disabled"></span></div>
          <div class="col-6"><label class="fw-medium">Fim:</label><span id="detalheHoraFim" class="input-custom-disabled"></span></div>
          <div class="col-12"><label class="fw-medium">Unidade:</label><span id="detalheUnidade" class="input-custom-disabled"></span></div>
          <div class="col-12"><label class="fw-medium">Responsável:</label><span id="detalheResponsavel" class="input-custom-disabled"></span></div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="button-green" data-bs-toggle="modal" data-bs-target="#modalEncerramento"><i class="bi bi-check-circle me-1"></i> Finalizar</button>
        @if(Auth::user()->isAdmin())
          <button type="button" id="btnEditar" class="button-blue"><i class="bi bi-pen me-1"></i>Editar</button>
          <button type="button" id="btnExcluir" class="button-red"><i class="bi bi-trash me-1"></i>Excluir</button>
        @endif
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-editar-reserva" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h6>Editar Reserva</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <form method="POST" id="form-editar-reserva">
          @csrf @method('PUT')
          <input type="hidden" name="data_reserva" id="data_reserva_edit">
          <div class="row mb-3">
            <div class="col-6"><label>Sala:</label><select name="sala_fk" id="sala_fk_edit" class="form-select">@foreach($salas as $sala)<option value="{{ $sala->id }}">{{ $sala->nome }}</option>@endforeach</select></div>
            @if(auth()->user()->isAdmin())<div class="col-6"><label>Unidade:</label><select name="unidade_fk" id="unidade_fk_edit" class="form-select">@foreach($unidades as $u)<option value="{{ $u->id }}">{{ $u->nome }}</option>@endforeach</select></div>@endif
          </div>

          <div class="mb-3"><label>Tipo:</label><select name="tipo_reserva" id="tipo_reserva_edit" class="form-select"><option value="interno">Reunião interna</option><option value="pesquisador">Atendimento ao pesquisador</option></select></div>
          <div class="row g-2">
            <div class="col-4"><label>Data:</label><input type="date" id="data_visual_edit" class="form-select"></div>
            <div class="col-4"><label>Início:</label><input type="text" name="hora_inicio" id="hora_inicio_edit" class="form-select time-picker"></div>
            <div class="col-4"><label>Fim:</label><input type="text" name="hora_termino" id="hora_termino_edit" class="form-select time-picker"></div>
          </div>
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="button-grey" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Cancelar</button>
        <button type="submit" form="form-editar-reserva" class="button-green"><i class="bi bi-check-circle me-1"></i>Salvar Alterações</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalEncerramento" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h6>Confirmar Encerramento</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <p class="mb-0 fs-14">Tem certeza que deseja encerrar esta reunião agora?</p>
        <p class="text-muted small mt-2">O horário de término será registrado como o momento atual.</p>
      </div>

      <div class="modal-footer">
        <button type="button" class="button-grey" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Cancelar</button>
        <form id="formEncerrar" method="POST" style="display: inline;">
          @csrf @method('PUT')
          <button type="submit" class="button-green"><i class="bi bi-check-circle me-1"></i>Sim, encerrar</button>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalConfirmarExclusao" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h6>Confirmar Exclusão</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body"><p class="fs-14 mb-0">Tem certeza de que deseja excluir esta reserva?</p></div>
      <div class="modal-footer">
        <button type="button" class="button-grey" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Cancelar</button>
        <button type="button" id="btnConfirmarExclusao" class="button-red"><i class="bi bi-trash me-1"></i>Excluir</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalErroDataPassada" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h6>Data inválida</h6><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body text-center"><p class="fs-14">Não é possível agendar reserva em uma data anterior à atual.</p></div>
      <div class="modal-footer"><button type="button" class="button-grey" data-bs-dismiss="modal"><i class="bi bi-x-lg me-1"></i>Fechar</button></div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAvisoPresidencia" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h6 class="modal-title"><i class="bi bi-info-circle me-2"></i>Aviso: Sala da Presidência</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center py-4">
        <h5 class="fw-semibold mb-3">Reserva Restrita</h5>
        <p class="fs-14 mb-0">A reserva desta sala é feita exclusivamente entrando em contato com o <span class="fw-bold" style="color: #374151;">GABINETE DA PRESIDÊNCIA</span>.</p>
        <p class="fs-14 mt-2">Ramal: <span class="fw-bold" style="color: #374151">4013</span></p>
      </div>
      <div class="modal-footer justify-content-center bg-modal-footer">
        <button type="button" class="button-grey px-4" data-bs-dismiss="modal">
          {{-- <i class="bi bi-x-lg me-1"></i> --}}
          Entendido
        </button>
      </div>
    </div>
  </div>
</div>

<form id="deleteForm" method="POST" style="display:none;">@csrf @method('DELETE')</form>

<script src="{{ asset('js/toggleDropdown.js') }}"></script>
<script src="{{ asset('js/miniCalendar.js') }}"></script>
<script src="{{ asset('js/abrirModalEdicao.js') }}"></script>
<script src="{{ asset('js/abrirModalDetalhes.js') }}"></script>
<script src="{{ asset('js/horaSelecionada.js') }}"></script>
<script src="{{ asset('js/diaInteiro.js') }}"></script>
<script src="{{ asset('js/setDeleteId.js') }}"></script>
<script src="{{ asset('js/abrirModalCalendario.js') }}"></script>
<script src="{{ asset('js/modalReservasFeitas.js') }}"></script>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const selectSala = document.getElementById('sala_fk');
    const modalAviso = new bootstrap.Modal(document.getElementById('modalAvisoPresidencia'));

    if (selectSala) {
      selectSala.addEventListener('change', function () {
        const opcaoSelecionada = this.options[this.selectedIndex];
        const nomeSala = opcaoSelecionada.text.trim().toLowerCase();

        if (nomeSala === 'presidencia' || nomeSala === 'presidência') {
          const modalReserva = bootstrap.Modal.getInstance(document.getElementById('modalReserva'));
          if(modalReserva) {
            modalReserva.hide();
          }

          modalAviso.show();
          this.value = '';
        }
      });
    }
  });
</script>
@endpush

@endsection
