@extends('layouts.app')

@section('content')
<div class="container">
   

    <!-- Botão para abrir o modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#criarReservaModal">
        Criar Reserva
    </button>
      

  

    <!-- Modal -->
    <div class="modal fade" id="criarReservaModal" tabindex="-1" aria-labelledby="criarReservaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="criarReservaModalLabel">Criar Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  
                <!-- Formulário -->
                    <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
                        @csrf

                        <div class="mb-3">
                            <label for="sala_fk" class="form-label">Sala</label>
                            <select name="sala_fk" id="sala_fk" class="form-control" required>
                                <option value="">Selecione uma Sala</option>
                                @foreach($salas as $sala)
                                    <option value="{{ $sala->id }}">{{ $sala->nome }}</option>
                                @endforeach
                            </select>
                        </div>

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
</div>

<div class="container mt-5">
    <h2 class="mt-5">Reservas</h2>
    <div class="row">
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
@endsection



<tr>
      <td>
        <div class="d-flex align-items-center">
          <img
              src="{{ asset('img/auditorio.png' . $sala->imagem) }}"
              alt=""
              style="width: 45px; height: 45px"
              class="rounded-circle"
              />
          <div class="ms-3">
            <p class="fw-bold mb-1">Aquário</p>
            <p class="text-muted mb-0">john.doe@gmail.com</p>
          </div>
        </div>
      </td>
      <td>
        <p class="fw-normal mb-1">Software engineer</p>
        <p class="text-muted mb-0">IT department</p>
      </td>
      <td>
        <span class="badge badge-success rounded-pill d-inline">Active</span>
      </td>
      <td>Senior</td>
      <td>
        <button type="button" class="btn btn-link btn-sm btn-rounded">
          Edit
        </button>
      </td>
    </tr>