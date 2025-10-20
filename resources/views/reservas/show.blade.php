@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/form-custom.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

@section('content')

@section('title') {{ 'Detalhes da Reserva' }} @endsection

<div class="form-custom no-border-bottom form-no-bottom mt-5">
  <h5 class="fw-bold text-center text-uppercase">
    Detalhes da Reserva
  </h5>

  {{-- <div class="text-center fst-italic" style="color: #374151;">
    Reserva {{ $reserva->id }}
  </div> --}}

  <div class="form-line mt-4">
    <label class="fw-bold">Nome:</label>
    <span class="input-custom-disabled">
      {{ $reserva->user->name ?? 'Usuário não encontrado' }}
    </span>
  </div>

  <div class="form-line mt-4">
    <label class="fw-bold">Unidade:</label>
    <span class="input-custom-disabled">
      {{ $reserva->user->unidade->nome ?? 'Unidade não encontrada' }}
    </span>
  </div>

  <div class="form-line mt-4">
    <label class="fw-bold">Sala:</label>
    <span class="input-custom-disabled">
      {{ $reserva->sala->nome ?? 'Sala não encontrada' }}
    </span>
  </div>

  <div class="form-line mt-4">
    <label class="fw-bold">Data da Reserva:</label>

    <span class="input-custom-disabled">
      {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y') }}
    </span>
  </div>

  <div class="form-line mt-4">
    <label class="fw-bold">Hora Início / Término:</label>
    
    <div class="form-line-split">
      <div class="input-custom-disabled">
        {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i') }}
      </div>

      <div class="input-custom-disabled">
        {{ \Carbon\Carbon::parse($reserva->data_fim)->format('H:i') }}
      </div>
    </div>
  </div>
</div>

<div class="form-custom no-border-top form-no-top pt-3">
  <div class="d-flex justify-content-end pb-3">
    <span style="color: #f1f1f1">Jesus vive!</span>
  </div>
</div>

@endsection