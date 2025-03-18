<!-- filepath: c:\Users\jfurtado\Documents\GitHub\ReservaSalas\resources\views\usuarios\create.blade.php -->
@extends('layouts.app')

@section('title') {{ 'Cadastrar Novo Usuário' }} @endsection

@section('content')

<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
label {
    font-size: 16px;
}
</style>

@if(session('success'))
<div class="d-flex justify-content-center">
    <div class="alert alert-success alert-dismissible fade show text-center alert-custom" style="max-width: 30%;"
        role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
</div>
@endif

<!-- @if(session('cpf_error'))
<div class="modal fade" id="cpfErrorModal" tabindex="-1" aria-labelledby="cpfErrorModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cpfErrorModalLabel">Erro de Cadastro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                {{ session('cpf_error') }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
@endif -->

<div class="p-30__no-bottom">
    <div class="mx-auto form_create-800">
        <div class="row justify-content-center">
            <div class="col">
                <div class="box__no-border no-margin-bottom title-bg">
                    <h3 class="text-center fw-bold">Cadastrar Novo Usuário</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto form_create__no-border-800">
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
                            <label for="cpf" class="fw-bold">CPF:</label>
                            <input type="text" class="input-custom" id="cpf" name="cpf" required>
                            @error('cpf')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
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
                            <input type="password" class="input-custom" id="password" name="password" required>
                        </div>

                        <!-- Confirmação de Senha -->
                        <div class="mb-3">
                            <label for="password_confirmation" class="fw-bold">Confirme a Senha:</label>
                            <input type="password" class="input-custom" id="password_confirmation"
                                name="password_confirmation" required>
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
