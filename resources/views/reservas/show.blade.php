@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

@section('content')

@section('title') {{ 'Detalhes da Reserva' }} @endsection

<div class="p-30__no-bottom">
  <div class="mx-auto form_create-800">
    <div class="row justify-content-center">
      <div class="col">
        <div class="box__no-border no-margin-bottom title-bg">
          <h3 class="text-center fw-bold">Detalhes da Reserva</h3>
          <div class="text-center">
            <span class="fs-19" style="color: #374151;">Reserva {{ $reserva->id }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="mx-auto form_create__no-border-800">
    <div class="row justify-content-center">
      <div class="col">
        <div class="box__no-border" style="padding-bottom: 4rem; margin-bottom: 2rem;">
          <div class="" style="padding: 0 !important;">

            <div class="row g-3">
              <div class="col-12 ">
                <label for="" class="fw-bold fs-16">Nome:</label>

                <span class="input-custom-disabled">
                  {{ $reserva->user->name ?? 'Usuário não encontrado' }}
                </span>
              </div>

              <div class="col 12 ">
                <label for="" class="fw-bold fs-16">Unidade:</label>

                <span class="input-custom-disabled">
                  {{ $reserva->user->unidade->nome ?? 'Unidade não encontrada' }}
                </span>
              </div>
            </div>

            <div class="row g-3 mt-4">
              <div class="col-12 col-md-6">
                <label for="" class="fw-bold fs-16">Sala:</label>

                <span class="input-custom-disabled">
                  {{ $reserva->sala->nome ?? 'Sala não encontrada' }}
                </span>
              </div>

              <div class="col-12 col-md-6">
                <label for="" class="fw-bold fs-16">Data da Reserva:</label>

                <span class="input-custom-disabled">
                  {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y') }}
                </span>
              </div>
            </div>

            <div class="row g-3 mt-4">
              <div class="col-12 col-md-6">
                <label for="" class="fw-bold fs-16">Hora do Início:</label>

                <div class="input-custom-disabled">
                  {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i') }}
                </div>
              </div>

              <div class="col-12 col-md-6">
                <label for="" class="fw-bold fs-16">Hora do Término:</label>

                <div class="input-custom-disabled">
                  {{ \Carbon\Carbon::parse($reserva->data_fim)->format('H:i') }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection