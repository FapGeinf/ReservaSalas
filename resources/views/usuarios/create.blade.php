@extends('layouts.app')
@section('title') {{ 'Novo Usuário' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/form-custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/swal-alert.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

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

<div class="form-custom no-border-bottom form-no-bottom mt-5" style="max-width: 480px;">
  <h5 class="fw-bold text-center text-uppercase mb-3">
    Novo Usuário
  </h5>

  <form id="new-user" method="POST" action="{{ route('usuarios.store') }}">
    @csrf

    <div class="row g-3 mt-3">
      <div class="col-12 col-sm-7">
        <label for="name" class="fw-bold">Nome:</label>
        <input type="text" class="input-custom" id="name" name="name" required>        
      </div>

      <div class="col-12 col-sm-5">
        <label class="fw-bold">Login:</label>
        <input type="text" class="input-custom" id="login" name="login" required>        
      </div>

      <div class="col-12 col-sm-7">
        <label class="fw-bold">Tipo de Usuário:</label>
        <select class="form-select pointer" id="role" name="role" required>
          <option value="user">Usuário Comum</option>
          <option value="admin">Administrador</option>
        </select>
      </div>

      <div class="col-12">
        <label for="email" class="fw-bold">Email:</label>
        <input type="email" class="input-custom" id="email" name="email" required>        
      </div>

      <div class="col-12">
        <label for="unidade_fk" class="fw-bold">Unidade:</label>
        <select class="form-select pointer" id="unidade_fk" name="unidade_fk" required>
          <option value="">Selecione a unidade</option>

          @foreach($unidades as $unidade)
            <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
          @endforeach
        </select>        
      </div>
    </div>

    <div class="row g-3 mt-1">
      <div class="col-12 col-sm-6">
        <label class="fw-bold">Crie uma senha:</label>

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
      </div>

      <div class="col-12 col-sm-6">
        <label class="fw-bold">Repita a senha:</label>

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

<div class="form-custom no-border-top form-no-top pb-3" style="padding-top: 12px; max-width: 480px;">
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