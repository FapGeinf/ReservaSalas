@extends('layouts.app')
@section('title', 'Editar Reserva')

@section('content')

@php
    $horarios = [];
    for ($h = 8; $h <= 20; $h++) {
        for ($m = 0; $m < 60; $m += 30) {
            if ($h == 20 && $m == 30) continue;
            $horario = sprintf('%02d:%02d', $h, $m);
            $horarios[] = $horario;
        }
    }
@endphp

<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/boxes.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/swal-alert.css') }}">
<link rel="stylesheet" href="{{ asset('css/reservas/edit.css') }}">

<div id="flash-messages" 
     data-success="{{ session('success') }}" 
     data-error="{{ session('error') }}"
     data-errors-list="{{ $errors->any() ? implode(' • ', $errors->all()) : '' }}">
</div>

<div class="container pt-5 mb-5" style="max-width: 500px;">
    <div class="box-edit shadow-sm">
        <div class="text-center mb-4">
            <h5 class="fw-bold mb-1">Alterar Reserva</h5>
            <span class="badge bg-warning-subtle text-warning px-2 py-1 rounded-pill border border-warning-subtle">
                CÓDIGO #{{ $reserva->id }}
            </span>
        </div>

        <form id="form-edit" action="{{ route('reservas.update', $reserva->id) }}" method="POST">
            @csrf 
            @method('PUT')

            <div class="row g-3">
                
                <div class="col-12">
                    <span class="text-danger">*</span>
                    <label class="fw-semibold">Sala:</label>
                    <select name="sala_fk" id="sala_fk" class="form-select input-custom @error('sala_fk') is-invalid @enderror" required>
                        @foreach($salas as $sala)
                            <option value="{{ $sala->id }}" {{ old('sala_fk', $reserva->sala_fk) == $sala->id ? 'selected' : '' }}>
                                {{ $sala->nome }}
                            </option>
                        @endforeach
                    </select>
                    @error('sala_fk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="fw-semibold">Unidade Responsável:</label>
                    @if(auth()->user()->is_admin)
                        <select name="unidade_fk" class="form-select input-custom @error('unidade_fk') is-invalid @enderror" required>
                            @foreach($unidades as $unidade)
                                <option value="{{ $unidade->id }}" {{ old('unidade_fk', $reserva->unidade_fk) == $unidade->id ? 'selected' : '' }}>
                                    {{ $unidade->nome }}
                                </option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="unidade_fk" value="{{ $reserva->unidade_fk }}">
                        <div class="info-readonly bg-light p-2 rounded border">
                            <i class="bi bi-building me-2 text-muted"></i>
                            {{ $reserva->unidade->nome ?? 'Unidade não definida' }}
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <span class="text-danger">*</span>
                    <label class="fw-semibold">Tipo de Reserva:</label>
                    <select name="tipo_reserva" id="tipo_reserva" class="form-select" required>
                        <option value="interno" {{ old('tipo_reserva', $reserva->finalidade) == 'interno' ? 'selected' : '' }}>
                            Reunião Interna
                        </option>
                        <option value="pesquisador" {{ old('tipo_reserva', $reserva->finalidade) == 'pesquisador' ? 'selected' : '' }}>
                            Atendimento ao Pesquisador
                        </option>
                    </select>
                </div>

                <div class="col-12">
                    <span class="text-danger">*</span>
                    <label class="fw-semibold">Data:</label>
                    <input type="date" name="data_reserva" id="data_inicio" 
                           class="input-custom @error('data_reserva') is-invalid @enderror" 
                           value="{{ old('data_reserva', \Carbon\Carbon::parse($reserva->data_inicio)->format('Y-m-d')) }}" required>
                    @error('data_reserva') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="col-6">
                    <span class="text-danger">*</span>
                    <label class="fw-semibold">Início:</label>
                    <select name="hora_inicio" id="hora_inicio" class="form-select input-custom" required>
                        @foreach($horarios as $horario)
                            @php $hora_atual = \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i'); @endphp
                            <option value="{{ $horario }}" {{ old('hora_inicio', $hora_atual) == $horario ? 'selected' : '' }}>
                                {{ $horario }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6">
                    <span class="text-danger">*</span>
                    <label class="fw-semibold">Fim:</label>
                    <select name="hora_termino" id="data_fim" class="form-select" required>
                        @foreach($horarios as $horario)
                            @php $hora_fim_atual = \Carbon\Carbon::parse($reserva->data_fim)->format('H:i'); @endphp
                            <option value="{{ $horario }}" {{ old('hora_termino', $hora_fim_atual) == $horario ? 'selected' : '' }}>
                                {{ $horario }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <div id="hora_alert" class="alert alert-danger py-2 d-none shadow-sm border-0" style="font-size: 13px;">
                        <i class="bi bi-exclamation-circle-fill me-2"></i>
                        O término deve ser maior que o início.
                    </div>
                </div>

                <div class="col-12 mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center opacity-75" style="font-size: 11px;">
                        <span><i class="bi bi-person-fill"></i> {{ $reserva->user->name ?? 'Usuário' }}</span>
                        <span><i class="bi bi-clock-history"></i> {{ $reserva->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-4">
                <a href="{{ session('return_url', route('reservas.index')) }}" class="button-grey text-decoration-none">
                    <i class="bi bi-x-lg me-1"></i>
                    Cancelar
                </a>

                <button type="submit" id="submit-btn" class="button-green">
                    <i class="bi bi-save2 me-1"></i>
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/messages/alert.js') }}"></script>
<script src="{{ asset('js/reservas/edit-validation.js') }}"></script>

@endsection