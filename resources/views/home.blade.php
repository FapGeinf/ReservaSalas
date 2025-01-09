

<style>
  section {
    width: 80%; /* Ocupar 80% da tela */
    margin: auto; /* Centralizar horizontalmente */
    min-height: 80vh; /* Garantir altura mínima */
    display: flex;
    flex-direction: column;
    justify-content: center; /* Centralizar verticalmente */
    padding: 2rem 0;
  }

  .custom-list {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* 4 colunas iguais */
    gap: 20px; /* Espaçamento entre as salas */
    list-style: none;
    padding: 0;
  }

  .custom-list li {
    background: #f8f9fa; /* Fundo claro */
    margin: 0.5rem 0;
    padding: 1rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    border: 1px solid #b7b7b7;
    box-shadow: 1px 1px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s, box-shadow 0.2s;
  }

  .custom-list li:hover {
    transform: translateY(-2px);
    box-shadow: 1px 1px 15px rgba(0, 0, 0, 0.2);
  }

  .custom-list li img {
    width: 100%; /* Tamanho da imagem */
    height:auto;
    object-fit: cover;
    border-radius: 5px; /* Imagem circular */
    margin-bottom: 10px;
    margin-right: 1rem;
  }

  .custom-list li .info {
    margin-bottom: 10px;
  }

  .custom-list .info h5 {
    margin: 0;
    font-size: 1.2rem;
    font-weight: bold;
  }

  .custom-list .info p {
    margin: 0;
    color: #6c757d;
  }

  .custom-list button {
    background: #007bff;
    color: white;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.2s;
  }

  .custom-list button:hover {
    background: #0056b3;
  }

  /* ***************************** */
  .table th, 
.table td {
  border-right: 1px solid #ddd; /* Linha vertical */
}

.table th:last-child, 
.table td:last-child {
  border-right: none; /* Remove a linha vertical da última coluna */
}

.table-title {
    font-size: 2rem; /* Ajuste o tamanho conforme necessário */
    font-weight: bold; /* Opcional: deixa o texto mais destacado */
  }


</style>

@extends('layouts.app')

@section('content')
<div class="container">
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
                    onclick="selecionarSala({{ $sala->id }})"
                >
                    Reservar
                </button>
            </div>
        </div>
    </div>
@endforeach
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="reservaForm" class="btn btn-primary">Salvar Reserva</button>
            </div>
        </div>
    </div>
</div>

    <!-- <div class="container mt-5">
        <h2 class="mt-5">Reservas</h2> -->
        <!-- <div class="row">
            <div class="col-md-8 offset-md-2">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Sala</th>
                            <th>Hora Início</th>
                            <th>Hora Término</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservas as $reserva)
                            <tr>
                                <td>{{ $reserva->sala->nome }}</td>
                                <td>{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y | H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y | H:i')}}</td>
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
        </div> -->
    <!-- </div> -->

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
</div>
<script>
    function selecionarSala(salaId) {
        document.getElementById('sala_fk').value = salaId;
    }
</script>


<table class="table align-middle mb-4 bg-white">
  <thead class="bg-light">
    <tr>
      <th colspan="4" class="text-center fs-4">Reservas</th>
    </tr>
  <tr class="text-center">
      <th>Sala</th>
      <th>Hora Início</th>
      <th>Hora Término</th>
      <th>Ações</th>
    </tr>
  </thead>
  <tbody>
    @foreach($reservas as $reserva)
      <tr>
      <td>
  <div class="d-flex align-items-center">
    <!-- Corrigindo o caminho da imagem -->
    <img
    src="{{ asset('img/salas/' . $reserva->sala->imagem) }}"

      alt=""
      style="width: 45px; height: 45px"
      class="rounded-circle"
    />
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

@endsection


