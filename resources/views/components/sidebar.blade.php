@props(['salas' => [], 'reservas' => []])

@vite(['resources/js/flatpickr-data.js'])

<head>
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
</head>

<button class="toggle-btn button-light-grey" id="toggleSidebar">☰</button>

<x-tutorial />

<div id="overlay" class="overlay"></div>

<div class="sidebar" id="sidebar" style="padding-top: 4rem;">
  <h6>Filtros de Consulta</h6>
  <p class="desc">Selecione a sala e a data desejadas para visualizar as reservas existentes.</p>

  <div class="mb-3">
    <label for="salaSelecionada" style="font-weight: 500; color: #374151; font-size: 14px;">
      Sala:
    </label>
    <select id="salaSelecionada" class="input-custom form-select pointer">
      <option value="">Mostrar todas as salas</option>
      @foreach($salas as $sala)
        <option value="{{ $sala->id }}">{{ $sala->nome }}</option>
      @endforeach
    </select>
  </div>

  <div class="filter-section">
    <label for="dataSelecionada">Data:</label>
    <input type="text" id="dataSelecionada" class="input-custom form-select"
      value="{{ $dataSelecionada ?? date('Y-m-d') }}">
  </div>

  <div class="reservas-container" id="reservasContainer">
    @forelse($reservas as $reserva)
      <div class="reserva-item mb-2 p-2 border rounded"
           data-sala-id="{{ $reserva->sala_fk }}"
           data-inicio="{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('Y-m-d') }}"
           data-fim="{{ \Carbon\Carbon::parse($reserva->data_fim)->format('Y-m-d') }}">
        <p class="mb-1"><strong>{{ $reserva->sala->nome ?? 'Sala' }}</strong></p>
        <p class="mb-0 text-muted" style="font-size: 12px;">
          {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y H:i') }}
          @if($reserva->data_inicio != $reserva->data_fim)
            até {{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y H:i') }}
          @endif
        </p>
      </div>
    @empty
      <p class="text-muted" id="semReservasMsg">Nenhuma reserva futura encontrada.</p>
    @endforelse
  </div>
</div>

<script src="{{ asset('js/sidebar.js') }}"></script>