@extends('layouts.app')
@section('title') {{ 'Lista de Salas' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

<style>
    .fs-14 {
        font-size: 14px;
    }

    .fs-20 {
        font-size: 20px;
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

<div class="p-30__no-bottom">
    <div class="mx-auto form_create">
        <div class="row justify-content-center">
            <div class="col">
                <div class="box__no-border no-margin-bottom title-bg pb-0 mb-0"
                    style="
                        border-top-left-radius: 8px;
                        border-top-right-radius: 8px;
                    ">
                    <h3 class="text-center text-uppercase fs-20 fw-bold">Lista de Salas</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto form_create__no-border">
        <div class="row justify-content-center">
            <div class="col">
                <div class="box__no-border">
                    <div class="border-table" style="padding: 0 !important;">

                        @if($salas->isEmpty())
                            <p>Não há salas cadastradas no momento.</p>
                        @else

                            <table class="table table-striped" style="font-size: 14px; margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th class="text-center table-bg border-none">SALA</th>
                                        <th class="text-center table-bg border-none">DESCRIÇÃO</th>
                                        <th class="text-center table-bg border-none">SITUAÇÃO</th>
                                        <th class="text-center table-bg border-none">COR</th>
                                        <th class="text-center table-bg border-none" style="width: 15%;">AÇÕES</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($salas as $sala)
                                        <tr>
                                            <td class="td-bg border-none text-uppercase align-middle text-center">
                                                {{ $sala->nome }}
                                            </td>

                                            <td class="td-bg border-none align-middle text-center">
                                                {{ $sala->descricao }}
                                            </td>

                                            <td class="td-bg border-none align-middle text-center">
                                                {{ $sala->situacao }}
                                                {{-- <x-status-indicator-green /> --}}
                                            </td>

                                            <td class="td-bg border-none align-middle text-center">
                                                @if ($sala->cor)
                                                    <span
                                                        style="
                                                            display: inline-block;
                                                            width: 25px;
                                                            height: 25px;
                                                            border-radius: 4px;
                                                            background-color: {{ $sala->cor }};
                                                            border: 1px solid #ccc;
                                                            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                                                            ">
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>

                                            <td class="text-center align-middle border-none">
                                                <a href="#" class="button-yellow buttons-sm td-mb text-decoration-none"
                                                    data-bs-toggle="modal" data-bs-target="#editarSalaModal{{ $sala->id }}">
                                                    <i class="fas fa-pen" style="font-size: 14px;"></i>
                                                </a>

                                                <!-- Botão de Exclusão (Abre o Modal) -->
                                                <button type="button" class="button-red td-mb buttons-sm" data-bs-toggle="modal"
                                                    data-bs-target="#confirmarExclusaoModal{{ $sala->id }}">
                                                    <i class="fas fa-trash" style="font-size: 14px;"></i>
                                                </button>

                                                <!-- Modal de Confirmação -->
                                                <div class="modal fade" id="confirmarExclusaoModal{{ $sala->id }}" tabindex="-1"
                                                    aria-labelledby="confirmarExclusaoModalLabel{{ $sala->id }}"
                                                    aria-hidden="true">

                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h6 class="modal-title fw-bold" id="confirmarExclusaoModalLabel{{ $sala->id }}">Confirmar Exclusão</h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                                                            </div>

                                                            <div class="modal-body text-start">
                                                                Tem certeza de que deseja excluir a sala "<span class="text-nowrap bg-body-secondary border">{{ $sala->nome }}</span>"?
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="button-grey"
                                                                    data-bs-dismiss="modal">Cancelar</button>
                                                                <!-- Botão de Confirmação (Submete o Formulário) -->
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

                                        <!-- Modal de Edição -->
                                        <div class="modal fade" id="editarSalaModal{{ $sala->id }}" tabindex="-1"
                                            aria-labelledby="editarSalaModalLabel{{ $sala->id }}" aria-hidden="true">

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

                                                            <!-- <div class="mt-4 col-5">
                                                                <label for="situacao{{ $sala->id }}"
                                                                    class="fw-bold fs-16">Situação:</label>
                                                                <select name="situacao" id="situacao{{ $sala->id }}"
                                                                    class="form-select" required>
                                                                    <option disabled selected>Selecione uma opção</option>
                                                                    <option value="ativa" {{ $sala->situacao === 'ativa' ? 'selected' : '' }}>Ativa</option>
                                                                    <option value="inativa" {{ $sala->situacao === 'inativa' ? 'selected' : '' }}>Inativa</option>
                                                                </select>
                                                            </div> -->

                                                            <div class="row mt-4">
                                                                <div class="col-md-6">
                                                                    <label for="situacao{{ $sala->id }}" class="fw-bold fs-16">Situação:</label>
                                                                    <select name="situacao" id="situacao{{ $sala->id }}"
                                                                        class="form-select input-custom pointer" required>
                                                                        <option disabled selected>Selecione uma opção</option>
                                                                        <option value="ativa" {{ $sala->situacao === 'ativa' ? 'selected' : '' }}>Ativa</option>
                                                                        <option value="inativa" {{ $sala->situacao === 'inativa' ? 'selected' : '' }}>Inativa</option>
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <label for="cor{{ $sala->id }}" class="fw-bold fs-16">Cor da Sala:</label>
                                                                    <input type="color" name="cor" id="cor{{ $sala->id }}"
                                                                        class="form-control input-custom"
                                                                        value="{{ $sala->cor ?? '#ffffff' }}">
                                                                </div>
                                                            </div>

                                                            <div class="d-flex justify-content-end mt-4">
                                                                <button type="submit" class="button-green">Salvar</button>
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

<!-- Modal de Cadastro -->
<div class="modal fade" id="cadastrarSalaModal" tabindex="-1" aria-labelledby="cadastrarSalaModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="cadastrarSalaModalLabel">Cadastrar Nova Sala</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="d-flex justify-content-center mt-1">
                <span class="fst-italic" style="font-size: 14px; color: #374151;">Campos marcados com
                    <span class="span-warning">*</span>são obrigatórios
                </span>
            </div>

            <div class="modal-body">
                <form action="{{ route('salas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="">
                        <label for="nome" class="fw-bold">Sala
                            <span class="span-warning">*</span>:
                        </label>
                        <input type="text" name="nome" id="nome" class="input-custom" required>
                    </div>

                    <div class="mt-4">
                        <label for="descricao" class="fw-bold">Descrição/ Localização
                            <span class="span-warning">*</span>:
                        </label>
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
                            <label for="imagem" class="fw-bold">Imagem
                                <span class="span-warning">*</span>:
                            </label>
                            <input type="file" name="imagem" id="imagem" class="input-custom" style="padding: 8px;" required>
                        </div>
                    </div>

                    <!-- NOVO CAMPO DE COR -->
                    <div class="mt-4">
                        <label for="cor" class="fw-bold">Cor da Sala:</label>
                        <input type="color" name="cor" id="cor" class="form-control form-control-color" value="#3788d8">
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="button-green fs-16" style="margin-right: 0 !important;">Salvar</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>