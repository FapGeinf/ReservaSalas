<!-- filepath: /resources/views/usuarios/create.blade.php -->
@extends('layouts.app')

@section('title') {{ 'Cadastrar Novo Usuário' }} @endsection

@section('content')

<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
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
  <div class="mx-auto form_create">
    <div class="row justify-content-center">
      <div class="col">
        <div class="box__no-border no-margin-bottom title-bg">
          <h3 class="text-center fw-bold">Cadastrar Novo Usuário</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="mx-auto form_create__no-border">
    <div class="row justify-content-center">
      <div class="col">
        <div class="box__no-border">
          <form method="POST" action="{{ route('usuarios.store') }}">
            @csrf

            <!-- Nome -->
            <div class="mb-3">
              <label for="name" class="form-label">Nome</label>
              <input type="text" class="form-control input-text" id="name" name="name" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control input-text" id="email" name="email" required>
            </div>

            <!-- CPF -->
            <div class="mb-3">
              <label for="cpf" class="form-label">CPF</label>
              <input type="text" class="form-control input-text" id="cpf" name="cpf" required>
            </div>

            <!-- Unidade -->
            <div class="mb-3">
              <label for="unidade_fk" class="form-label">Unidade</label>
              <select class="form-control input-text" id="unidade_fk" name="unidade_fk" required>
                <option value="">Selecione a unidade</option>
                @foreach($unidades as $unidade)
                  <option value="{{ $unidade->id }}">{{ $unidade->nome }}</option>
                @endforeach
              </select>
            </div>

            <!-- Senha -->
            <div class="mb-3">
              <label for="password" class="form-label">Senha</label>
              <input type="password" class="form-control input-text" id="password" name="password" required>
            </div>

            <!-- Confirmação de Senha -->
            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirme a Senha</label>
              <input type="password" class="form-control input-text" id="password_confirmation" name="password_confirmation" required>
            </div>

            <div class="d-flex justify-content-end">
              <button type="submit" class="button-blue">
                <i class="fas fa-save"></i> Cadastrar
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