@extends('layouts.app')
@section('content')

@section('title') {{ 'Lista de Salas' }} @endsection

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
          <h3 class="text-center fw-bold">Lista de Salas</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="mx-auto form_create__no-border">
    <div class="row justify-content-center">
      <div class="col">
        <div class="box__no-border">
          <div class=" border-table" style="padding: 0 !important;">
            
            @if($salas->isEmpty())
              <p>Não há salas cadastradas no momento.</p>
            @else

            <table class="table table-bordered table-striped" style="border: 1px solid #c0c4c9; font-size: 17px; margin-bottom: 0;">
            <thead style="border: 1px solid #c0c4c9;">
                  <tr>
                    <th class="text-center table-bg border-none">SALA</th>
                    <th class="text-center table-bg border-none">DESCRIÇÃO</th>
                    <th class="text-center table-bg border-none">SITUAÇÃO</th>
                    <th class="text-center table-bg border-none" style="width: 15%;">AÇÕES</th>
                  </tr>
                </thead>
          
                <tbody>
                  @foreach($salas as $sala)
                    <tr>
                      <td class="td-bg border-none text-center">
                        {{ $sala->nome }}
                      </td>

                      <td class="td-bg border-none text-center">
                        {{ $sala->descricao }}
                      </td>

                      <td class="td-bg border-none text-center">
                        {{ $sala->situacao }}
                        {{-- <x-status-indicator-green /> --}}
                      </td>

                      <td class="text-center border-none">
                        <a href="#" class="button-yellow td-mb text-decoration-none" data-bs-toggle="modal" data-bs-target="#editarSalaModal{{ $sala->id }}">
                          <i class="fas fa-pen"></i>
                        </a>

                        <!-- <form action="{{ route('salas.destroy', $sala) }}" method="POST" style="display:inline-block;">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="button-red td-mb">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form> -->
                        <!-- Botão de Exclusão (Abre o Modal) -->
<button type="button" class="button-red td-mb" data-bs-toggle="modal" data-bs-target="#confirmarExclusaoModal{{ $sala->id }}">
  <i class="fas fa-trash"></i>
</button>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmarExclusaoModal{{ $sala->id }}" tabindex="-1" aria-labelledby="confirmarExclusaoModalLabel{{ $sala->id }}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmarExclusaoModalLabel{{ $sala->id }}">Confirmar Exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>
      <div class="modal-body">
        Tem certeza de que deseja excluir a sala "{{ $sala->nome }}"?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <!-- Botão de Confirmação (Submete o Formulário) -->
        <form action="{{ route('salas.destroy', $sala) }}" method="POST" style="display:inline-block;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Confirmar</button>
        </form>
      </div>
    </div>
  </div>
</div>
                      </td>
                    </tr>
          
                      <!-- Modal de Edição -->
                      <div class="modal fade" id="editarSalaModal{{ $sala->id }}" tabindex="-1" aria-labelledby="editarSalaModalLabel{{ $sala->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title fw-bold" id="editarSalaModalLabel{{ $sala->id }}">Editar Sala</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
          
                            <div class="modal-body">
                              <form action="{{ route('salas.update', $sala) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="">
                                  <label for="nome{{ $sala->id }}" class="fw-bold fs-16">Sala:</label>
                                  <input type="text" name="nome" id="nome{{ $sala->id }}" class="input-custom" value="{{ $sala->nome }}" required>
                                </div>
          
                                <div class="mt-4">
                                  <label for="descricao{{ $sala->id }}" class="fw-bold fs-16">Descrição/ Localização:</label>
                                  <input type="text" name="descricao" id="descricao{{ $sala->id }}" class="input-custom" value="{{ $sala->descricao }}" required>
                                </div>
          
                                <div class="mt-4 col-5">
                                  <label for="situacao{{ $sala->id }}" class="fw-bold fs-16">Situação:</label>
                                  <select name="situacao" id="situacao{{ $sala->id }}" class="form-select" required>
                                     <option value="ativa" {{ $sala->situacao === 'ativa' ? 'selected' : '' }}>Ativa</option>
                                     <option value="inativa" {{ $sala->situacao === 'inativa' ? 'selected' : '' }}>Inativa</option>
                                  </select>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                  <button type="submit" class="button-green fs-16">Salvar</button>
                                </div>
                                
                              </form>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- Fim do Modal de Edição -->
                  @endforeach
                </tbody>
              </table>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="p-30__no-top">
  <div class="mx-auto form_create__no-border">
    <div class="box__no-border no-margin-bottom" style="background-color: #f1f1f1;">
      <a href="#" class="button-blue text-decoration-none float-end" style="font-size: 16px;" data-bs-toggle="modal" data-bs-target="#cadastrarSalaModal">
        Nova Sala
      </a>
    </div>
  </div>
</div>

<!-- Modal de Cadastro -->
<div class="modal fade" id="cadastrarSalaModal" tabindex="-1" aria-labelledby="cadastrarSalaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="cadastrarSalaModalLabel">Cadastrar Nova Sala</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="d-flex justify-content-center mt-1">
        <span class="fst-italic" style="font-size: 14px; color: #374151;">Campos marcados com <span class="span-warning">*</span> são obrigatórios</span>
      </div>

      <div class="modal-body">
        <form action="{{ route('salas.store') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="">
            <label for="nome" class="fw-bold">Sala<span class="span-warning">*</span>:</label>
            <input type="text" name="nome" id="nome" class="input-custom" required>
          </div>

          <div class="mt-4">
            <label for="descricao" class="fw-bold" >Descrição/ Localização<span class="span-warning">*</span>:</label>
            <input type="text" name="descricao" id="descricao" class="input-custom" required>
          </div>

          <div class="row mt-4">
            <div class="col-3">
              <label for="situacao" class="fw-bold">Situação<span class="span-warning">*</span>:</label>
              <select name="situacao" id="situacao" class="form-select" required>
                <option value="ativa">Ativa</option>
                <option value="inativa">Inativa</option>
              </select>
            </div>

            <div class="col-9">
              <label for="imagem" class="fw-bold">Imagem<span class="span-warning">*</span>:</label>

              <input type="file" name="imagem" id="imagem" class="input-custom" style="padding: 8px;" required>
            </div>
          </div>

          <div class="text-end mt-4">
            <button type="submit" class="button-green fs-16" style="margin-right: 0 !important;">Salvar</button>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<!-- Fim do Modal de Cadastro -->
@endsection

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

