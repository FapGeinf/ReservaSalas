@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/form-custom.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>
  .form-line-split input:first-child {
    margin-left: 42px;
  }

  @media (max-width: 768px) {
    form-line-split input:first-child {
      margin-left: 0px;
    }
  }
</style>

@section('content')

@section('title') {{ 'Editar Reserva' }} @endsection

<div class="form-custom no-border-bottom form-no-bottom mt-5">
  <h5 class="fw-bold text-center text-uppercase pb-3">
    Editar Reserva
  </h5>

  <form id="form-edit" action="{{ route('reservas.update', $reserva->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-line mt-4">
      <label for="sala_id" class="fw-bold">Sala:</label>
        <select name="sala_id" id="sala_id" class="form-select text-uppercase pointer" required>
        @foreach($salas as $sala)
          <option class="text-uppercase pointer" value="{{ $sala->id }}" @if($sala->id == $reserva->sala_fk) selected @endif>
            {{ $sala->nome }}
          </option>
        @endforeach
      </select>
    </div>

    <div class="form-line mt-4">
      <label for="data_inicio" class="fw-bold">Data:</label>
      <input type="date" name="data_inicio" id="data_inicio" class="input-custom pointer" value="{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('Y-m-d') }}" required>
    </div>

    <div class="form-line mt-4">
      <label class="fw-bold">Hora Início/ Término:</label>

      <div class="form-line-split">
        <input type="time" name="hora_inicio" id="hora_inicio" class="input-custom pointer" value="{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i') }}" required>
      
        <input type="time" name="data_fim" id="data_fim" class="input-custom pointer" value="{{ \Carbon\Carbon::parse($reserva->data_fim)->format('H:i') }}" required>
      </div>
      
    </div>
  </form>
</div>

<div class="form-custom no-border-top form-no-top pt-3">
  <div class="d-flex justify-content-end pb-3">
    <button type="submit" form="form-edit" class="button-green">
      <i class="bi bi-save me-1"></i>
      Salvar Alterações
    </button>
  </div>
</div>

@endsection