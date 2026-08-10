@extends('layouts.app')
@section('title', 'Usuários Cadastrados')
@section('content')

<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-borders.css') }}">
<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/calendar-page.css') }}">
<link rel="stylesheet" href="{{ asset('js/scripts/datatables.min.css') }}">

<div class="container mt-5">
  <div id="flash-messages" data-success="{{ session('success') }}"
    data-error="{{ session('error') ?: session('cpf_error') ?: $errors->first() }}">
  </div>

  <div class="tabela-main-page">
    <div class="text-center fw-semibold mb-4">
      <span class="title-meetings">Usuários Cadastrados</span>

      <div class="pt-1 pb-4">
        <a href="{{ route('usuarios.create') }}" style="padding: 0px 9px"
          class="nav-buttons fw-normal text-decoration-none d-inline-flex align-items-center justify-content-center">
          <i class="bi bi-plus fs-4"></i>
          Novo Usuário
        </a>
      </div>
    </div>

    <table id="tableUsers" class="table table-striped border-bottom-0 my-3">
      <thead>
        <tr>
          <th class="fs-13 bg-th-table">Nome</th>
          <th class="fs-13 bg-th-table">Login</th>
          <th class="fs-13 bg-th-table">Unidade</th>
          <th class="fs-13 bg-th-table">Tipo</th>
          <th class="fs-13 bg-th-table">Opções</th>
        </tr>
      </thead>

      <tbody>
        @foreach($usuarios as $usuario)
          <tr>
            <td data-th="Nome" class="fs-13">{{ $usuario->name }}</td>
            <td data-th="Login" class="fs-13">{{ $usuario->login }}</td>
            <td data-th="Unidade" class="fs-13">{{ $usuario->unidade->nome ?? 'Não definida' }}</td>
            <td data-th="Tipo" class="fs-13">
              @php
                $badgeClass = match((int) $usuario->nivel_acesso_id) {
                    3       => 'bg-danger',    // Root
                    2       => 'bg-success',   // Admin
                    default => 'bg-secondary', // Comum
                };
              @endphp

              <span class="badge {{ $badgeClass }}">
                {{ $usuario->nivelAcesso->tipo ?? 'N/A' }}
              </span>
            </td>

              <td data-th="Opções" class="fs-13">
                <div class="dropdown">
                  <button class="button-grey" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 0px 7px;">
                    <i class="bi bi-three-dots-vertical"></i>
                  </button>

                <ul class="dropdown-menu">
                  <li>
                    <button type="button" class="dropdown-item fs-13 btn-edit-user" data-user-id="{{ $usuario->id }}">
                      <i class="bi bi-pencil me-1"></i> Editar
                    </button>
                  </li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li>
                    <button type="button" class="dropdown-item text-danger fs-13" data-bs-toggle="modal"
                      data-bs-target="#confirmDeleteModal" data-user-id="{{ $usuario->id }}">
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

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title">Confirmação de Exclusão</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body fs-14">
        Tem certeza que deseja excluir este usuário? Esta ação não pode ser desfeita.
      </div>

      <div class="modal-footer py-2 bg-modal-footer">
        <button type="button" class="button-grey" data-bs-dismiss="modal">
          <i class="bi bi-x-lg me-1"></i>
          Cancelar
        </button>

        <form id="deleteForm" action="" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="button-red">
            <i class="bi bi-trash me-1"></i>
            Excluir
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

  {{-- Modal de Edição --}}
  <div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Editar Usuário</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>
        
        <form id="editUserForm" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="fs-13 fw-semibold">Nome Completo:</label>
                <input type="text" name="name" id="edit_name" class="input-custom fs-13" required>
              </div>
              
              <div class="col-md-6">
                <label class="fs-13 fw-semibold">Login do Sistema:</label>
                <input type="text" name="login" id="edit_login" class="input-custom fs-13" required>
              </div>

            <div class="col-md-6">
              <label class="fs-13 fw-semibold">Unidade:</label>
              <select name="unidade_fk" id="edit_unidade" class="form-select fs-13" required>
                {{-- Populado via JS --}}
              </select>
            </div>

            <div class="col-md-6">
                <label class="fs-13 fw-semibold">Nível de Acesso:</label>
                <select class="fs-13 form-select" id="edit_nivel_acesso_id" name="nivel_acesso_id" required>
                    {{-- Populado via JS --}}
                </select>
            </div>

              <div class="col-md-6">
                <label class="fs-13 fw-semibold">Nova Senha:</label>
                <input type="password" name="password" class="input-custom fs-13" placeholder="Deixe em branco para manter a atual">
                <small class="text-muted fs-11 mt-1">Mínimo de 8 caracteres.</small>
              </div>

              <div class="col-md-6">
                <label class="fs-13 fw-semibold">Confirmar Nova Senha:</label>
                <input type="password" name="password_confirmation" class="input-custom fs-13" placeholder="Confirme a nova senha">
              </div>
            </div>
          </div>
          
          <div class="modal-footer py-2 bg-modal-footer">
            <button type="button" class="button-grey" data-bs-dismiss="modal">
              <i class="bi bi-x-lg me-1"></i>
              Cancelar
            </button>

            <button type="submit" class="button-green">
              <i class="bi bi-check-circle me-1"></i>
              Salvar Alterações
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/scripts/jquery-3.7.1.min.js') }}"></script>
  <script src="{{ asset('js/scripts/datatables.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/usuarios/index.js') }}"></script>

@endsection