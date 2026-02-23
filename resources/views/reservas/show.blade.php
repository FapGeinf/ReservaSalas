@extends('layouts.app')
@section('title') {{ 'Detalhes da Reserva' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/boxes.css') }}">
<link rel="stylesheet" href="{{ asset('css/salas.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/form-custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/swal-alert.css') }}">

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
  <div class="box-page pb-5">
    <div class="text-center fw-semibold mb-4">
      <span class="title-meetings">Detalhes da Reserva</span>
    </div>

    <div class="row g-3">
      <div class="col-12">
        <label class="fw-medium">Nome:</label>
        <span class="input-custom-disabled">
          {{ $reserva->user->name ?? 'Usuário não encontrado' }}
        </span>        
      </div>

      <div class="col-12">
        <label class="fw-medium">Unidade:</label>
        <span class="input-custom-disabled">
          {{ $reserva->unidade->nome ?? 'Unidade não encontrada' }}
        </span>
      </div>

      <div class="col-12">
        <label class="fw-medium">Sala:</label>
        <span class="input-custom-disabled">
          {{ $reserva->sala->nome ?? 'Sala não encontrada' }}
        </span>
      </div>

      <div class="col-12">
        <label class="fw-medium">Data da Reserva:</label>
        <span class="input-custom-disabled">
          {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y') }}
        </span>
      </div>

      <div class="col-12 col-sm-6">
        <label class="fw-medium">Hora Início:</label>
        <span class="input-custom-disabled">
          {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i') }}
        </span>
      </div>

      <div class="col-12 col-sm-6">
        <label class="fw-medium">Hora Término:</label>
        <span class="input-custom-disabled">
          {{ \Carbon\Carbon::parse($reserva->data_fim)->format('H:i') }}
        </span>        
      </div>
    </div>
  </div>
</div>


@endsection