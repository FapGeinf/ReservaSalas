@extends('layouts.app')
@section('title', 'Detalhes da Reserva')

@section('content')

<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/boxes.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/swal-alert.css') }}">
<link rel="stylesheet" href="{{ asset('css/reservas/show.css') }}">

<div id="flash-messages" 
     data-success="{{ session('success') }}" 
     data-error="{{ session('error') }}">
</div>

<div class="container pt-5 mb-5" style="max-width: 500px;">
    <div class="box-details">
        <div class="text-center mb-5">
            <div class="badge bg-primary-subtle text-primary mb-2 px-3 py-2 rounded-pill" style="font-size: 10px; letter-spacing: 1px;">
                CÓDIGO DA RESERVA: #{{ $reserva->id }}
            </div>
            <h4 class="fw-bold text-dark mb-0">Detalhes da Reserva</h4>
        </div>

        <div class="row">
            <div class="col-12">
                <span class="info-label">Solicitante</span>
                <div class="info-value">
                    <i class="bi bi-person"></i>
                    {{ $reserva->user->name ?? 'Não informado' }}
                </div>
            </div>

            <div class="col-12">
                <span class="info-label">Unidade</span>
                <div class="info-value">
                    <i class="bi bi-geo-alt"></i>
                    {{ $reserva->unidade->nome ?? 'Não informada' }}
                </div>
            </div>

            <div class="col-12">
                <span class="info-label">Sala</span>
                <div class="info-value">
                    <i class="bi bi-door-closed"></i>
                    {{ $reserva->sala->nome ?? 'Não informada' }}
                </div>
            </div>

            <div class="col-12">
                <span class="info-label">Data</span>
                <div class="info-value">
                    <i class="bi bi-calendar-event"></i>
                    {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y') }}
                </div>
            </div>

            <div class="col-6 pe-2">
                <span class="info-label">Check-in</span>
                <div class="info-value">
                    <i class="bi bi-clock"></i>
                    {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i') }}
                </div>
            </div>

            <div class="col-6 ps-2">
                <span class="info-label">Check-out</span>
                <div class="info-value">
                    <i class="bi bi-clock-fill"></i>
                    {{ \Carbon\Carbon::parse($reserva->data_fim)->format('H:i') }}
                </div>
            </div>
        </div>

        <div class="mt-4 pt-2">
            <a href="{{ route('reservas.index') }}" class="btn-return">
                <i class="bi bi-chevron-left me-2"></i>
                Voltar para Lista
            </a>
        </div>
    </div>
</div>

<script src="{{ asset('js/messages/alert.js') }}"></script>
@endsection