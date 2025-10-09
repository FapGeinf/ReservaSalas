<nav class="navbar justify-content-center pt-4 py-2"
  style="background-color: #FFF;">
  <div class="d-flex flex-wrap align-items-center justify-content-center gap-3">

    @foreach($salas as $sala)
      @php
        $situacao = strtolower(trim($sala->situacao));
        $statusColor = $situacao === 'inativa' ? 'bg-danger' : 'bg-success';
      @endphp

      <div class="d-flex flex-column align-items-center px-2 py-1"
        style="font-size: 13px; line-height: 1.2; min-width: 100px; color: #4B3F2F;">
        
        <!-- Nome da sala -->
        <span class="fw-semibold text-uppercase text-center mb-1" style="font-size: 13px;">
          {{ $sala->nome }}
        </span>

        <!-- Status com bolinha -->
        <div class="d-flex align-items-center gap-1">
          <span class="rounded-circle {{ $statusColor }}" style="width: 8px; height: 8px; display:inline-block;"></span>
          @if($situacao === 'inativa')
            <span class="fw-medium" style="font-size: 12px; color: #A83232;">Manutenção</span>
          @else
            <span class="fw-medium" style="font-size: 12px; color: #2F593B;">Disponível</span>
          @endif
        </div>
      </div>
    @endforeach

    <!-- Botão minimalista de pesquisa -->
   <button class="btn btn-sm btn-light border rounded d-flex align-items-center"
    style="height: 32px; white-space: nowrap; padding: 0 8px;" 
    data-bs-toggle="modal"
    data-bs-target="#verReservasModal"> 
      <i class="bi bi-search" style="font-size: 14px;"></i> 
      <span class="ms-1" style="font-size: 12px;">Consultar reservas</span>
    </button>
  </div>
</nav>