@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Criar Reserva</h1>

    <!-- Formulário -->
    <form action="{{ route('reservas.store') }}" method="POST">
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

        <button type="submit" class="btn btn-primary">Salvar Reserva</button>
    </form>

    <!-- Exibindo Reservas -->
    <h2 class="mt-5">Reservas</h2>
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
@endsection
