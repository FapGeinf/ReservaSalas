@extends('layouts.app')
@section('content')

<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<div class="p-30__no-bottom">
  <div class="mx-auto form_create">
    <div class="row justify-content-center">
      <div class="col">
        <div class="box__no-border no-margin-bottom title-bg">
          <h3 class="text-center fw-bold">Editar Perfil</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="mx-auto form_create">
    <div class="row justify-content-center">
      <div class="col">
        <div class="box__no-border no-margin-bottom">
          <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            @if (session('status'))
              <div class="alert alert-success">
                {{ session('status') }}
              </div>
            @endif

            <div class="mb-3 col-8">
              <label for="name" class="fw-bold fs-16">Nome completo:</label>
              <input type="text" id="name" name="name" class="input-custom" value="{{ old('name', auth()->user()->name) }}" required placeholder="ex: Julliany Souza" autocomplete="name">

              <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <div class="mb-3 col-8">
              <label for="email" class="fw-bold fs-16">Email:</label>
              <input type="email" id="email" name="email" class="input-custom" value="{{ old('email', auth()->user()->email) }}" required placeholder="ex: meuemail@email.com" autocomplete="username">

              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mb-3 col-8">
              <label for="cpf" class="fw-bold fs-16">CPF:</label>
              <input type="text" id="cpf" name="cpf" class="input-custom" value="{{ old('cpf', auth()->user()->cpf) }}" required placeholder="ex: 000.000.000-00">
            </div>

            <div class="mb-3 col-8">
              <label for="unidade_fk" class="fw-bold fs-16">Unidade:</label>

              <select name="unidade_fk" id="unidade_fk" class="form-select" required>
                <option value="">Selecione a unidade</option>
                @foreach($unidades as $unidade)
                  <option value="{{ $unidade->id }}" {{ auth()->user()->unidade_fk == $unidade->id ? 'selected' : '' }}>{{ $unidade->nome }}</option>
                @endforeach
              </select>
            </div>

            <div class="mb-3 col-8">
              <label for="password" class="fw-bold fs-16">Senha:</label>
              <input type="password" id="password" name="password" class="input-custom" placeholder="Mínimo de 8 caracteres" autocomplete="new-password">

              <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="mb-3 col-8">
              <label for="password_confirmation" class="fw-bold fs-16">Repita a nova senha:</label>
              <input type="password" id="password_confirmation" name="password_confirmation" class="input-custom" placeholder="Repita a senha" autocomplete="new-password">

              <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>


            <button type="submit" class="button-green">Atualizar Perfil</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection