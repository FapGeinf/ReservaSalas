@extends('layouts.app')
@section('title') {{ 'Cadastrar Novo Usuário' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
    label {
        font-size: 16px;
    }

    .input-container {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-custom {
        width: 100%;
        padding-right: 40px; /* Espaço para o ícone */
    }

    i {
        position: absolute;
        right: 10px;
        cursor: pointer;
        color: #666;
    }

</style>

<script>
    function togglePassword(inputId, iconId) {
        let passwordField = document.getElementById(inputId);
        let eyeIcon = document.getElementById(iconId);

        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordField.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
</script>



@push('scripts')
@if(session('success'))
<script>
  document.addEventListener('DOMContentLoaded', function() {
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

<div class="p-30__no-bottom">
    <div class="mx-auto box-profile">
        <div class="row justify-content-center">
            <div class="col position-relative">
                <div class="box__no-border no-margin-bottom title-bg">
                    <h3 class="text-center fw-bold">Cadastrar Novo Usuário</h3>
                    <!-- Botão Fechar -->
                    <a href="{{ route('usuarios.index') }}" class="btn-close position-absolute end-0 top-0 m-3" aria-label="Fechar"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto box-profile">
        <div class="row justify-content-center">
            <div class="col">
                <div class="box__no-border">
                    <form method="POST" action="{{ route('usuarios.store') }}">
                        @csrf

                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="name" class="fw-bold">Nome:</label>
                            <input type="text" class="input-custom" id="name" name="name" required>
                        </div>

                        <!-- Login -->
                        <div class="mb-3">
                            <label for="login" class="fw-bold">Login:</label>
                            <input type="text" class="input-custom" id="login" name="login" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="fw-bold">Email:</label>
                            <input type="email" class="input-custom" id="email" name="email" required>
                        </div>

                        <!-- CPF -->
                        <div class="mb-3">
    <label for="cpf" class="form-label">CPF (debug)</label>
    <input type="text" name="cpf" id="cpf-debug" class="form-control" oninput="console.log(this.value)">
</div>

                       


                        <!-- Unidade -->
                        <div class="mb-3">
                            <label for="unidade_fk" class="fw-bold">Unidade:</label>
                            <select class="form-select" id="unidade_fk" name="unidade_fk" required>
                                <option value="">Selecione a unidade</option>
                                @foreach($unidades as $unidade)
                                <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Senha -->
                        <div class="mb-3">
                           <label for="password" class="fw-bold">Senha:</label>
                            <div class="input-container">
                            <input type="password" class="input-custom" id="password" name="password" required>
                         <i id="eyeIconPassword" class="fas fa-eye" onclick="togglePassword('password', 'eyeIconPassword')"></i>
                      </div>
                    </div>

                    <!-- Confirmação de Senha -->
                    <div class="mb-3">
                        <label for="password_confirmation" class="fw-bold">Confirme a Senha:</label>
                        <div class="input-container">
                        <input type="password" class="input-custom" id="password_confirmation" name="password_confirmation" required>
                         <i id="eyeIconConfirm" class="fas fa-eye" onclick="togglePassword('password_confirmation', 'eyeIconConfirm')"></i>
                     </div>
                </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="button-green">
                                Cadastrar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<!-- bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@if(session('cpf_error'))
<script>
    var cpfErrorModal = new bootstrap.Modal(document.getElementById('cpfErrorModal'));
    cpfErrorModal.show();
</script>
@endif
