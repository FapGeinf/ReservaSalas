@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Sala</h1>
    <form action="{{ route('salas.update', $sala) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" value="{{ $sala->nome }}" required>
        </div>
        <div class="form-group">
            <label for="descricao">Descrição/Localização</label>
            <input type="text" name="descricao" id="descricao" class="form-control" value="{{ $sala->descricao }}" required>
        </div>
        <div class="form-group">
            <label for="situacao">Situação</label>
            <select name="situacao" id="situacao" class="form-control" required>
                <option value="ativa" {{ $sala->situacao === 'ativa' ? 'selected' : '' }}>Ativa</option>
                <option value="inativa" {{ $sala->situacao === 'inativa' ? 'selected' : '' }}>Inativa</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
    </form>
</div>
@endsection
