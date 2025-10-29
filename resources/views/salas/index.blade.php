@extends('layouts.app')
@section('title') {{ 'Lista de Salas' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/calendar-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/dropdown.css') }}">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
  .form-line-split select:first-child {
    margin-left: 44px;
  }

  @media (max-width: 768px) {
    .form-line-split input:first-child {
      margin-left: 0px;
    }
  }
</style>

@push('scripts')
  @if(session('success'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          title: 'Sucesso!',
          text: '{{ session('success ') }}',
          icon: 'success',
          confirmButtonText: 'Fechar',
          customClass: {
            confirmButton: 'button-green'
          }
        });
      });
    </script>
  @endif
@endpush

<div class="container mt-5">
  <div class="tabela-main-page">
    <div class="text-center fw-bold mb-4">
      <span class="title-meetings text-uppercase">Lista de Salas</span>
    </div>

    @if($salas->isEmpty())
      <p>Não há salas cadastradas no momento.</p>
    @else

    <table class="table table-reservas table-striped">
      <thead>
        <tr>
          <th class="text-light fs-12">Sala</th>
          <th class="text-light fs-12">Descrição</th>
          <th class="text-light fs-12">Situação</th>
          <th class="text-light fs-12">Cor</th>
          <th class="text-light fs-12">Opções</th>
        </tr>
      </thead>

      <tbody>
        @foreach($salas as $sala)
          <tr>
            <td class="fs-13">{{ $sala->nome }}</td>
            <td class="fs-13">{{ $sala->descricao }}</td>
            <td class="fs-13">{{ $sala->situacao }}</td>

            <td class="fs-13">
              @if ($sala->cor)
                <span style="
                  display: inline-block;
                  width: 45px;
                  height: 25px;
                  border: 1px solid #aaa;
                  border-radius: 4px;
                  background-color: {{ $sala->cor }};
                  ">
                </span>

              @else
                <span class="text-muted">Nenhuma cor selecionada.</span>
              @endif
            </td>

            <td class="fs-13">
              <div class="dropdown">
                <button class="custom-actions-btn" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>        
                
                <ul class="dropdown-menu">
                  <li>
                    <a href="#" class="dropdown-item text-decoration-none text-pattern fs-13" 
                      data-bs-toggle="modal" data-bs-target="#editarSalaModal{{ $sala->id }}">
                      <i class="bi bi-pencil-square me-1"></i>
                      Editar
                    </a>
                  </li>

                  <li>
                     <a href="#" class="dropdown-item text-decoration-none text-danger fs-13" 
                      data-bs-toggle="modal" data-bs-target="#confirmarExclusaoModal{{ $sala->id }}">
                      <i class="bi bi-trash me-1"></i>
                      Excluir
                    </a>
                  </li>
                </ul>
              </div>

              <div class="modal fade" id="confirmarExclusaoModal{{ $sala->id }}" tabindex="-1"
                aria-labelledby="confirmarExclusaoModalLabel{{ $sala->id }}" aria-hidden="true">

                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h6 class="modal-title" id="confirmarExclusaoModalLabel{{ $sala->id }}">Confirmar Exclusão</h6>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>

                    <div class="modal-body fs-13 text-start">
                      Tem certeza de que deseja excluir a sala
                      <span class="text-nowrap bg-body-secondary fw-semibold border">{{ $sala->nome }}</span>?
                    </div>

                    <div class="modal-footer py-2" style="background-color: #f1f1f1; border-top: 0px;">
                      <button type="button" class="button-grey" data-bs-dismiss="modal">Cancelar</button>
                      <form action="{{ route('salas.destroy', $sala) }}" method="POST"
                        style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="button-red">Confirmar</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>

          <div class="modal fade" id="editarSalaModal{{ $sala->id }}" tabindex="-1"
            aria-labelledby="editarSalaModalLabel{{ $sala->id }}" aria-hidden="true">

            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h6 class="modal-title" id="editarSalaModalLabel{{ $sala->id }}">Editar Sala</h6>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-0">
                  <form id="form-sala-{{ $sala->id }}" action="{{ route('salas.update', $sala) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-line mt-4">
                      <label for="nome{{ $sala->id }}" class="fw-bold fs-13">Sala:</label>
                      <input type="text" name="nome" id="nome{{ $sala->id }}" class="input-custom" value="{{ $sala->nome }}" required>
                    </div>

                    <div class="form-line mt-4">
                      <label for="descricao{{ $sala->id }}" class="fw-bold fs-13">Descrição/ Localização:</label>
                      <input type="text" name="descricao" id="descricao{{ $sala->id }}" class="input-custom" value="{{ $sala->descricao }}" required>
                    </div>

                    <div class="row mt-4">
                      <div class="col-12 col-sm-6">
                        <label class="fw-bold">Situação:</label>

                        <select name="situacao" id="situacao{{ $sala->id }}" class="form-select input-custom pointer" required>
                          <option selected disabled>Selecione</option>
                          <option value="ativa" {{ $sala->situacao === 'ativa' ? 'selected' : '' }}>Ativa</option>
                          <option value="inativa" {{ $sala->situacao === 'inativa' ? 'selected' : '' }}>Inativa</option>
                        </select>
                      </div>

                      <div class="col-12 col-sm-6">
                        <label class="fw-bold">Cor da Sala:</label>
                        <input type="color" name="cor" id="cor{{ $sala->id }}" class="form-control input-custom pointer"
                          style="height: 38px;" value="{{ $sala->cor ?? '#ffffff' }}">
                          
                      </div>
                    </div>
                  </form>
                </div>

                <div class="modal-footer py-2" style="background-color: #f1f1f1; border-top: 0px !important;">
                  <a href="#" class="button-grey text-decoration-none" data-bs-dismiss="modal">Cancelar</a>
                  <button type="submit" class="button-green" form="form-sala-{{ $sala->id }}">Salvar</button>
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </tbody>
    </table>
    @endif
  </div>
</div>

<div class="p-30__no-top">
  <div class="mx-auto form_create__no-border">
    <div class="box__no-border no-margin-bottom"
      style="
        background-color: #f1f1f1;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
      ">

      <a href="#" class="button-blue text-decoration-none float-end"
        data-bs-toggle="modal" data-bs-target="#cadastrarSalaModal">
        Nova Sala
      </a>
    </div>
  </div>
</div>

<div class="modal fade" id="cadastrarSalaModal" tabindex="-1" aria-labelledby="cadastrarSalaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="cadastrarSalaModalLabel">Cadastrar Nova Sala</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="form-save" action="{{ route('salas.store') }}" method="POST" enctype="multipart/form-data" class="mb-0">
        @csrf

        <div class="modal-body">
          <div class="d-flex justify-content-center mt-1 mb-3">
            <span class="fst-italic" style="font-size: 14px; color: #374151;">
              Campos marcados com <span class="span-warning">*</span> são obrigatórios
            </span>
          </div>

          <form>
            <div class="form-line mt-3">
              <label for="nome" class="fw-bold">Sala <span class="span-warning">*</span>:</label>
              <input type="text" name="nome" id="nome" class="input-custom" required>
            </div>

            <div class="form-line mt-3">
              <label for="descricao" class="fw-bold">Descrição/<br>Localização <span class="span-warning">*</span>:</label>
              <input type="text" name="descricao" id="descricao" class="input-custom" required>
            </div>

            <div class="form-line mt-3">
              <label for="imagem" class="fw-bold">Imagem <span class="span-warning">*</span>:</label>
              <input type="file" name="imagem" id="imagem" class="input-custom" required>
            </div>

            <div class="form-line mt-3">
              <label class="fw-bold fs-16">Situação/<br>Cor da Sala:<span class="span-warning">*</span>:</label>

              <div class="form-line-split">
                <select name="situacao" id="situacao" class="form-select input-custom pointer" required>
                  <option value="ativa">Ativa</option>
                  <option value="inativa">Inativa</option>
                </select>

                <input type="color" name="cor" id="cor" class="form-control input-custom pointer" style="height: 42px;" value="#3788d8">
              </div>
            </div>
          </form>
        </div>

        <div class="modal-footer" style="background-color: #f1f1f1; border-top: 0px;">
          <a href="#" class="button-grey text-decoration-none" data-bs-dismiss="modal">Cancelar</a>
          <button form="form-save" type="submit" class="button-green">Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>