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
    <link rel="stylesheet" href="{{ asset('css/swal-alert.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

    <style>
        .table-responsive,
        .tabela-main-page {
            overflow: visible !important;
        }

        .dropdown-menu {
            z-index: 1050 !important;
        }

        .color-pill {
            display: inline-block;
            width: 35px;
            height: 18px;
            border-radius: 50px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            vertical-align: middle;
        }

        .badge-status {
            padding: 0.5em 1em;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .align-middle-custom td {
            vertical-align: middle !important;
        }

        #tableSalas tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.02);
            transition: 0.2s;
        }
    </style>

    <div class="container mt-5">

        <!-- Alertas de Sucesso e Erro -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-4 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Verifique os campos abaixo antes de continuar.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="tabela-main-page shadow-sm rounded-4 p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="title-meetings mb-0">Lista de Salas</h4>
                    <p class="text-muted small mb-0">Gerencie os espaços e locais de reunião</p>
                </div>
                <a href="#" class="button-orange fw-normal text-decoration-none shadow-sm" data-bs-toggle="modal"
                    data-bs-target="#cadastrarSalaModal">
                    <i class="bi bi-plus-lg me-1"></i> Nova Sala
                </a>
            </div>

            @if($salas->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-door-closed fs-1 text-muted opacity-25"></i>
                    <p class="mt-2 text-muted">Nenhuma sala encontrada no sistema.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table id="tableSalas" class="table table-hover border-bottom-0 my-3 align-middle-custom">
                        <thead class="table-light">
                            <tr>
                                <th class="fs-13 text-center">Sala</th>
                                <th class="fs-13 text-center">Descrição/Localização</th>
                                <th class="fs-13 text-center">Situação</th>
                                <th class="fs-13 text-center">Cor</th>
                                <th class="fs-13 text-center" style="width: 80px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salas as $sala)
                                <tr>
                                    <td class="fs-13 fw-semibold text-center">{{ $sala->nome }}</td>
                                    <td class="fs-13 text-center text-muted">{{ $sala->descricao }}</td>
                                    <td class="text-center">
                                        @if(strtolower($sala->situacao) == 'ativa')
                                            <span class="badge-status bg-success-subtle text-success">{{ $sala->situacao }}</span>
                                        @else
                                            <span class="badge-status bg-secondary-subtle text-secondary">{{ $sala->situacao }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="color-pill shadow-sm"
                                            style="background-color: {{ $sala->cor ?? '#ccc' }};"></span>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light border dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-dark shadow">
                                                <li>
                                                    <a href="#" class="dropdown-item fs-13 py-2" data-bs-toggle="modal"
                                                        data-bs-target="#editarSalaModal{{ $sala->id }}">
                                                        <i class="bi bi-pencil-square me-2 text-warning"></i> Editar
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider opacity-10">
                                                </li>
                                                <li>
                                                    <a href="#" class="dropdown-item text-danger fs-13 py-2" data-bs-toggle="modal"
                                                        data-bs-target="#confirmarExclusaoModal{{ $sala->id }}">
                                                        <i class="bi bi-trash me-2"></i> Excluir
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="cadastrarSalaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-light">
                    <h6 class="modal-title fw-bold">Cadastrar Nova Sala</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('salas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="fw-medium fs-13 mb-1"><span class="text-danger">*</span> Nome da Sala</label>
                            <input type="text" name="nome" class="form-control input-custom"
                                placeholder="Ex: Sala de Conferência A" required>
                        </div>
                        <div class="mb-3">
                            <label class="fw-medium fs-13 mb-1"><span class="text-danger">*</span>
                                Descrição/Localização</label>
                            <input type="text" name="descricao" class="form-control input-custom"
                                placeholder="Ex: Bloco B, 2º Andar" required>
                        </div>
                        <!-- <div class="mb-3">
                            <label class="fw-medium fs-13 mb-1"><span class="text-danger">*</span> Imagem de Referência</label>
                            <input type="file" name="imagem" class="form-control input-custom" required>
                        </div> -->
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="fw-medium fs-13 mb-1">Situação</label>
                                <select name="situacao" class="form-select input-custom" required>
                                    <option value="ativa" selected>Ativa</option>
                                    <option value="inativa">Inativa</option>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="fw-medium fs-13 mb-1">Cor no Calendário</label>
                                <input type="color" name="cor" class="form-control form-control-color w-100"
                                    style="height: 38px;" value="#3788d8">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-link text-muted text-decoration-none fs-13"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="button-green px-4 shadow-sm">Gravar Sala</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @foreach($salas as $sala)
        <div class="modal fade" id="editarSalaModal{{ $sala->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-light">
                        <h6 class="modal-title fw-bold">Editar: {{ $sala->nome }}</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('salas.update', $sala) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body p-4">
                            <div class="mb-3">
                                <label class="fw-medium fs-13 mb-1">Nome da Sala:</label>
                                <input type="text" name="nome" class="form-control input-custom"
                                    value="{{ old('nome', $sala->nome) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="fw-medium fs-13 mb-1">Descrição/Localização:</label>
                                <input type="text" name="descricao" class="form-control input-custom"
                                    value="{{ old('descricao', $sala->descricao) }}" required>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="fw-medium fs-13 mb-1">Situação:</label>

                                    <select name="situacao" class="form-select input-custom">
                                        <option value="ativa" {{ old('situacao', strtolower($sala->getRawOriginal('situacao'))) == 'ativa' ? 'selected' : '' }}>
                                            Ativa
                                        </option>

                                        <option value="inativa" {{ old('situacao', strtolower($sala->getRawOriginal('situacao'))) == 'inativa' ? 'selected' : '' }}>
                                            Inativa
                                        </option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label class="fw-medium fs-13 mb-1">Cor:</label>
                                    <input type="color" name="cor" class="form-control form-control-color w-100"
                                        style="height: 38px;" value="{{ old('cor', $sala->cor) }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer bg-light border-0">
                            <button type="button" class="btn btn-link text-muted text-decoration-none fs-13"
                                data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="button-green px-4 shadow-sm">Atualizar Dados</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="confirmarExclusaoModal{{ $sala->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-body text-center p-4">
                        <i class="bi bi-exclamation-triangle text-danger fs-1 mb-3"></i>
                        <h5 class="fw-bold">Excluir Sala?</h5>
                        <p class="text-muted small">Esta ação não poderá ser desfeita. Deseja realmente remover a sala
                            <strong>{{ $sala->nome }}</strong>?</p>
                        <div class="d-flex justify-content-center gap-2 mt-4">
                            <button type="button" class="btn btn-light border px-3 fs-13" data-bs-dismiss="modal">Não,
                                manter</button>
                            <form action="{{ route('salas.destroy', $sala) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger px-3 fs-13 shadow-sm">Sim, excluir</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('js/salas/index.js') }}"></script>
@endsection