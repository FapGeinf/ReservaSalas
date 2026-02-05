@extends('layouts.app')
@section('title') {{ 'Lista de Reuniões' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/calendar-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/dropdown.css') }}">
<link rel="stylesheet" href="{{ asset('css/swal-alert.css') }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<script src="js/custom.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-5">
  <div class="tabela-main-page">
    <div class="text-center fw-bold mb-4">
      <span class="title-meetings text-uppercase">Lista de Reuniões</span>
    </div>

    <table class="table-reservas table-striped" id="reservas">
      <thead>
        <tr>
          <th><label class="text-light">Id</label></th>
          <th><label class="text-light">Sala</label></th>
          <th><label class="text-light">Hora Início</label></th>
          <th><label class="text-light">Hora Término</label></th>
          <th><label class="text-light">Reservado Por</label></th>
          <th><label class="text-light">Unidade</label></th>
          <th data-label="Tipo Reserva"><label class="text-light">Tipo de Reserva</label></th>
          <th><label class="text-light">Opções</label></th>
        </tr>
      </thead>

      <tbody>
        @foreach($reservas as $reserva)
          <tr>
            <td data-label="Id" class="fs-13">{{ $reserva->id }}</td>
            <td data-label="Sala" class="fs-13">
              <div class="mt-1">
                <p class="mb-1 text-uppercase">
                  {{ $reserva->sala ? $reserva->sala->nome : 'Sala não encontrada' }}
                </p>
              </div>
            </td>

            <td data-label="Hora Início" class="fs-13">{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y \à\s H:i') }}</td>
            <td data-label="Hora Término" class="fs-13">{{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y \à\s H:i') }}</td>
            <td data-label="Reservado Por" class="fs-13">{{ $reserva->user ? $reserva->user->name : '' }}</td>
            <td data-label="Unidade" class="fs-13">{{ $reserva->user && $reserva->user->unidade ? $reserva->user->unidade->nome : '' }}</td>
            <!-- <td>{{ ucfirst($reserva->finalidade) ?? 'N/A' }}</td> -->
            <td data-label="Tipo Reserva" class="fs-13">{{ ucfirst($reserva->finalidade) ?? '—' }}</td>
            <td data-label="Opções" class="fs-13">
              <div class="dropdown">
                <button class="custom-actions-btn" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-three-dots-vertical"></i>
                </button>

                <ul class="dropdown-menu">
                  <li>
                    <a href="{{ route('reservas.show', $reserva->id) }}"
                      class="dropdown-item text-decoration-none text-pattern fs-13">
                      <i class="bi bi-info-circle me-1"></i>
                      Detalhes
                    </a>
                  </li>

                  <li>
                    <a href="{{ route('reservas.edit', $reserva->id) }}"
                      class="dropdown-item text-decoration-none text-pattern fs-13">
                      <i class="bi bi-pencil-square me-1"></i>
                      Editar
                    </a>
                  </li>

                  <li>
                    <button class="dropdown-item text-danger fs-13" data-bs-toggle="modal"
                      data-bs-target="#confirmDeleteModal" onclick="setDeleteId({{ $reserva->id }})">
                      <i class="bi bi-trash me-1"></i>
                      Excluir
                    </button>
                  </li>
                </ul>
              </div>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
  // Definir o plugin de ordenação personalizada ANTES de usar
  jQuery.extend(jQuery.fn.dataTableExt.oSort, {
    "date-euro-pre": function(a) {
      if ($.trim(a) !== '') {
        var parts = a.split(' | ');
        var dateParts = parts[0].split('/');
        var timeParts = parts[1].split(':');

        return new Date(
          dateParts[2], // ano
          dateParts[1] - 1, // mês (0-11)
          dateParts[0], // dia
          timeParts[0], // horas
          timeParts[1] // minutos
        ).getTime();
      }
      return 0;
    },
    "date-euro-asc": function(a, b) {
      return a - b;
    },
    "date-euro-desc": function(a, b) {
      return b - a;
    }
  });

  $(document).ready(function() {
    // Inicialização única da DataTable
    var table = $('#reservas').DataTable({
      order: [
        [0, 'desc']
      ], // Ordena pela coluna de Hora Início (índice 2)
      columnDefs: [{
        targets: [2, 3], // Colunas de data/hora
        type: 'date-euro' // Usa nosso tipo de ordenação personalizado
      }],

      language: {
        url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/pt-BR.json',
        search: "Procurar:",
        lengthMenu: "Mostrar _MENU_ registros por página",
        info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
        infoEmpty: "Nenhum registro disponível",
        infoFiltered: "(filtrado de _MAX_ registros totais)",
        zeroRecords: "Nenhum registro encontrado",
        paginate: {
          first: "Primeira",
          last: "Última",
          next: "Próximo",
          previous: "Anterior"
          }
        },
      scrollCollapse: true,
      responsive: true,
      paging: true,
      searching: true,
      lengthChange: true
    });

    // Função para o modal de confirmação de exclusão
    function setDeleteAction(action) {
      $('#deleteForm').attr('action', action);
    }

    // Exemplo de como você poderia usar (adaptar conforme necessário)
    $('.btn-delete').on('click', function() {
      var deleteUrl = $(this).data('url');
      setDeleteAction(deleteUrl);
    });
  });

  function selecionarSala(salaId) {
    console.log('Sala selecionada:', salaId); // Depuração
    document.getElementById('sala_fk').value = salaId;
  }

  function carregarReservas(salaId) {
    const dataSelecionada = document.getElementById('dataSelecionada').value;

    $('#reservasContainer').html(
      '<p class="text-center"><i class="bi bi-arrow-repeat" style="color: #2a64e7;"></i> Carregando reservas...</p>'
    );

    $.ajax({
      url: '/reservas/sala/' + salaId, // Rota para buscar as reservas da sala
      type: 'GET',
      data: {
        data: dataSelecionada
      },

      success: function(reservas) {
        let html = '';

        if (reservas.length === 0) {
          html = '<p class="reserva-vazia">Nenhuma reserva para esta data.</p>';
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

      error: function() {
        $('#reservasContainer').html(
          '<p class="text-center"><i class="bi bi-exclamation-circle-fill me-1" style="color: #b22720;"></i> Erro ao carregar reservas.</p>'
        );
      }
    });
  }

  $(document).ready(function() {
    $('#dataSelecionada').on('change', function() {
      const salaId = $('#verReservasModal').data('sala-id');
      carregarReservas(salaId);
    });

    $('#verReservasModal').on('show.bs.modal', function(event) {
      const button = $(event.relatedTarget);
      const salaId = button.data('sala-id');
      $('#verReservasModal').data('sala-id', salaId);

      const hoje = new Date().toISOString().split('T')[0];
      $('#dataSelecionada').val(hoje);

      carregarReservas(salaId);
    });
  });
</script>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fw-bold">Confirmar Exclusão</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body fs-14">
        Tem certeza que deseja excluir esta reserva?
      </div>

      <div class="modal-footer" style="background-color: #f1f1f1; border-top: 0px;">
        <button type="button" class="button-grey" data-bs-dismiss="modal">Cancelar</button>
        <form id="deleteForm" method="POST">
          @csrf
          @method('DELETE')
          <button type="submit" class="button-red">
            <i class="bi bi-trash me-1"></i>
            Excluir
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  function setDeleteId(reservaId) {
    console.log("Exclusão da reserva ID:", reservaId); // Depuração

    // Define o ID da reserva para exclusão
    $('#deleteForm').attr('action', `/reservas/${reservaId}`);
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        title: 'Sucesso!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'Fechar',
        customClass: {
          confirmButton: 'button-red'
        }
      });
    });
  </script>
@endif

@if (session('error'))
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
      title: 'Ops!',
      text: "{{ session('error') }}",
      icon: 'error',
      confirmButtonText: 'Fechar',
      customClass: {
        confirmButton: 'button-red'
      }
    });
  });
  </script>
@endif