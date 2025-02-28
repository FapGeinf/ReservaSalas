<!-- filepath: /resources/views/usuarios/index.blade.php -->
@extends('layouts.app')

@section('title') {{ 'Usuários Cadastrados' }} @endsection

@section('content')

<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">

<div class="container">
  <h3 class="text-center fw-bold mb-4">Usuários Cadastrados</h3>

  <div class="table-responsive">
    <table class="table custom-table">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Email</th>
          <th>CPF</th>
          <th>Unidade</th>
          <th>Ações</th>
        </tr>
      </thead>

      <tbody>
        @foreach($usuarios as $usuario)
          <tr>
            <td>{{ $usuario->name }}</td>
            <td>{{ $usuario->email }}</td>
            <td>{{ $usuario->cpf }}</td>
            <td>{{ $usuario->unidade ? $usuario->unidade->nome : 'Unidade não encontrada' }}</td>
            <td>
              <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-action btn-sm btn-primary">
                <i class="bi bi-pencil"></i>
              </a>

              <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                
                <button type="submit" class="btn btn-action btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

<!-- bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>