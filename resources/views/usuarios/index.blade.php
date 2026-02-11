@extends('layouts.app')
@section('title') {{ 'Usuários Cadastrados' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-borders.css') }}">
<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/calendar-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/swal-alert.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

@push('scripts')
  @if(session('success'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          position: 'top',
          title: 'Sucesso!',
          text: '{{ session('success') }}',
          icon: 'success',
          confirmButtonText: 'Fechar',
          customClass: {
            confirmButton: 'button-green'
          }
        });
      });
    </script>
  @endif
@endpush

<div class="container mt-5">
  <div class="tabela-main-page">
    <div class="text-center fw-medium mb-4">
      <div class="title-meetings">Usuários Cadastrados</div>

      <div class="pt-1 pb-4">
        <a href="{{ route('usuarios.create') }}" class="button-orange fw-normal text-decoration-none justify-content-center">
          <i class="bi bi-plus fs-13"></i>
          Novo Usuário
        </a>        
      </div>
    </div>

    <table id="tableUsers" class="table table-striped border-bottom-0 my-3">
      <thead>
        <tr>
          <th class="fs-13 text-center">Id</th>
          <th class="fs-13 text-center">Nome</th>
          <th class="fs-13 text-center">Login</th>
          <th class="fs-13 text-center">Email</th>
          <th class="fs-13 text-center">Unidade</th>
          <th class="fs-13 text-nowrap text-center">Tipo de Usuário</th>
          <th class="fs-13 text-center">Opções</th>
        </tr>
      </thead>

      <tbody>
        @foreach($usuarios as $usuario)
          <tr>
            <td data-th="Id" class="fs-13">{{ $usuario->id }}</td>
            <td data-th="Nome" class="fs-13">{{ $usuario->name }}</td>
            <td data-th="Login" class="fs-13">{{ $usuario->login }}</td>
            <td data-th="Email" class="fs-13">{{ $usuario->email }}</td>
            <td data-th="Unidade" class="fs-13">{{ $usuario->unidade ? $usuario->unidade->nome : 'Unidade não encontrada' }}</td>

            <td data-th="Tipo de Usuário" class="fs-13">
              @if(in_array($usuario->unidade_fk, [12, 14]))
                Administrador
              @else
                Comum
              @endif
            </td>

            <td data-th="Opções" class="fs-13">
              <div class="dropdown">
                <button class="button-garden" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 4px 9px;">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-dark">
                  <li>
                    <button type="button" class="dropdown-item text-danger fs-13" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-user-id="{{ $usuario->id }}">
                      <i class="bi bi-trash me-1"></i>
                      Excluir
                    </button>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body fs-14">
        Tem certeza que deseja excluir este usuário?
      </div>

      <div class="modal-footer py-2 bg-modal-footer">
        <button type="button" class="button-grey" data-bs-dismiss="modal">
          <i class="bi bi-x me-1"></i>
          Cancelar
        </button>

        <form id="deleteForm" action="" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="button-red">
            <i class="bi bi-trash fs-12 me-1"></i>
            Excluir
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function () {
    $('#tableUsers').DataTable({
      language: {
      url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
      search: "Procurar:",
      lengthMenu: "Paginação: _MENU_",
      info: 'Mostrando página _PAGE_ de _PAGES_',
      infoEmpty: 'Sem relatórios de risco disponíveis no momento',
      infoFiltered: '(Filtrados do total de _MAX_ relatórios)',
      zeroRecords: 'Nada encontrado. Se achar que isso é um erro, contate o suporte.',
      paginate: {
          next: "Próximo",
          previous: "Anterior"
        }
      },
      // scrollY: '200px',
      scrollCollapse: true,
      paging: true
    });
  });
</script>

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