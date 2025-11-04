@extends('layouts.app')
@section('content')

@section('title') {{ 'Editar Perfil' }} @endsection

<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/form-custom.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<x-alert-toast/>

<div class="form-custom no-border-bottom form-no-bottom mt-5" style="max-width: 480px;">
  <h5 class="fw-bold text-center text-uppercase">
    Editar Perfil
  </h5>

  <form id="form-profile" method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PATCH')

    <div class="row g-3 mt-3">
      <div class="col-12">
        <label for="name" class="fw-bold">Nome Completo:</label>
        <input type="text" id="name" name="name" class="input-custom" value="{{ old('name', auth()->user()->name) }}" required placeholder="ex: Julliany Souza" autocomplete="name">

        <x-input-error :messages="$errors->get('name')" class="mt-2" />
      </div>

      <div class="col-12">
        <label for="email" class="fw-bold">Email:</label>
        <input type="email" id="email" name="email" class="input-custom" value="{{ old('email', auth()->user()->email) }}"
        required placeholder="ex: meuemail@email.com" autocomplete="username">

        <x-input-error :messages="$errors->get('email')" class="mt-2" />
      </div>

      <div class="col-12">
        <label for="unidade_fk" class="fw-bold">Unidade:</label>

        <select name="unidade_fk" id="unidade_fk" class="form-control form-select input-custom pointer" required>
          <option value="" selected disabled>Selecione a unidade</option>
          @foreach($unidades as $unidade)
            <option value="{{ $unidade->id }}" {{ auth()->user()->unidade_fk == $unidade->id ? 'selected' : '' }}>{{ $unidade->nome }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <div class="row g-3 mt-1">
      <div class="col-12 col-sm-6">
        <label for="password" class="fw-bold">Senha:</label>
        <input type="password" id="password" name="password" class="input-custom" placeholder="Mínimo de 8 caracteres" autocomplete="new-password">
        <x-input-error :messages="$errors->get('password')" class="mt-2" />
      </div>

      <div class="col-12 col-sm-6">
        <label for="password_confirmation" class="fw-bold">Repita a senha:</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="input-custom" placeholder="Repita a senha" autocomplete="new-password">
        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
      </div>
    </div>
  </form>
</div>

<div class="form-custom no-border-top form-no-top pt-3" style="max-width: 480px;">
  <div class="d-flex justify-content-end pb-3">
    <button type="submit" form="form-profile" class="button-green">
      <i class="bi bi-save me-1"></i>
      Atualizar Perfil
    </button>
  </div>
</div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>