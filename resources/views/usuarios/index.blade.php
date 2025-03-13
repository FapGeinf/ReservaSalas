<!-- filepath: /resources/views/usuarios/index.blade.php -->
@extends('layouts.app')

@section('title') {{ 'Usuários Cadastrados' }} @endsection

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

<!-- <div class="p-30__no-bottom">
  <div class="mx-auto form_create">
    <div class="row justify-content-center">
      <div class="col">
        <div class="box__no-border no-margin-bottom title-bg">
          <h3 class="text-center fw-bold">Usuários Cadastrados</h3>
        </div>
      </div>
    </div>
  </div> -->


  <div class="p-30__no-bottom">
  <div class="mx-auto form_create">
    <div class="row justify-content-center">
      <div class="col">
        <!-- Título da Página -->
        <div class="box__no-border no-margin-bottom title-bg">
          <h3 class="text-center fw-bold">Usuários Cadastrados</h3>
    

        <!-- Botão de Cadastrar Novo Usuário -->
        <div class="d-flex justify-content-end mb-3">
          <a href="{{ route('usuarios.create') }}" class="button-blue text-decoration-none">
            <i class="fas fa-plus"></i> Cadastrar Novo Usuário
          </a>
        </div>
      </div>
    </div>
  </div>
</div>


  <div class="mx-auto form_create__no-border">
    <div class="row justify-content-center">
      <div class="col">
        <div class="box__no-border">
          <div class=" border-table" style="padding: 0 !important;">

            <table class="table table-bordered table-striped" style="border: 1px solid #c0c4c9; font-size: 17px; margin-bottom: 0;">
              <thead style="border: 1px solid #c0c4c9;">
                <th class="text-center table-bg border-none">NOME</th>
                <th class="text-center table-bg border-none">EMAIL</th>
                <th class="text-center table-bg border-none">CPF</th>
                <th class="text-center table-bg border-none">UNIDADE</th>
                <th class="text-center table-bg border-none" style="width: 15%;">AÇÕES</th>
              </thead>

              <tbody>
                @foreach($usuarios as $usuario)

                <tr>
                  <td class="td-bg border-none text-center">
                    {{ $usuario->name }}
                  </td>

                  <td class="td-bg border-none text-center">
                    {{ $usuario->email }}
                  </td>

                  <td class="td-bg border-none text-center">
                    {{ $usuario->cpf }}
                  </td>

                  <td class="td-bg border-none text-center">
                    {{ $usuario->unidade ? $usuario->unidade->nome : 'Unidade não encontrada' }}
                  </td>

                  <td class="td-bg border-none text-center">
                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="button-yellow text-decoration-none">
                      <i class="fas fa-pen"></i>
                    </a>
      
                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
      
                      <button type="submit" class="button-red" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>

                @endforeach
              </tbody>
            </table>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

<!-- bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>