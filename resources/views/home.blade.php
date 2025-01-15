@extends('layouts.app')

@section('content')

<head>
  <link rel="stylesheet" href="{{ asset('css/show.css') }}">
</head>

<div class="container-fluid p-30">
  <div class="text-center mb-4">
    <h2 class="fw-bold">SALAS DE REUNIÃO</h2>
  </div>
    
  <div class="row">
    @foreach($salas as $index => $sala)
      <div class="col-md-3 mb-4">
        <div class="card">
          @php
            // Lista fixa de 4 imagens
            $imagens = [
              'img/salas/sala1.jpg',
              'img/salas/sala2.jpg',
              'img/salas/sala3.jpg',
              'img/salas/sala4.png',
            ];
            // Rotação das imagens baseado no índice
            $imagem = $imagens[$index % count($imagens)];
          @endphp

          <img src="{{ asset($imagem) }}" class="card-img-top" alt="Imagem da {{ $sala->nome }}">

          <div class="card-body text-center">
            <h5 class="card-title">{{ $sala->nome }}</h5>
            <p class="card-text">{{ $sala->descricao }}</p>
            <button 
                type="button" 
                class="btn btn-primary" 
                data-bs-toggle="modal" 
                data-bs-target="#criarReservaModal" 
                onclick="selecionarSala({{ $sala->id }})">
                Reservar
            </button>
          </div>
        </div>
      </div>
    @endforeach

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
       <input type="hidden" name="unidade_fk" id="unidade_fk" value="{{ auth()->user()->unidade_id }}">

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="reservaForm" class="btn btn-primary">Salvar Reserva</button>
      </div>
    </div>
  </div>
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

  <table class="table table-bordered align-middle mb-4 bg-white" style="border-collapse: collapse; border: 1px solid #d3d3d3; background-color: #f5f5f5;">
   <thead class="bg-light">
      <tr>
         <th colspan="6" class="text-center fs-4">Reservas</th>
      </tr>
      <tr class="text-center" style="">
         <th>Sala</th>
         <th>Hora Início</th>
         <th>Hora Término</th>
         <th>Reservado Por</th>
         <th>Unidade</th>
         <th>Ações</th>
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
            <td>
                <a href="{{ route('reservas.show', $reserva->id) }}" class="btn btn-info btn-sm">Detalhes</a>
                <a href="{{ route('reservas.edit', $reserva->id) }}" class="btn btn-warning btn-sm">Editar</a>
                <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                </form>
            </td>
        </tr>
    @endforeach
</tbody>





</table>
</div>

<script>
  function selecionarSala(salaId) {
    document.getElementById('sala_fk').value = salaId;
  }
</script>

@endsection