@extends('layouts.app')
@section('title') {{ 'Novo Usuário' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/boxes.css') }}">
<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-borders.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/calendar-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/swal-alert.css') }}">
<script src="{{ asset('js/hidePassWord.js') }}"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

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
        }).then(() => {
          window.location.href = "{{ route('home') }}";
        });
      });
    </script>
  @endif
@endpush

<div class="container pt-5" style="max-width: 480px;">
  <div class="box-page">
    <div class="text-center fw-semibold mb-4">
      <span class="title-meetings">Novo Usuário</span>
    </div>

    <form id="new-user" method="POST" action="{{ route('usuarios.store') }}">
      @csrf

      <div class="row g-3 mt-3">
        <div class="col-12 col-sm-7">
          <label for="name" class="fw-medium">Nome:</label>
          <input type="text" class="input-custom" id="name" name="name" required>        
        </div>

        <div class="col-12 col-sm-5">
          <label class="fw-medium">Login:</label>
          <input type="text" class="input-custom" id="login" name="login" required>        
        </div>

        <div class="col-12 col-sm-7">
          <label class="fw-medium">Tipo de Usuário:</label>
          <select class="form-select pointer" id="role" name="role" required>
            <option value="user">Usuário Comum</option>
            <option value="admin">Administrador</option>
          </select>
        </div>

        <div class="col-12">
          <label for="email" class="fw-medium">Email:</label>
          <input type="email" class="input-custom" id="email" name="email" required>        
        </div>

        <div class="col-12">
          <label for="unidade_fk" class="fw-medium">Unidade:</label>
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
          <label class="fw-medium">Crie uma senha:</label>

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
          <label class="fw-medium">Repita a senha:</label>

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

    <div class="form-custom pt-4">
      <div class="d-flex justify-content-end">
        <button type="submit" form="new-user" class="button-green">
          <i class="bi bi-save me-1"></i>
          Cadastrar
        </button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@if(session('cpf_error'))
  <script>
    var cpfErrorModal = new bootstrap.Modal(document.getElementById('cpfErrorModal'));
    cpfErrorModal.show();
  </script>
@endif

@endsection