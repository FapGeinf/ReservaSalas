<!-- filepath: /resources/views/usuarios/index.blade.php -->
@extends('layouts.app')
@section('title') {{ 'Usuários Cadastrados' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive-table.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

@if(session('success'))
  <div class="d-flex justify-content-center">
    <div class="alert alert-success alert-dismissible fade show text-center alert-custom" style="max-width: 30%;" role="alert">
      {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
  </div>
@endif

<div class="p-30__no-bottom">
  
  <div class="form-wrapper p-30 py-3 mx-auto divTable">
    <div class="table-container-no-bottom text-center p-30 mt-5">
      <h4 class="fw-bold">
        USUÁRIOS CADASTRADOS
      </h4>
      
    </div>

    <div class="table-container-no-top">
      <div class="d-flex bg-light justify-content-end mb-3">
        <a href="{{ route('usuarios.create') }}" class="button-blue text-decoration-none">
          <i class="fas fa-plus"></i> Cadastrar Novo Usuário
        </a>
      </div>

      <table>
        <thead>
          <th>
            <label class="text-light">Id</label>
          </th>
        
          <th>
            <label class="text-light">Nome</label>
          </th>
        
          <th>
            <label class="text-light">Email</label>
          </th>

          <th>
            <label class="text-light">Cpf</label>
          </th>

          <th>
            <label class="text-light">Unidade</label>
          </th>

          <th>
            <label class="text-light">Opções</label>
          </th>
        </thead>

        <tbody>
          @foreach($usuarios as $usuario)
          <tr>
            <td data-label="Id">
              {{ $usuario->id }}
            </td>

            <td data-label="Nome">
              {{ $usuario->name }}
            </td>

            <td data-label="Email">
              {{ $usuario->email }}
            </td>

            <td data-label="Cpf">
              {{ $usuario->cpf }}
            </td>

            <td data-label="Unidade">
              {{ $usuario->unidade ? $usuario->unidade->nome : 'Unidade não encontrada' }}
            </td>

            <td data-label="Opções">
              <button type="button" class="button-red" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-user-id="{{ $usuario->id }}">
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        Tem certeza que deseja excluir este usuário?
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <form id="deleteForm" action="" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Excluir</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Script para passar o ID do usuário ao modal -->
<script>
  document.addEventListener("DOMContentLoaded", function () {
  var confirmDeleteModal = document.getElementById('confirmDeleteModal');
  confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget; // Botão que acionou o modal
    var userId = button.getAttribute('data-user-id'); // Obtém o ID do usuário
    var form = document.getElementById('deleteForm');
    form.action = '/usuarios/' + userId; // Atualiza a ação do formulário
  });
  });
</script>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection