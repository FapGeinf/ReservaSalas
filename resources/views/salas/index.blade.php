@extends('layouts.app')
@section('title') {{ 'Lista de Salas' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-borders.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/calendar-page.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

<link rel="stylesheet" href="{{ asset('css/swal-alert.css') }}">

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

  @if(session('success'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          position: 'top',
          title: 'Sucesso!',
          text: @json(session('success')),
          icon: 'success',
          confirmButtonText: 'Fechar',
          customClass: {
            confirmButton: 'button-green'
          }
        });
      });
    </script>
  @endif

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      if (window.jQuery && $.fn.dataTable) {
        $('#tableSalas').DataTable({
          pageLength: 10,
          lengthMenu: [10, 25, 50, 100],
          ordering: true,
          language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/pt-BR.json'
          },
        });
      } else {
        console.error('DataTables não carregou. Verifique se o layout possui @stack("scripts") antes do </body>.');
      }
    });
  </script>
@endpush

<div class="container mt-5">
  <div class="tabela-main-page">
    <div class="text-center fw-bold mb-4">
      <span class="title-meetings">Lista de Salas</span>

        <div class="pt-1 pb-4">
          <a href="#" class="button-blue fw-normal text-decoration-none"
            data-bs-toggle="modal" data-bs-target="#cadastrarSalaModal">
            <i class="bi bi-plus fs-13 me-1"></i>
            Nova Sala
          </a>
      </div>
    </div>

    @if($salas->isEmpty())
      <p>Não há salas cadastradas no momento.</p>

    @else
      <div class="table-responsive">
        <table id="tableSalas" class="table table-striped border-bottom-0 my-3">
          <thead>
            <tr>
              <th class="fs-13 text-center">Sala</th>
              <th class="fs-13 text-center">Descrição</th>
              <th class="fs-13 text-center">Situação</th>
              <th class="fs-13 text-center">Cor</th>
              <th class="fs-13 text-center">Opções</th>
            </tr>
          </thead>

          <tbody>
            @foreach($salas as $sala)
              <tr>
                <td data-th="Sala" class="fs-13">{{ $sala->nome }}</td>
                <td data-th="Descrição" class="fs-13">{{ $sala->descricao }}</td>
                <td data-th="Situação" class="fs-13">{{ $sala->situacao }}</td>

                <td data-th="Cor" class="fs-13">
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

                <td data-th="Opções" class="fs-13">
                  <div class="dropdown">
                    <button class="button-garden" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 3px 9px;">
                      <i class="bi bi-three-dots-vertical"></i>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-dark">
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
                          <span class="text-nowrap bg-body-secondary fw-medium rounded border">{{ $sala->nome }}</span>?
                        </div>

                        <div class="modal-footer bg-modal-footer">
                          <button type="button" class="button-grey" data-bs-dismiss="modal">
                            <i class="bi bi-x me-1"></i>
                            Cancelar
                          </button>

                          <form action="{{ route('salas.destroy', $sala) }}" method="POST"
                            style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button-red">
                              <i class="bi bi-trash fs-12 me-1"></i>
                              Confirmar
                            </button>
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
                      <a href="#" class="button-grey text-decoration-none" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>
                        Cancelar
                      </a>

                      <button type="submit" class="button-green" form="form-sala-{{ $sala->id }}">
                        <i class="bi bi-save me-1"></i>
                        Salvar
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>

<div class="modal fade" id="cadastrarSalaModal" tabindex="-1" aria-labelledby="cadastrarSalaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="cadastrarSalaModalLabel">Cadastrar Nova Sala</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <form id="form-save" action="{{ route('salas.store') }}" method="POST" enctype="multipart/form-data" class="mb-0">
        @csrf

        <div class="modal-body">
          <div class="d-flex justify-content-center mt-1 mb-3">
            <span class="fs-13" style="color: #374151;">
              Campos marcados com <span class="text-danger">*</span> são obrigatórios
            </span>
          </div>

          <form>
            <div class="row g-3">
              <div class="col-12">
                <label for="nome" class="fw-medium">
                  <span class="text-danger">*</span>
                  Sala:
                </label>
                <input type="text" name="nome" id="nome" class="input-custom" required>
              </div>

              <div class="col-12">
                <label for="descricao" class="fw-medium">
                  <span class="text-danger">*</span>
                  Descrição/ Localização:
                </label>

                <input type="text" name="descricao" id="descricao" class="input-custom" required>
              </div>

              <div class="col-12">
                <label for="imagem" class="fw-medium">
                  <span class="text-danger">*</span>
                  Imagem:
                </label>

                <input type="file" name="imagem" id="imagem" class="input-custom" required>
              </div>

              <div class="col-12 col-sm-6">
                <label class="fw-medium">
                  <span class="text-danger">*</span>
                  Situação:
                </label>

                <select name="situacao" id="situacao" class="form-select input-custom pointer" required>
                  <option value="ativa">Ativa</option>
                  <option value="inativa">Inativa</option>
                </select>
              </div>

              <div class="col-12 col-sm-6">
                <label class="fw-medium">
                  <span class="text-danger">*</span>
                  Cor da Sala:
                </label>

                <input type="color" name="cor" id="cor" class="form-control input-custom pointer" style="height: 38px;" value="#3788d8">
              </div>
            </div>
          </form>
        </div>

        <div class="modal-footer bg-modal-footer">
          <a href="#" class="button-grey text-decoration-none" data-bs-dismiss="modal">
            <i class="bi bi-x me-1"></i>
            Cancelar
          </a>

          <button form="form-save" type="submit" class="button-green">
            <i class="bi bi-save me-1"></i>
            Salvar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection