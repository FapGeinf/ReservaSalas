@extends('layouts.app')
@section('title') {{ 'Editar Reserva' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/boxes.css') }}">
<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/form-custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/swal-alert.css') }}">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

@push('scripts')
  @if(session('success'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          position: 'top',
          title: 'Sucesso!',
          text: '{{ session('success') }}',
          icon: 'success',
          confirmButtonText: 'Fechar',
          customClass: {
            confirmButton: 'button-green'
          }
        }).then(() => {
          window.location.href = "{{ route('home') }}";
        });
      });
    </script>
  @endif
@endpush

<div class="container pt-5" style="max-width: 480px;">
  <div class="box-page">
    <div class="text-center fw-semibold mb-4">
      <span class="title-meetings">Editar Reserva</span>
    </div>

    <form id="form-edit" action="{{ route('reservas.update', $reserva->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row g-3">
        <div class="col-12">
          <label for="sala_id" class="fw-medium">Sala:</label>

          <select name="sala_id" id="sala_id" class="form-select text-capitalize pointer" required>
            @foreach($salas as $sala)
              <option class="text-uppercase pointer" value="{{ $sala->id }}" @if($sala->id == $reserva->sala_fk) selected @endif>
                {{ $sala->nome }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-12">
          <label for="data_inicio" class="fw-medium">Data:</label>
          <input type="date" name="data_inicio" id="data_inicio" class="input-custom pointer" 
          value="{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('Y-m-d') }}" required>
        </div>

        <div class="col-12 col-sm-6">
          <label class="fw-medium">Hora Início:</label>
          <input type="time" name="hora_inicio" id="hora_inicio" class="input-custom pointer" value="{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i') }}" required>
        </div>

        <div class="col-12 col-sm-6">
          <label class="fw-medium">Hora Fim:</label>
          <input type="time" name="data_fim" id="data_fim" class="input-custom pointer" value="{{ \Carbon\Carbon::parse($reserva->data_fim)->format('H:i') }}" required>
        </div>
      </div>
    </form>

    <div class="d-flex justify-content-end pt-4">
      <button type="submit" form="form-edit" class="button-green">
        <i class="bi bi-save me-1"></i>
        Salvar Alterações
      </button>
    </div>
  </div>
</div>
@endsection