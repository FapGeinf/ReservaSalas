<!-- @extends('layouts.app')

@section('content')

<div class="container">
  <h1>Cadastro de Sala</h1>

  <form action="{{ route('salas.store') }}" method="POST">
    @csrf

    <div class="form-group">
      <label for="nome">Sala</label>
      <input type="text" name="nome" id="nome" class="form-control" required>
    </div>

    <div class="form-group">
      <label for="descricao">Descrição/Localização</label>
      <input type="text" name="descricao" id="descricao" class="form-control" required>
    </div>

    <div class="form-group">
      <label for="situacao">Situação</label>
      <select name="situacao" id="situacao" class="form-control form-select" required>
        <option value="ativa">Ativa</option>
        <option value="inativa">Inativa</option>
      </select>
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
  </form>

</div>
@endsection -->
