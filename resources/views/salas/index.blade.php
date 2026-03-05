@extends('layouts.app')
@section('title', 'Lista de Salas')

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

<style>
    .table-responsive, .tabela-main-page {
        overflow: visible !important;
    }
    .dropdown-menu {
        z-index: 1050 !important;
    }
</style>

<div class="container mt-5">
    <div class="tabela-main-page">
        <div class="text-center fw-semibold mb-4">
            <span class="title-meetings">Lista de Salas</span>
            <div class="pt-1 pb-4">
                <a href="#" class="button-orange fw-normal text-decoration-none"
                   data-bs-toggle="modal" data-bs-target="#cadastrarSalaModal">
                    <i class="bi bi-plus fs-13 me-1"></i> Nova Sala
                </a>
            </div>
        </div>

        @if($salas->isEmpty())
            <p class="text-center">Não há salas cadastradas no momento.</p>
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
                                <td class="fs-13 text-center">{{ $sala->nome }}</td>
                                <td class="fs-13 text-center">{{ $sala->descricao }}</td>
                                <td class="fs-13 text-center">{{ ucfirst($sala->situacao) }}</td>
                                <td class="fs-13 text-center">
                                    <span style="display: inline-block; width: 45px; height: 25px; border: 1px solid #aaa; border-radius: 4px; background-color: {{ $sala->cor ?? '#ccc' }};"></span>
                                </td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <button class="button-garden dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding: 3px 9px;">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-dark">
                                            <li>
                                                <a href="#" class="dropdown-item fs-13" data-bs-toggle="modal" data-bs-target="#editarSalaModal{{ $sala->id }}">
                                                    <i class="bi bi-pencil-square me-1"></i> Editar
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" class="dropdown-item text-danger fs-13" data-bs-toggle="modal" data-bs-target="#confirmarExclusaoModal{{ $sala->id }}">
                                                    <i class="bi bi-trash me-1"></i> Excluir
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>

  
                            <div class="modal fade" id="editarSalaModal{{ $sala->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content text-dark">
                                        <div class="modal-header border-bottom-0">
                                            <h6 class="modal-title">Editar Sala</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('salas.update', $sala) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-body pt-0 text-start">
                                                <div class="form-line mt-3">
                                                    <label class="fw-medium fs-13">Sala:</label>
                                                    <input type="text" name="nome" class="input-custom" value="{{ old('nome', $sala->nome) }}" required>
                                                </div>
                                                <div class="form-line mt-3">
                                                    <label class="fw-medium fs-13">Descrição/Localização:</label>
                                                    <input type="text" name="descricao" class="input-custom" value="{{ old('descricao', $sala->descricao) }}" required>
                                                </div>
                                                <div class="row mt-3">
                                                    <div class="col-6">
                                                        <label class="fw-medium fs-13">Situação:</label>
                                                        <select name="situacao" class="form-select input-custom" required>
                                                            <option value="ativa" {{ old('situacao', $sala->situacao) == 'ativa' ? 'selected' : '' }}>Ativa</option>
                                                            <option value="inativa" {{ old('situacao', $sala->situacao) == 'inativa' ? 'selected' : '' }}>Inativa</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="fw-medium fs-13">Cor:</label>
                                                        <input type="color" name="cor" class="form-control" style="height: 38px;" value="{{ old('cor', $sala->cor) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-modal-footer border-top-0">
                                                <button type="button" class="button-grey" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="button-green">Salvar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                      
                            <div class="modal fade" id="confirmarExclusaoModal{{ $sala->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content text-dark">
                                        <div class="modal-header border-bottom-0">
                                            <h6 class="modal-title">Confirmar Exclusão</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body text-start fs-13">
                                            Tem certeza de que deseja excluir a sala <strong>{{ $sala->nome }}</strong>?
                                        </div>
                                        <div class="modal-footer bg-modal-footer border-top-0">
                                            <button type="button" class="button-grey" data-bs-dismiss="modal">Cancelar</button>
                                            <form action="{{ route('salas.destroy', $sala) }}" method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="button-red">Confirmar</button>
                                            </form>
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

<div class="modal fade" id="cadastrarSalaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h6 class="modal-title">Cadastrar Nova Sala</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('salas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3 text-start">
                        <label class="fw-medium fs-13"><span class="text-danger">*</span> Sala:</label>
                        <input type="text" name="nome" class="input-custom" value="{{ old('nome') }}" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="fw-medium fs-13"><span class="text-danger">*</span> Descrição/Localização:</label>
                        <input type="text" name="descricao" class="input-custom" value="{{ old('descricao') }}" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label class="fw-medium fs-13"><span class="text-danger">*</span> Imagem:</label>
                        <input type="file" name="imagem" class="input-custom" required>
                    </div>
                    <div class="row">
                        <div class="col-6 text-start">
                            <label class="fw-medium fs-13">Situação:</label>
                            <select name="situacao" class="form-select input-custom" required>
                                <option value="ativa" {{ old('situacao') == 'ativa' ? 'selected' : '' }}>Ativa</option>
                                <option value="inativa" {{ old('situacao') == 'inativa' ? 'selected' : '' }}>Inativa</option>
                            </select>
                        </div>
                        <div class="col-6 text-start">
                            <label class="fw-medium fs-13">Cor:</label>
                            <input type="color" name="cor" class="form-control" style="height: 38px;" value="{{ old('cor', '#3788d8') }}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-modal-footer border-top-0">
                    <button type="button" class="button-grey" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="button-green">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(session('success'))
    <div id="success-message" data-message="{{ session('success') }}" style="display: none;"></div>
@endif

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('js/salas/index.js') }}"></script>

@endsection