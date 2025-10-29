@extends('layouts.app')
@section('title') {{ 'Cadastrar Novo Usuário' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/form-custom.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
  /* Removendo margem apenas para campos que não sejam senha */
  .form-line-split > input:first-child {
    margin-left: 42px;
  }

  .split-password {
    margin-left: 54px;
  }

  /* Mobile */
  @media (max-width: 768px) {
    .form-line-split > input:first-child {
      margin-left: 0px;
    }

    .split-password {
      margin-left: 0px;
    }
  }
</style>

<script>
  function togglePassword(inputId, iconId) {
    let passwordField = document.getElementById(inputId);
    let eyeIcon = document.getElementById(iconId);

    if (passwordField.type === "password") {
      passwordField.type = "text";
      eyeIcon.classList.remove("bi-eye");
      eyeIcon.classList.add("bi-eye-slash");

    } else {
      passwordField.type = "password";
      eyeIcon.classList.remove("bi-eye-slash");
      eyeIcon.classList.add("bi-eye");
    }
  }
</script>

@push('scripts')
  @if(session('success'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          title: 'Sucesso!',
          text: '{{ session('success') }}',
          icon: 'success',
          confirmButtonText: 'Fechar'
        });
      });
    </script>
  @endif
@endpush

<div class="form-custom no-border-bottom form-no-bottom mt-5">
  <h5 class="fw-bold text-center text-uppercase mb-3">
    Novo Usuário
  </h5>

  <form id="new-user" method="POST" action="{{ route('usuarios.store') }}">
    @csrf
    
    <div class="form-line mt-4">
      <label for="name" class="fw-bold">Nome:</label>
      <input type="text" class="input-custom" id="name" name="name" required>
    </div>

    <div class="form-line mt-4">
      <label for="email" class="fw-bold">Email:</label>
      <input type="email" class="input-custom" id="email" name="email" required>
    </div>

    <div class="form-line mt-4">
      <label for="unidade_fk" class="fw-bold">Unidade:</label>
      <select class="form-select pointer" id="unidade_fk" name="unidade_fk" required>
        <option value="">Selecione a unidade</option>

        @foreach($unidades as $unidade)
          <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-line mt-4">
      <label class="fw-bold">Login/ Tipo de Usuário:</label>

      <div class="form-line-split">
        <input type="text" class="input-custom" id="login" name="login" required>

        <select class="form-select pointer" id="role" name="role" required>
          <option value="user">Usuário Comum</option>
          <option value="admin">Administrador</option>
        </select>
      </div>
    </div>

    <div class="form-line mt-4">
      <label class="fw-bold">Senha/ Repita a senha:</label>

      <div class="form-line-split split-password">
        <div style="position: relative;">
          <input type="password" class="input-custom" id="password" name="password" required>
          <i id="eyePassword" class="bi bi-eye" 
            style="
            position: absolute;
            top: 50%;
            color: #374151;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;"
            onclick="togglePassword('password', 'eyePassword')">
          </i>
        </div>

        <div style="position: relative;">
          <input type="password" class="input-custom" id="password_confirmation" name="password_confirmation" required>
          <i id="eyePasswordConfirmation" class="bi bi-eye"
            style="position: absolute;
            top: 50%;
            color: #374151;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;"
            onclick="togglePassword('password_confirmation', 'eyePasswordConfirmation')">
          </i>
        </div>
      </div>
    </div>

  </form>
</div>

<div class="form-custom no-border-top form-no-top pb-3" style="padding-top: 13px;">
  <div class="d-flex justify-content-end">
    <button type="submit" form="new-user" class="button-green">
      <i class="bi bi-save me-1"></i>
      Cadastrar
    </button>
  </div>
</div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@if(session('cpf_error'))
  <script>
    var cpfErrorModal = new bootstrap.Modal(document.getElementById('cpfErrorModal'));
    cpfErrorModal.show();
  </script>
@endif