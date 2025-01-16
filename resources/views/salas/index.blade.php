@extends('layouts.app')

@section('content')
<div class="container">
    <a href="#" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#cadastrarSalaModal">Cadastrar Nova Sala</a>
    
    <h1 class="text-center">Lista de Salas</h1>

    @if($salas->isEmpty())
        <p>Não há salas cadastradas no momento.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Sala</th>
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
                            <a href="#" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#editarSalaModal{{ $sala->id }}">Editar</a>
                            <form action="{{ route('salas.destroy', $sala) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal de Edição -->
                    <div class="modal fade" id="editarSalaModal{{ $sala->id }}" tabindex="-1" aria-labelledby="editarSalaModalLabel{{ $sala->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editarSalaModalLabel{{ $sala->id }}">Editar Sala</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('salas.update', $sala) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="nome{{ $sala->id }}">Nome</label>
                                            <input type="text" name="nome" id="nome{{ $sala->id }}" class="form-control" value="{{ $sala->nome }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="descricao{{ $sala->id }}">Descrição/Localização</label>
                                            <input type="text" name="descricao" id="descricao{{ $sala->id }}" class="form-control" value="{{ $sala->descricao }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="situacao{{ $sala->id }}">Situação</label>
                                            <select name="situacao" id="situacao{{ $sala->id }}" class="form-control" required>
                                                <option value="ativa" {{ $sala->situacao === 'ativa' ? 'selected' : '' }}>Ativa</option>
                                                <option value="inativa" {{ $sala->situacao === 'inativa' ? 'selected' : '' }}>Inativa</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-success">Salvar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Fim do Modal de Edição -->
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Modal de Cadastro -->
<div class="modal fade" id="cadastrarSalaModal" tabindex="-1" aria-labelledby="cadastrarSalaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cadastrarSalaModalLabel">Cadastrar Nova Sala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('salas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="nome">Sala</label>
                        <input type="text" name="nome" id="nome" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição/Localização</label>
                        <input type="text" name="descricao" id="descricao" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="situacao">Situação</label>
                        <select name="situacao" id="situacao" class="form-control form-select" required>
                            <option value="ativa">Ativa</option>
                            <option value="inativa">Inativa</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="imagem">Imagem</label>
                        <input type="file" name="imagem" id="imagem" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Salvar</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Fim do Modal de Cadastro -->
@endsection
