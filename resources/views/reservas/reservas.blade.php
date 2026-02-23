@extends('layouts.app')
@section('title') {{ 'Lista de Reuniões' }} @endsection
@section('content')

<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-borders.css') }}">
<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/calendar-page.css') }}">

<link rel="stylesheet" href="{{ asset('css/swal-alert.css') }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container mt-5">
  <div class="tabela-main-page">
    <div class="text-center fw-semibold mb-4">
      <span class="title-meetings">Lista de Reuniões</span>
    </div>

    <div class="table-responsive">
      <table id="reservas" class="table table-striped border-bottom-0 my-3">
        <thead>
          <tr>
            <th class="fs-13">Id</th>
            <th class="fs-13">Sala</th>
            <th class="fs-13">Hora Início</th>
            <th class="fs-13 text-nowrap">Hora Término</th>
            <th class="fs-13">Reservado Por</th>
            <th class="fs-13">Unidade</th>
            <th class="fs-13 text-nowrap">Tipo de Reserva</th>
            <th class="fs-13">Opções</th>
          </tr>
        </thead>

        <tbody>
          @foreach($reservas as $reserva)
            <tr>
              <td data-th="Id" class="fs-13">{{ $reserva->id }}</td>
              <td data-th="Sala" class="fs-13">
                <div class="mt-1">
                  <p class="mb-1 text-capitalize">
                    {{ $reserva->sala ? $reserva->sala->nome : 'Sala não encontrada' }}
                  </p>
                </div>
              </td>

              <td data-th="Hora Início" class="fs-13">{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y \à\s H:i') }}</td>
              <td data-th="Hora Término" class="fs-13">{{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y \à\s H:i') }}</td>

              <td data-th="Reservado Por" class="fs-13">
                @php
                  $nome = $reserva->user ? $reserva->user->name : '';

                  $nomeFormatado = collect(explode(' ', $nome))
                    ->map(function ($parte) {
                      $lower = mb_strtolower($parte, 'UTF-8');
                      return in_array($lower, ['da', 'do', 'de'], true) ? $lower : $parte;
                    })
                    ->implode(' ');
                @endphp

                {{ $nomeFormatado }}
              </td>

              <td data-th="Unidade" class="fs-13">{{ $reserva->unidade?->nome ?? '—' }}</td>
              <!-- <td>{{ ucfirst($reserva->finalidade) ?? 'N/A' }}</td> -->
              <td data-th="Tipo Reserva" class="fs-13">{{ ucfirst($reserva->finalidade) ?? '—' }}</td>
              <td data-th="Opções" class="fs-13">
                <div class="dropdown">
                  <button class="button-garden" type="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                    <i class="bi bi-three-dots-vertical"></i>
                  </button>

                  <ul class="dropdown-menu dropdown-menu-dark">
                    <li>
                      <a href="{{ route('reservas.show', $reserva->id) }}"
                        class="dropdown-item text-decoration-none fs-13">
                        <i class="bi bi-info-circle me-1"></i>
                        Detalhes
                      </a>
                    </li>

                    <li>
                      <a href="{{ route('reservas.edit', $reserva->id) }}"
                        class="dropdown-item text-decoration-none fs-13">
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
</div>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
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
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title fw-medium">Confirmar Exclusão</h6>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body fs-14">
        Tem certeza que deseja excluir esta reserva?
      </div>

      <div class="modal-footer bg-modal-footer">
        <button type="button" class="button-grey" data-bs-dismiss="modal">
          <i class="bi bi-x-lg"></i>
          Cancelar
        </button>

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
        position: 'top',
        title: 'Sucesso!',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonText: 'Fechar',
        customClass: {
          confirmButton: 'button-green'
        }
      });
    });
  </script>
@endif

@if (session('error'))
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      Swal.fire({
        position: 'top',
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