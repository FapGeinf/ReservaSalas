@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<div class="padding__left4">
  <div class="p-30 mx-auto" style="width: 80%">
    <div class="text-center mb-3">
      <h2 class="fw-bold">Salas</h2>
    </div>

    @if (session('error'))
      <div class="alert alert-danger">
        {{ session('error') }}
      </div>
    @endif

    @if (session('success'))
      <div class="alert alert-success">
        {{ session('success') }}
      </div>
    @endif

    <div class="row">
      @foreach($salas as $index => $sala)

      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card border">

          <div class="bg__card_pattern bg__card_pattern_footer text-light text-center mb-0">
            <img class="fit-image" src="{{ asset('img/auditorio.png') }}" alt="">
          </div>

          <div class="card-body card-fofinho">
            <div class="title-teste text-center d-flex flex-column">
              <span>Local</span>
              <h3 class="fw-bold text-uppercase">{{ $sala->nome }}</h3>
              <span class="mt-3" style="color: #969696; font-size: 14px;">Lorem ipsum dolor sit amet consectetur adipisicing elit.</span>
            </div>
          </div>

          <div class="mx-auto py-3">
            <button 
              type="button" 
              class="button-68" 
              data-bs-toggle="modal" 
              data-bs-target="#criarReservaModal" 
              onclick="selecionarSala({{ $sala->id }})">
              Reservar
            </button>
          </div>

        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

<div class="form-wrapper p-30 pt-3 mx-auto" style="width: 80.5%">
  <div class="text-center mb-3">
    <h2 class="fw-bold">Reservas</h2>
  </div>

  <div class="custom__form_create">
    <div class="table-responsive">
      <table id="reservasTable" class="table table-bordered table-rounded align-middle mb-4 bg-white" style="border-collapse: collapse; border: 1px solid #d3d3d3; background-color: #f5f5f5;">
      <thead class="">
         {{-- <tr>
            <th colspan="6" class="text-center fs-4">Reservas</th>
         </tr> --}}
         <tr class="text-center">
            <th class="th__title">Sala</th>
            <th class="th__title">Hora Início</th>
            <th class="th__title">Hora Término</th>
            <th class="th__title">Reservado Por</th>
            <th class="th__title">Unidade</th>
            <th class="th__title">Ações</th>
         </tr>
      </thead>
   
    <tbody>
      @foreach($reservas as $reserva)
        <tr>
          <td>
            <div class="d-flex align-items-center">
              <img src="{{ asset('img/salas/' . $reserva->sala->imagem) }}" alt="" style="width: 45px; height: 45px" class="rounded-circle" />
              <div class="ms-3">
                <p class="fw-bold mb-1">{{ $reserva->sala->nome }}</p>
              </div>
            </div>
          </td>

          <td>
            <p class="fw-normal mb-1">{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y | H:i') }}</p>
          </td>

          <td>
            <p class="fw-normal mb-1">{{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y | H:i') }}</p>
          </td>

          <td>
            <p class="fw-normal mb-1">{{ $reserva->user ? $reserva->user->name : '' }}</p>
          </td>

          <td>
            <p class="fw-normal mb-1">{{ $reserva->user && $reserva->user->unidade ? $reserva->user->unidade->nome : '' }}</p>
          </td>

          <td class="text-center">
            <a href="{{ route('reservas.show', $reserva->id) }}" class="button-all button-bg-blue">Detalhes</a>
            <a href="{{ route('reservas.edit', $reserva->id) }}" class="button-all button-bg-yellow">Editar</a>
            <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="button-all button-bg-red">Cancelar</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
   </table>
    </div>
  </div>
</div>

  <!-- Modal -->
<div class="modal fade" id="criarReservaModal" tabindex="-1" aria-labelledby="criarReservaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="criarReservaModalLabel">Criar Reserva</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
          @csrf
          <input type="hidden" name="sala_fk" id="sala_fk">

          <div class="mb-3">
            <label for="data_reserva" class="form-label">Data</label>
            <input type="date" name="data_reserva" id="data_reserva" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="hora_inicio" class="form-label">Hora Início</label>
            <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="hora_termino" class="form-label">Hora Término</label>
            <input type="time" name="hora_termino" id="hora_termino" class="form-control" required>
          </div>

      <!-- Campo oculto para unidade_fk -->
       {{-- <input type="hidden" name="unidade_fk" id="unidade_fk" value="{{ auth()->user()->unidade_id }}"> --}}

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="reservaForm" class="btn btn-primary">Salvar Reserva</button>
      </div>
    </div>
  </div>
</div>

<script>
  function selecionarSala(salaId) {
    document.getElementById('sala_fk').value = salaId;
  }
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#reservasTable').DataTable({
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
        search: "Procurar:",
        lengthMenu: "Paginação: _MENU_",
        info: 'Mostrando página _PAGE_ de _PAGES_',
        infoEmpty: 'Sem relatórios de risco disponíveis no momento',
        infoFiltered: '(Filtrados do total de _MAX_ relatórios)',
        zeroRecords: 'Nada encontrado. Se achar que isso é um erro, contate o suporte.',
        paginate: {
          next: "Próximo",
          previous: "Anterior"
        }
      }
    });
  });
</script>

@endsection