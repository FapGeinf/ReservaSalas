@extends('layouts.app') 

@section('content')
<div class="container">
    <h1>Nova Reserva</h1>
    <form action="{{ route('reservas.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user_id">Usuário</label>
            <select name="user_id" class="form-control" required>
                @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="sala_id">Sala</label>
            <select name="sala_id" class="form-control" required>
                @foreach($salas as $sala)
                <option value="{{ $sala->id }}">{{ $sala->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="data_reserva">Data da Reserva</label>
            <input type="datetime-local" name="data_reserva" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
    </form>
    
</div>
@endsection
