@extends('layouts.app')

@section('content')
<div class="container">

   <header>
    <h2>Agendaí</h2>
   </header>
   <section>
  <nav>
    <ul>
      <li><a href="#">Salas de Reunião</a></li>
      <li><a href="#">Reservas de Salas</a></li>
      <li><a href="#">Configuração</a></li>
    </ul>
  </nav>

    <h1>Reservas</h1>
    <a href="{{ route('reservas.create') }}" class="btn btn-primary">Nova Reserva</a>
    <!-- <table class="table">
        <thead>
            <tr>
                <th>Usuário</th>
                <th>Sala</th>
                <th>Data</th>
                <th>Ações</th>

            </tr>
        </thead> -->
        <tbody>
            @foreach ($reservas as $reserva)
            <tr>
                <td>{{ $reserva->id }}</td>
                <td>{{ $reserva->user->name }}</td>
                <td>{{ $reserva->sala->nome }}</td>
                <td>{{ $reserva->data_reserva }}</td>
                <td>
                    <a href="{{ route('reservas.edit', $reserva) }}" class="btn btn-warning">Editar</a>
                    <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Excluir</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
