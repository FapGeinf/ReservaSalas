<!-- resources/views/reservas/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Reserva</h1>
    <form action="{{ route('reservas.update', $reserva->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="data_reserva" class="form-label">Data</label>
            <input type="date" name="data_reserva" id="data_reserva" class="form-control" value="{{ $reserva->data_reserva }}" required>
        </div>

        <div class="mb-3">
            <label for="hora_inicio" class="form-label">Hora Início</label>
            <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" value="{{ $reserva->hora_inicio }}" required>
        </div>

        <div class="mb-3">
            <label for="hora_termino" class="form-label">Hora Término</label>
            <input type="time" name="hora_termino" id="hora_termino" class="form-control" value="{{ $reserva->hora_termino }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>
@endsection

