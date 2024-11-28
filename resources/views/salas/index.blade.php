@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Lista de Salas</h1>
    
    @if($salas->isEmpty())
        <p>Não há salas cadastradas no momento.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Situação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salas as $sala)
                    <tr>
                        <td>{{ $sala->nome }}</td>
                        <td>{{ $sala->descricao }}</td>
                        <td>{{ $sala->situacao }}</td>
                        <td>
                            <a href="{{ route('salas.edit', $sala) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('salas.destroy', $sala) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
