@extends('layouts.app')
@section('content')

@section('title') {{ 'Início' }} @endsection

<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">

<style>
  .modal-backdrop {
  background-color: rgba(0, 0, 0, 0.5) !important; /* Mais claro que o padrão (0.5) */
}

</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<div class="">
  <div class="p-30 mx-auto mt-5 divCards">

    <div class="row">
    @foreach($salas as $index => $sala)

      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card border position-relative">

          <!-- Imagem da Sala com Sobreposição -->
          <div class="bg__card_pattern bg__card_pattern_footer text-light text-center mb-0 position-relative">
            <img class="fit-image" src="{{ asset('img/salas/' . $sala->imagem) }}" alt="{{ $sala->nome }}">

            <!-- Sobreposição para Sala Inativa -->
            @php
              $situacao = strtolower(trim($sala->situacao)); // Normaliza o valor de 'situacao'
            @endphp

            @if($situacao === 'inativa')
              <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background-color: rgba(0, 0, 0, 0.7);">
                <span class="text-white fw-bold fs-5">Sala em Manutenção</span>
              </div>
            @endif
          </div>

          <!-- Corpo do Card -->
          <div class="card-body card-fofinho">
            <div class="title-teste text-center d-flex flex-column" style="margin-bottom: 1rem; margin-top: .5rem;">
              <span>Local</span>
              <h3 class="fw-bold text-uppercase" style="word-wrap:normal;">{{ $sala->nome }}</h3>
              <span class="mt-2">Descrição</span>
              <span class="" style="color:rgb(134, 132, 132); font-size: 14px;">
                <p class="card-text">{{ $sala->descricao }}</p>
              </span>
            </div>
          </div>

          <!-- Botões de Reserva e Ver Reservas -->
          <div class="card-body card-fofinho" style="background-color: #f1f1f1; padding: 10px 30px;">
            <div class="title-teste text-center d-flex flex-column">
              <div class="d-flex justify-content-center gap-3 py-2">
                <button 
                  type="button" 
                  class="button-green-index {{ $situacao === 'inativa' ? 'disabled d-none' : '' }}" 
                  data-bs-toggle="modal" 
                  data-bs-target="{{ $situacao === 'ativa' ? '#criarReservaModal' : '' }}" 
                  onclick="{{ $situacao === 'ativa' ? 'selecionarSala(' . $sala->id . ')' : 'return false;' }}"
                  {{ $situacao === 'inativa' ? 'disabled' : '' }}>
                  Reservar
                </button>

                <button 
                type="button" 
                class="button-blue" 
                data-bs-toggle="modal" 
                data-bs-target="#verReservasModal" 
                data-sala-id="{{ $sala->id }}" 
                onclick="carregarReservas({{ $sala->id }})"
                style="font-size: 15px;">
                Ver Reservas
              </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    @if (session('error'))
      <div class="alert alert-danger text-center mx-auto" style="max-width: 30%;">
        {{ session('error') }}
      </div>
    @endif

    @if (session('success'))
      <div class="alert alert-success text-center mx-auto" style="max-width: 30%;">
        {{ session('success') }}
      </div>
    @endif

  </div>
</div>

<div class="form-wrapper p-30 py-3 mx-auto divTable">

  <div class="custom__form_create">
    <div class="table-responsive">
      <table id="reservasTable" class="table table-striped"
      style="border-collapse: collapse; border: 1px solid #d3d3d3;">
        
      <thead>
        <tr>
          <th colspan="7" class="text-center fs-4">Reservas</th>
        </tr>

        <tr class="text-center">
          <th class="th__title">ID</th>
          <th class="th__title">SALA</th>
          <th class="th__title" style="white-space: nowrap">HORA INÍCIO</th>
          <th class="th__title" style="white-space: nowrap">HORA TÉRMINO</th>
          <th class="th__title" style="white-space: nowrap">RESERVADO POR</th>
          <th class="th__title">UNIDADE</th>
          <th class="th__title">AÇÕES</th>
        </tr>
      </thead>
   
      <tbody style="border-left: 1px solid #ccc;">
        @foreach($reservas as $reserva)
        <tr>

          <td class="text-center td__data">{{ $reserva->id }}</td>

          <td class="text-center">
            <div class="d-flex align-items-center">

              @if($reserva->sala && $reserva->sala->imagem)
                <img src="{{ asset('img/salas/' . $reserva->sala->imagem) }}" alt="" style="width: 45px; height: 45px" class="square"/>

              @else
                <p>Imagem não disponível</p>
              @endif
              
              <div class="ms-3">
                <p class="mb-1 text-uppercase">{{ $reserva->sala ? $reserva->sala->nome : 'Sala não encontrada' }}</p>
              </div>
            </div>
          </td>

          <td class="text-center">
            <p class="fw-normal mb-1">{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y | H:i') }}</p>
          </td>

          <td class="text-center">
            <p class="fw-normal mb-1">{{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y | H:i') }}</p>
          </td>

          <td class="text-center">
            <p class="fw-normal mb-1">{{ $reserva->user ? $reserva->user->name : '' }}</p>
          </td>

          <td class="text-center">
            <p class="fw-normal mb-1">{{ $reserva->user && $reserva->user->unidade ? $reserva->user->unidade->nome : '' }}</p>
          </td>

            <td class="text-center">
            <div class="d-flex flex-column flex-lg-row align-items-center justify-content-center gap-2">
              <a href="{{ route('reservas.show', $reserva->id) }}" class="button-blue text-decoration-none">
              <i class="fas fa-info-circle"></i>
              </a>
              
              <a href="{{ route('reservas.edit', $reserva->id) }}" class="button-yellow text-decoration-none">
              <i class="fa-regular fa-pen-to-square"></i>
              </a>
            
              <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" class="m-0">
              @csrf
              @method('DELETE')
              <button type="button" class="button-red" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" onclick="setDeleteAction('{{ route('reservas.destroy', $reserva->id) }}')">
                <i class="fa-solid fa-trash"></i>
              </button>
              </form>
            </div>
            </td>
        </tr>
        @endforeach
        </tbody>

      </table>
    </div>
  </div>
</div>

  <!-- Modal -->
  <div class="modal fade" id="criarReservaModal" tabindex="-1" aria-labelledby="criarReservaModalLabel" aria-hidden="true">
    
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="criarReservaModalLabel">Criar Reserva</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
          @csrf
          <input type="hidden" name="sala_fk" id="sala_fk">

          <div class="mb-3">
            <label for="data_reserva" class="fw-bold">Data:</label>
            <input type="date" name="data_reserva" id="data_reserva" class="input-custom" required>
          </div>

          <div class="mb-3">
            <label for="hora_inicio" class="fw-bold">Hora de Início:</label>
            <input type="time" name="hora_inicio" id="hora_inicio" class="input-custom" required>
          </div>

          <div class="mb-3">
            <label for="hora_termino" class="fw-bold">Hora de Término:</label>
            <input type="time" name="hora_termino" id="hora_termino" class="input-custom" required>
          </div>

        </form>
      </div>

      <div class="modal-footer">
        <button type="submit" form="reservaForm" class="button-green">Salvar Reserva</button>
        <button type="button" class="button-grey" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function() {
    $('#reservasTable').DataTable({
      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
        search: "Procurar:",
        lengthMenu: "Paginação: _MENU_",
        info: 'Mostrando página _PAGE_ de _PAGES_',
        infoEmpty: 'Sem relatórios de risco disponíveis no momento',
        infoFiltered: '(Filtrados do total de _MAX_ relatórios)',
        zeroRecords: 'Nada encontrado. Se achar que isso é um erro, contate o suporte.',
        paginate: {
          next: "Próximo",
          previous: "Anterior"
        }
      },
      scrollY: '200px',
      scrollCollapse: true,
      paging: true
    });
  });

  
  function setDeleteAction(action) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = action;
  }

  function selecionarSala(salaId) {
    console.log('Sala selecionada:', salaId); // Depuração
    document.getElementById('sala_fk').value = salaId;
}

function carregarReservas(salaId) {
  const dataSelecionada = document.getElementById('dataSelecionada').value;

  $('#reservasContainer').html('<p class="text-center"><i class="fa-regular fa-spinner" style="color: #2a64e7;"></i> Carregando reservas...</p>');

  $.ajax({
    url: '/reservas/sala/' + salaId,
    type: 'GET',
    data: { data: dataSelecionada },
    success: function (reservas) {
      let html = '';

      if (reservas.length === 0) {
        html = '<p class="text-center"><i class="fa-solid fa-x me-1" style="color: #b22720;"></i> Nenhuma reserva para esta data.</p>';
      } else {
        html += '<div class="reservas-grid">';
        reservas.forEach(reserva => {
          const unidade = reserva.user?.unidade?.nome ?? 'Unidade Desconhecida';
          const usuario = reserva.user ? reserva.user.name : 'N/A';
          const horaInicio = reserva.data_inicio.split(' ')[1];
          const horaFim = reserva.data_fim.split(' ')[1];

          html += `
          <div class="reserva-card">
            <span class="reserva-info">
              <i class="bi bi-building"></i>
              <strong>Unidade:</strong> ${unidade}
            </span>

            <span class="reserva-info">
              <i class="bi bi-clock"></i>
              <strong>Hora:</strong> ${horaInicio} - ${horaFim}
            </span>

            <span class="reserva-info">
              <i class="bi bi-person"></i>
              <strong>Reservado por:</strong> ${usuario}
            </span>
          </div>
          `;
        });
        html += '</div>';
      }

      $('#reservasContainer').html(html);
    },
    error: function () {
      $('#reservasContainer').html('<p class="text-center"><i class="fa-solid fa-x me-1" style="color: #b22720;"></i> Erro ao carregar reservas.</p>');
    }
  });
}

$(document).ready(function () {
  $('#dataSelecionada').on('change', function () {
    const salaId = $('#verReservasModal').data('sala-id');
    carregarReservas(salaId);
  });

  $('#verReservasModal').on('show.bs.modal', function (event) {
    const button = $(event.relatedTarget);
    const salaId = button.data('sala-id');
    $('#verReservasModal').data('sala-id', salaId);

    const hoje = new Date().toISOString().split('T')[0];
    $('#dataSelecionada').val(hoje);

    carregarReservas(salaId);
  });
});
</script>

<div class="modal fade" id="verReservasModal" tabindex="-1" aria-labelledby="verReservasModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content custom-modal">

      <div class="modal-header">
        <h5 class="modal-title fw-bold">Reservas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <div class="mb-3">
          <label for="dataSelecionada" class="form-label">Selecione a Data:</label>
          <input type="date" id="dataSelecionada" class="input-custom">
        </div>

        <div id="reservasContainer" class="reservas-container">
          <p class="text-center text-muted">
            <i class="fa-regular fa-spinner" style="color: #2a64e7;"></i> Carregando reservas...
          </p>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">

  <div class="modal-dialog modal-dialog-top">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        Tem certeza de que deseja excluir esta reserva? Essa ação não pode ser desfeita.
      </div>

      <div class="modal-footer">
        <form id="deleteForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="button-red">Excluir</button>
        </form>

        <button type="button" class="button-grey" data-bs-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>