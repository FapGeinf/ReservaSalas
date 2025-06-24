@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/form-custom.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

@section('content')

@section('title') {{ 'Editar Reserva' }} @endsection

<div class="form-custom form-no-bottom mt-5">

  <h5 class="fw-bold text-center text-uppercase underline-title mb-5">Editar Reserva</h5>

  <form id="form-edit" action="{{ route('reservas.update', $reserva->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-line">
      <label for="sala_id" class="fw-bold fs-16">Sala:</label>
      <select name="sala_id" id="sala_id" class="form-select text-uppercase pointer" required>
        @foreach($salas as $sala)
          <option class="text-uppercase pointer" value="{{ $sala->id }}" @if($sala->id == $reserva->sala_fk) selected @endif>
            {{ $sala->nome }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="form-line">
      <label for="data_inicio" class="fw-bold fs-16">Data:</label>
      <input type="date" name="data_inicio" id="data_inicio" class="input-custom pointer" value="{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('Y-m-d') }}" required>
    </div>

    <div class="form-line">
      <label for="hora_inicio" class="fw-bold fs-16">Hora Início:</label>
      <input type="time" name="hora_inicio" id="hora_inicio" class="input-custom pointer" value="{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i') }}" required>
    </div>

    <div class="form-line">
      <label for="data_fim" class="fw-bold fs-16">Hora Término:</label>
      <input type="time" name="data_fim" id="data_fim" class="input-custom pointer" value="{{ \Carbon\Carbon::parse($reserva->data_fim)->format('H:i') }}" required>
    </div>
  </form>
</div>

<div class="form-custom form-no-top pt-3">
  <div class="d-flex justify-content-end pb-3">
    <button type="submit" form="form-edit" class="button-green">Salvar Alterações</button>
  </div>
</div>
@endsection

{{-- <div class="p-30__no-bottom">
  <div class="mx-auto form_create-800">
    <div class="row justify-content-center">
      <div class="col">
        <div class="box__no-border no-margin-bottom title-bg">
          <h3 class="text-center fw-bold">Editar Reserva</h3>
          <div class="text-center">
            <span class="fs-19" style="color: #374151;">Reserva {{ $reserva->id }}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="mx-auto form_create__no-border-800">
      <div class="row justify-content-center">
        <div class="col">
          <div class="box__no-border" style="">
            <div class="" style="padding: 0 !important;">
              <form action="{{ route('reservas.update', $reserva->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3">
                  <div class="col-12 col-md-6">
                    <label for="sala_id" class="fw-bold fs-16">Sala:</label>
                    <select name="sala_id" id="sala_id" class="form-select pointer" required>
                      @foreach($salas as $sala)
                        <option value="{{ $sala->id }}" @if($sala->id == $reserva->sala_fk) selected @endif>{{ $sala->nome }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-12 col-md-6">
                    <label for="data_inicio" class="fw-bold fs-16">Data:</label>
                    <input type="date" name="data_inicio" id="data_inicio" class="input-custom pointer" value="{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('Y-m-d') }}" required>
                  </div>
                </div>

                <div class="row g-3 mt-1">
                  <div class="col-12 col-md-6">
                    <label for="hora_inicio" class="fw-bold fs-16">Hora Início:</label>
                    <input type="time" name="hora_inicio" id="hora_inicio" class="input-custom pointer" value="{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i') }}" required>
                  </div>

                  <div class="col-12 col-md-6">
                    <label for="data_fim" class="fw-bold fs-16">Hora Término:</label>
                    <input type="time" name="data_fim" id="data_fim" class="input-custom pointer" value="{{ \Carbon\Carbon::parse($reserva->data_fim)->format('H:i') }}" required>
                  </div>
                </div>

                <div class="d-flex justify-content-end mt-5">
                  <button type="submit" class="button-blue">Salvar Alterações</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div> --}}