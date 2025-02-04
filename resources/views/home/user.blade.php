@extends('layouts.app')
@section('content')


<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<div class="padding__left4">
  <div class="p-30 mx-auto mt-5" style="width: 80%">

    <div class="row">
      @foreach($salas as $index => $sala)

      <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4">
      <div class="card border">

        <div class="bg__card_pattern bg__card_pattern_footer text-light text-center mb-0">
        <img class="fit-image" src="{{ asset('img/salas/' . $sala->imagem) }}" alt="{{ $sala->nome }}">
        </div>


        <div class="card-body card-fofinho">
        <div class="title-teste text-center d-flex flex-column">
          <span>Local</span>
          <h3 class="fw-bold text-uppercase">{{ $sala->nome }}</h3>
          <span class="mt-3" style="color:rgb(134, 132, 132); font-size: 14px;">
          <p class="card-text">{{ $sala->descricao }}</p>
          </span>
        </div>
        </div>

        <div class="d-flex justify-content-center gap-3 py-3">
       <button 
         type="button" 
         class="button-68" 
         data-bs-toggle="modal" 
         data-bs-target="#criarReservaModal" 
         onclick="selecionarSala({{ $sala->id }})">
         Reservar
       </button>

       <button 
          type="button" 
          class="button-69" 
          data-bs-toggle="modal" 
          data-bs-target="#verReservasModal" 
          onclick="carregarReservas({{ $sala->id }})">
          Ver Reservas
       </button>
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

<div class="form-wrapper p-30 pt-3 mx-auto" style="width: 80.5%">
  {{-- <div class="text-center mb-3">
    <h2 class="fw-bold fst-italic">Reservas</h2>
  </div> --}}

  <div class="custom__form_create">
    <div class="table-responsive">
      <table id="reservasTable" class="table table-bordered table-rounded align-middle mb-4 bg-white"
        style="border-collapse: collapse; border: 1px solid #d3d3d3; background-color: #f5f5f5;">
        <thead class="">
          <tr style="">
            <th colspan="7" class="text-center fs-4">Reservas</th>
          </tr>
          <tr class="text-center">
            <th class="th__title">ID</th>
            <th class="th__title">SALA</th>
            <th class="th__title">HORA INÍCIO</th>
            <th class="th__title">HORA TÉRMINO</th>
            <th class="th__title">RESERVADO POR</th>
            <th class="th__title">UNIDADE</th>
            <th class="th__title">AÇÕES</th>
          </tr>
        </thead>

        <tbody>
          @foreach($reservas as $reserva)
          <tr>

            <td class="text-center">{{ $reserva->id }}</td>

            <td>
              <div class="d-flex align-items-center">
              @if($reserva->sala && $reserva->sala->imagem)
              <img src="{{ asset('img/salas/' . $reserva->sala->imagem) }}" alt="" style="width: 45px; height: 45px" class="rounded"/>
              @else
                <p>Imagem não disponível</p>
              @endif
                <div class="ms-3">
                  <p class="fw-bold mb-1">{{ $reserva->sala ? $reserva->sala->nome : 'Sala não encontrada' }}</p>
                </div>
              </div>
            </td>

            <td>
              <p class="fw-normal mb-1">{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y | H:i') }}</p>
            </td>

            <td>
              <p class="fw-normal mb-1">{{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y | H:i') }}</p>
            </td>

            <td>
              <p class="fw-normal mb-1">{{ $reserva->user ? $reserva->user->name : '' }}</p>
            </td>
            
            <td>
              <p class="fw-normal mb-1">
              {{ $reserva->user && $reserva->user->unidade ? $reserva->user->unidade->nome : '' }}</p>
            </td>
            <!-- <td class="text-center">
              <a href="{{ route('reservas.show', $reserva->id) }}" class="button-all button-bg-blue"><i class="fas fa-info-circle"></i></a>
              <a href="{{ route('reservas.edit', $reserva->id) }}" class="button-all button-bg-yellow"><i class="fa-regular fa-pen-to-square"></i></a>
              <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="button-all button-bg-red"><i class="fa-solid fa-trash"></i></button>
              </form>
            </td> -->
            <td class="text-center">
              <a href="{{ route('reservas.show', $reserva->id) }}" class="button-blue text-decoration-none">
                <i class="fas fa-info-circle"></i>
              </a>

              <a href="{{ route('reservas.edit', $reserva->id) }}" class="button-yellow text-decoration-none">
                <i class="fa-regular fa-pen-to-square"></i>
              </a>

              <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" style="display:inline;">
              @csrf
              @method('DELETE')
              <button type="button" class="button-red" data-bs-toggle="modal"
                data-bs-target="#confirmDeleteModal"
                onclick="setDeleteAction('{{ route('reservas.destroy', $reserva->id) }}')"><i
                class="fa-solid fa-trash"></i>
              </button>

              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal -->
<div class="modal fade" id="criarReservaModal" tabindex="-1" aria-labelledby="criarReservaModalLabel"
  aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="criarReservaModalLabel">Criar Reserva</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
          @csrf
          <input type="hidden" name="sala_fk" id="sala_fk">

          <div class="mb-3">
            <label for="data_reserva" class="form-label">Data</label>
            <input type="date" name="data_reserva" id="data_reserva" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="hora_inicio" class="form-label">Hora Início</label>
            <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
          </div>

          <div class="mb-3">
            <label for="hora_termino" class="form-label">Hora Término</label>
            <input type="time" name="hora_termino" id="hora_termino" class="form-control" required>
          </div>

          <!-- Campo oculto para unidade_fk -->
          {{-- <input type="hidden" name="unidade_fk" id="unidade_fk" value="{{ auth()->user()->unidade_id }}"> --}}

        </form>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="reservaForm" class="btn btn-primary">Salvar Reserva</button>
      </div>
    </div>
  </div>
</div>

<script>
  function selecionarSala(salaId) {
    document.getElementById('sala_fk').value = salaId;
  }
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  $(document).ready(function () {
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

  function carregarReservas(salaId) {
  $('#reservasContainer').html('<p class="text-center">🔄 Carregando reservas...</p>');

  $.ajax({
    url: '/reservas/dia/' + salaId,
    type: 'GET',
    success: function (reservas) {
      let html = '';

      if (reservas.length === 0) {
        html = '<p class="text-center">❌ Nenhuma reserva para hoje.</p>';
      } else {
        // Criar um container flexível para exibir em formato de grid
        html += '<div class="reservas-grid">';

        reservas.forEach(reserva => {
          const unidade = reserva.user?.unidade ?? 'Unidade Desconhecida';
          const usuario = reserva.user ? reserva.user.name : 'N/A';
          const horaInicio = reserva.data_inicio.split(' ')[1];
          const horaFim = reserva.data_fim.split(' ')[1];

          // Cada reserva será exibida como um card separado
          html += `<div class="reserva-card">
                    <h5 class="text-primary">🏢 Unidade: ${unidade}</h5>
                    <p><strong>🕒 Hora:</strong> ${horaInicio} - ${horaFim}</p>
                    <p><strong>👤 Reservado por:</strong> ${usuario}</p>
                  </div>`;
        });

        html += '</div>'; // Fechar o container grid
      }

      $('#reservasContainer').html(html);
    },
    error: function () {
      $('#reservasContainer').html('<p class="text-center text-danger">❌ Erro ao carregar reservas.</p>');
    }
  });
}
  
</script>
<div class="modal fade" id="verReservasModal" tabindex="-1" aria-labelledby="verReservasModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="verReservasModalLabel">Reservas do Dia</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="reservasContainer">
          <p class="text-center">Carregando reservas...</p>
        </div>
      </div>
    </div>
  </div>
</div>


@endsection

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmar Exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Tem certeza de que deseja excluir esta reserva? Essa ação não pode ser desfeita.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <form id="deleteForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Excluir</button>
        </form>
      </div>
    </div>
  </div>
</div>