@extends('layouts.app')
@section('title') {{ 'Usuários Cadastrados' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/calendar-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/dropdown.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
<link rel="stylesheet" href="{{ asset('css/swal-alert.css') }}">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

<style>
  .dataTables_wrapper .dataTables_info {
    font-size: 13px;
  }
</style>

@push('scripts')
  @if(session('success'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
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
    <div class="d-flex justify-content-center fw-bold mb-4 align-items-center gap-2">
      <span class="title-meetings text-uppercase">Usuários Cadastrados</span>
      <a href="{{ route('usuarios.create') }}" class="button-blue text-decoration-none d-flex align-items-center justify-content-center" style="padding: 7px 9px;">
        <i class="fas fa-plus" style="font-size: 10px;"></i>
      </a>
    </div>

    <table class="table table-striped" id="tableUsers">
      <thead>
        <tr>
          <th class="text-light bg-color-dgreen align-middle fs-12">Id</th>
          <th class="text-light bg-color-dgreen align-middle fs-12">Nome</th>
          <th class="text-light bg-color-dgreen align-middle fs-12">Login</th>
          <th class="text-light bg-color-dgreen align-middle fs-12">Email</th>
          <th class="text-light bg-color-dgreen align-middle fs-12">Unidade</th>
          <th class="text-light bg-color-dgreen align-middle fs-12">Tipo de Usuário</th>
          <th class="text-light bg-color-dgreen align-middle fs-12">Opções</th>
        </tr>
      </thead>

      <tbody>
        @foreach($usuarios as $usuario)
        <tr>
          <td class="fs-13" data-th="Id">{{ $usuario->id }}</td>
          <td class="fs-13" data-th="Nome">{{ $usuario->name }}</td>
          <td class="fs-13" data-th="Login">{{ $usuario->login }}</td>
          <td class="fs-13" data-th="Email">{{ $usuario->email }}</td>
          <td class="fs-13" data-th="Unidade">{{ $usuario->unidade ? $usuario->unidade->nome : 'Unidade não encontrada' }}</td>

          <td class="fs-13" data-th="Tipo de Usuário">
            @if(in_array($usuario->unidade_fk, [12, 14]))
              Administrador
            @else
              Comum
            @endif
          </td>

          <td class="fs-13" data-th="Opções">
            <button type="button" class="button-red fs-13" style="padding: 2px 6px;" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-user-id="{{ $usuario->id }}">
              <i class="bi bi-trash fs-12 me-1"></i>Excluir
            </button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body fs-14">
        Tem certeza que deseja excluir este usuário?
      </div>

      <div class="modal-footer py-2" style="background-color: #f1f1f1; border-top: 0px;">
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