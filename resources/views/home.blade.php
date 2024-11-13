<!-- resources/views/home.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reservas de Salas</h1>
    
    @if($reservas->isEmpty())
        <p>Não há reservas de salas no momento.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sala</th>
                    <th>Usuário</th>
                    <th>Data</th>
                    <th>Hora</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservas as $reserva)
                    <tr>
                        <td>{{ $reserva->sala->nome }}</td>
                        <td>{{ $reserva->usuario->nome }}</td>
                        <td>{{ $reserva->data }}</td>
                        <td>{{ $reserva->hora }}</td>
                        <td>{{ $reserva->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
