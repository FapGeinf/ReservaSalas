@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Reserva</h1>
    <form action="{{ route('reservas.update', $reserva->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="sala_id" class="form-label">Sala</label>
            <select name="sala_id" id="sala_id" class="form-control" required>
                @foreach($salas as $sala)
                    <option value="{{ $sala->id }}" @if($sala->id == $reserva->sala_fk) selected @endif>{{ $sala->nome }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="data_inicio" class="form-label">Data</label>
            <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label for="hora_inicio" class="form-label">Hora Início</label>
            <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" value="{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i') }}" required>
        </div>

        <div class="mb-3">
            <label for="data_fim" class="form-label">Hora Término</label>
            <input type="time" name="data_fim" id="data_fim" class="form-control" value="{{ \Carbon\Carbon::parse($reserva->data_fim)->format('H:i') }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>
@endsection
