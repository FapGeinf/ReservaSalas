<!-- resources/views/reservas/edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Reserva</h1>
    <form action="{{ route('reservas.update', $reserva->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="user_id">Usuário</label>
            <select name="user_id" class="form-control" required>
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ $reserva->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="sala_id">Sala</label>
            <select name="sala_id" class="form-control" required>
                @foreach($salas as $sala)
                <option value="{{ $sala->id }}" {{ $reserva->sala_id == $sala->id ? 'selected' : '' }}>{{ $sala->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="data_inicio">Data de Início</label>
            <input type="datetime-local" name="data_inicio" class="form-control" value="{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('Y-m-d\TH:i') }}" required>
        </div>
        <div class="form-group">
            <label for="data_fim">Data de Término</label>
            <input type="datetime-local" name="data_fim" class="form-control" value="{{ \Carbon\Carbon::parse($reserva->data_fim)->format('Y-m-d\TH:i') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>

@endsection
