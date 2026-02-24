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
          window.location.href = "{{ session('return_url', route('home')) }}";
        });
      });
    </script>
  @endif

  @if(session('error'))
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          position: 'top',
          title: 'Erro!',
          text: '{{ session('error') }}',
          icon: 'error',
          confirmButtonText: 'Fechar',
          customClass: {
            confirmButton: 'button-red'
          }
        });
      });
    </script>
  @endif

  @if($errors->any())
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        let errorMessage = '';
        @foreach($errors->all() as $error)
          errorMessage += '• {{ $error }}\n';
        @endforeach
        
        Swal.fire({
          position: 'top',
          title: 'Ops! Verifique os campos',
          text: errorMessage,
          icon: 'warning',
          confirmButtonText: 'Entendi',
          customClass: {
            confirmButton: 'button-blue'
          }
        });
      });
    </script>
  @endif

  <script>
    $(document).ready(function() {
      function validarHorarios() {
        const horaInicio = $('#hora_inicio').val();
        const horaFim = $('#data_fim').val();
        const dataInicio = $('#data_inicio').val();
        
        if (horaInicio && horaFim && dataInicio) {
          const dataHoraInicio = new Date(dataInicio + 'T' + horaInicio + ':00');
          const dataHoraFim = new Date(dataInicio + 'T' + horaFim + ':00');
          
          if (dataHoraFim <= dataHoraInicio) {
            $('#hora_alert').removeClass('d-none');
            $('#submit-btn').prop('disabled', true);
          } else {
            $('#hora_alert').addClass('d-none');
            $('#submit-btn').prop('disabled', false);
          }
        }
      }

      $('#hora_inicio, #data_fim, #data_inicio').on('change', validarHorarios);
      validarHorarios();
    });
  </script>
@endpush

<div class="container pt-5" style="max-width: 480px;">
  <div class="box-page">
    <div class="text-center fw-semibold mb-4">
      <span class="title-meetings">Alterar Reserva Cód: {{ $reserva->id }}</span>
    </div>

    <form id="form-edit" action="{{ route('reservas.update', $reserva->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row g-3">
        <div class="col-12">
          <label for="sala_id" class="fw-medium">Sala <span class="text-danger">*</span></label>
          <select name="sala_id" id="sala_id" class="form-select text-capitalize pointer @error('sala_id') is-invalid @enderror" required>
            <option value="" disabled>Selecione uma sala</option>
            @foreach($salas as $sala)
              <option class="text-uppercase pointer" value="{{ $sala->id }}" 
                {{ old('sala_id', $reserva->sala_fk) == $sala->id ? 'selected' : '' }}>
                {{ $sala->nome }}
              </option>
            @endforeach
          </select>
          @error('sala_id')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        @if(auth()->user()->is_admin)
          <div class="col-12">
            <label class="fw-medium">Unidade Responsável <span class="text-danger">*</span></label>
            <select name="unidade_fk" class="form-select pointer @error('unidade_fk') is-invalid @enderror" required>
              <option value="" disabled>Selecione a unidade</option>
              @foreach($unidades as $unidade)
                <option value="{{ $unidade->id }}" 
                  {{ old('unidade_fk', $reserva->unidade_fk) == $unidade->id ? 'selected' : '' }}>
                  {{ $unidade->nome }}
                </option>
              @endforeach
            </select>
            @error('unidade_fk')
              <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
          </div>
        @else          
          <div class="col-12">
            <label class="fw-medium">Unidade Responsável</label>
            <input type="hidden" name="unidade_fk" value="{{ $reserva->unidade_fk }}">
            <div class="form-control bg-light" style="cursor: not-allowed; background-color: #f5f5f5;">
              <i class="bi bi-building me-2"></i>
              {{ $reserva->unidade->nome ?? 'Unidade não definida' }}
            </div>
          </div>
        @endif

        <div class="col-12">
          <label for="data_inicio" class="fw-medium">Data <span class="text-danger">*</span></label>
          <input type="date" 
                 name="data_inicio" 
                 id="data_inicio" 
                 class="input-custom pointer @error('data_inicio') is-invalid @enderror" 
                 value="{{ old('data_inicio', \Carbon\Carbon::parse($reserva->data_inicio)->format('Y-m-d')) }}" 
                 required>
          @error('data_inicio')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12 col-sm-6">
          <label class="fw-medium">Hora Início <span class="text-danger">*</span></label>
          <select name="hora_inicio" id="hora_inicio" class="form-select pointer @error('hora_inicio') is-invalid @enderror" required>
            <option value="" disabled>Selecione a hora</option>
            @foreach($horarios as $horario)
              <option value="{{ $horario }}" 
                {{ old('hora_inicio', \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i')) == $horario ? 'selected' : '' }}>
                {{ $horario }}
              </option>
            @endforeach
          </select>
          @error('hora_inicio')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12 col-sm-6">
          <label class="fw-medium">Hora Fim <span class="text-danger">*</span></label>
          <select name="data_fim" id="data_fim" class="form-select pointer @error('data_fim') is-invalid @enderror" required>
            <option value="" disabled>Selecione a hora</option>
            @foreach($horarios as $horario)
              <option value="{{ $horario }}" 
                {{ old('data_fim', \Carbon\Carbon::parse($reserva->data_fim)->format('H:i')) == $horario ? 'selected' : '' }}>
                {{ $horario }}
              </option>
            @endforeach
          </select>
          @error('data_fim')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-12">
          <div id="hora_alert" class="alert alert-warning py-2 d-none">
            <i class="bi bi-exclamation-triangle me-2"></i>
            A hora de término deve ser maior que a hora de início
          </div>
        </div>

        <div class="col-12 mt-2">
          <div class="border-top pt-3">
            <small class="text-muted d-block">
              <i class="bi bi-person-circle me-1"></i>
              Criado por: <span class="fw-medium">{{ $reserva->user->name ?? 'Usuário desconhecido' }}</span>
            </small>
            <small class="text-muted d-block">
              <i class="bi bi-calendar-check me-1"></i>
              Data da criação: <span class="fw-medium">{{ $reserva->created_at ? $reserva->created_at->format('d/m/Y H:i') : '—' }}</span>
            </small>
          </div>
        </div>
      </div>
    </form>

    <div class="d-flex justify-content-between pt-4">
      <a href="{{ session('return_url', route('home')) }}" class="button-grey">
        <i class="bi bi-arrow-left me-1"></i>
        Cancelar
      </a>
      <button type="submit" id="submit-btn" form="form-edit" class="button-green">
        <i class="bi bi-save me-1"></i>
        Salvar Alterações
      </button>
    </div>
  </div>
</div>
@endsection