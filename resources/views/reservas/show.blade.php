@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalhes da Reserva</h1>

    <div class="card">
        <div class="card-header">
            Reserva {{ $reserva->id }}
        </div>
        <div class="card-body">
            <p><strong>Usuário:</strong> {{ $reserva->usuario->name ?? 'Usuário não encontrado' }}</p>
            <p><strong>Unidade:</strong> {{ $reserva->usuario->unidade->nome ?? 'Unidade não encontrada' }}</p>
            <p><strong>Sala:</strong> {{ $reserva->sala->nome ?? 'Sala não encontrada' }}</p>
            <p><strong>Data da Reserva:</strong> {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y') }}</p>
            <p><strong>Hora Início:</strong> {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i') }}</p>
            <p><strong>Hora Término:</strong> {{ \Carbon\Carbon::parse($reserva->data_fim)->format('H:i') }}</p>
        </div>
    </div>
</div>
@endsection
