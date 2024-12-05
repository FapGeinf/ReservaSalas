@extends('layouts.app')

@section('content')

<style>
  /* Centralizar apenas o container */
  .content-wrapper {
    display: flex;
    justify-content: center; /* Centraliza horizontalmente */
    align-items: center;    /* Centraliza verticalmente */
    min-height: 80vh;      /* Garante que o pai ocupe toda a altura da tela */
  }

  .container {
    max-width: 720px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.10);
    padding: 15px;
    background-color: #f8fafc;
    border-radius: 8px; /* Bordas arredondadas */
  }
</style>

<div class="content-wrapper">
  <div class="container">

    <h3 class="text-center">Cadastro de Sala</h3>

    <form action="{{ route('salas.store') }}" method="POST">
      @csrf

      <div class="form-group mb-3">
        <label for="nome" class="mb-1">Sala:</label>
        <input type="text" name="nome" id="nome" class="form-control" required>
      </div>

      <div class="form-group mb-3">
        <label for="descricao">Descrição/Localização:</label>
        <input type="text" name="descricao" id="descricao" class="form-control" required>
      </div>

      <div class="form-group mb-3">
        <label for="situacao">Situação:</label>
        <select name="situacao" id="situacao" class="form-select" required>
          <option value="ativa">Ativa</option>
          <option value="inativa">Inativa</option>
        </select>
      </div>

      <button type="submit" class="btn btn-success">Salvar</button>

    </form>
  </div>
</div>

@endsection
