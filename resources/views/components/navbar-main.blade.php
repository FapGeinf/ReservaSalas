<head>
  <link rel="stylesheet" href="{{ asset('css/navbar-main.css') }}">
</head>

<nav class="navbar justify-content-center bg-white pt-4 py-2 border-bottom">
  <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">
    @foreach($salas as $sala)
      @php
        $situacao = strtolower(trim($sala->situacao));
        $statusColor = $situacao === 'inativa' ? 'bg-danger' : 'bg-success';
      @endphp

      <div class="d-flex custom-column flex-column align-items-center px-2 py-1">
        <span class="fw-semibold text-uppercase text-center fs-13 mb-1">
          {{ $sala->nome }}
        </span>

        <div class="d-flex align-items-center gap-1">
          <span class="rounded-circle {{ $statusColor }} status-ball"></span>
          @if($situacao === 'inativa')
            <span class="fw-medium fs-12 color-red">Em manutenção</span>
          @else
            <span class="fw-medium fs-12">Disponível</span>
          @endif
        </div>
      </div>
    @endforeach

   {{-- <button class="button-light-grey border rounded d-flex align-items-center consultar-reserva-btn"
    data-bs-toggle="modal"
    data-bs-target="#verReservasModal"> 
      <i class="bi bi-search fs-14"></i> 
      <span class="ms-1 fs-12">Consultar reservas</span>
    </button> --}}
  </div>
</nav>