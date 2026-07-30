@extends('layouts.app')
@section('title', 'Lista de Reuniões')
@section('content')

  <link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
  <link rel="stylesheet" href="{{ asset('css/table-borders.css') }}">
  <link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
  <link rel="stylesheet" href="{{ asset('css/table-main-page.css') }}">
  <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
  <link rel="stylesheet" href="{{ asset('css/calendar-page.css') }}">
  <link rel="stylesheet" href="{{ asset('css/swal-alert.css') }}">
  <link rel="stylesheet" href="{{ asset('js/scripts/datatables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/reservas/lista-reservas.css') }}">

  <style>
    table.dataTable.no-footer {
      border-bottom: inherit !important;
    }
  </style>

  <div id="flash-messages" data-success="{{ session('success') }}" data-error="{{ session('error') }}">
  </div>

  <div class="container mt-5">
    <div class="tabela-main-page shadow-sm rounded-4 p-4 bg-white">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h4 class="title-meetings mb-0">Lista de Reuniões</h4>
          <p class="text-muted small mb-0">Visualize e gerencie todas as reservas do sistema</p>
        </div>
        <div>
          <button type="button" class="button-grey d-flex align-items-center gap-2" data-bs-toggle="modal"
            data-bs-target="#reportModal">
            <i class="bi bi-file-earmark-pdf-fill"></i>
            Gerar Relatório
          </button>
        </div>
      </div>

      <div>
        <table id="reservas" class="table table-striped table-hover my-3">
          <thead class="table-light">
            <tr>
              <th class="fs-13 bg-th-table">ID</th>
              <th class="fs-13 bg-th-table">Sala</th>
              <th class="fs-13 bg-th-table">Período</th>
              <th class="fs-13 bg-th-table">Reservado Por</th>
              <th class="fs-13 bg-th-table">Unidade</th>
              <th class="fs-13 bg-th-table">Tipo</th>
              <th class="fs-13 bg-th-table">Ações</th>
            </tr>
          </thead>

          <tbody>
            @foreach($reservas as $reserva)
              <tr>
                <td data-th="Reserva" class="fs-13">#{{ $reserva->id }}</td>
                <td data-th="Sala" class="fs-13 text-capitalize">{{ $reserva->sala->nome ?? 'Sala removida' }}</td>

                <td data-th="Período" class="fs-13">
                  <div>
                    <div>{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y') }}</div>
                    <small class="text-muted">
                      {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('H:i') }}
                      às
                      {{ \Carbon\Carbon::parse($reserva->data_fim)->format('H:i') }}
                    </small>
                  </div>
                </td>

                <td data-th="Reservado Por" class="fs-13">
                  {{ ucwords(mb_strtolower($reserva->user->name ?? 'Usuário desconhecido')) }}
                </td>

                <td data-th="Unidade" class="fs-13">
                  <span>{{ $reserva->unidade->nome ?? '—' }}</span>
                </td>

                <td data-th="Tipo" class="fs-13">
                  <span class="badge-tipo">
                    {{ ucfirst($reserva->finalidade ?? 'Reserva') }}
                  </span>
                </td>

                <td data-th="Ações" class="fs-13">
                  <div class="dropdown">
                    <button class="button-grey" type="button" data-bs-toggle="dropdown" style="padding: 0px 7px;">
                      <i class="bi bi-three-dots-vertical"></i>
                    </button>

                    <ul class="dropdown-menu shadow">
                      <li>
                        <a href="{{ route('reservas.show', $reserva->id) }}" class="dropdown-item fs-13 py-2">
                          <i class="bi bi-info-circle me-1"></i>
                          Detalhes
                        </a>
                      </li>

                      <li>
                        <a href="{{ route('reservas.edit', $reserva->id) }}" class="dropdown-item fs-13 py-2">
                          <i class="bi bi-pencil-square me-1"></i>
                          Editar
                        </a>
                      </li>

                      <li>
                        <hr class="dropdown-divider">
                      </li>

                      <li>
                        <button class="dropdown-item text-danger fs-13 py-2" data-bs-toggle="modal"
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

  <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="reportModalLabel">Gerar Relatório Mensal</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>

        <form action="{{ route('reservas.relatorio.mensal') }}" method="GET" target="_blank">
          <div class="modal-body fs-14">
            <p class="text-muted small">Selecione o período das reservas que deseja exportar em PDF.</p>

            <div class="row g-3">
              <div class="col-6">
                <label for="mes" class="fw-semibold">Mês:</label>
                <select name="mes" id="mes" class="form-select fs-13">
                  @php
                    $meses = [
                      1 => 'Janeiro',
                      2 => 'Fevereiro',
                      3 => 'Março',
                      4 => 'Abril',
                      5 => 'Maio',
                      6 => 'Junho',
                      7 => 'Julho',
                      8 => 'Agosto',
                      9 => 'Setembro',
                      10 => 'Outubro',
                      11 => 'Novembro',
                      12 => 'Dezembro'
                    ];
                  @endphp

                  @foreach ($meses as $numero => $nome)
                    <option value="{{ $numero }}" {{ $numero == now()->month ? 'selected' : '' }}>
                      {{ $nome }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="col-6">
                <label for="ano" class="fw-semibold">Ano:</label>
                <select name="ano" id="ano" class="form-select fs-13">
                  @for ($y = now()->year; $y >= now()->year - 5; $y--)
                    <option value="{{ $y }}" {{ $y == now()->year ? 'selected' : '' }}>
                      {{ $y }}
                    </option>
                  @endfor
                </select>
              </div>
            </div>
          </div>

          <div class="modal-footer py-2 bg-modal-footer">
            <button type="button" class="button-grey" data-bs-dismiss="modal">
              <i class="bi bi-x-lg me-1"></i>
              Cancelar
            </button>
            <button type="submit" class="button-blue">
              <i class="bi bi-download me-1"></i>
              Baixar PDF
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal de Confirmação de Exclusão -->
  <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title">Confirmação de Exclusão</h6>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
        </div>

        <div class="modal-body fs-14">
          Tem certeza que deseja excluir esta reunião? Esta ação não pode ser desfeita.
        </div>

        <div class="modal-footer py-2 bg-modal-footer">
          <button type="button" class="button-grey" data-bs-dismiss="modal">
            <i class="bi bi-x-lg me-1"></i>
            Cancelar
          </button>

          <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="button-red">
              <i class="bi bi-trash me-1"></i>
              Excluir
            </button>
          </form>
        </div>

      </div>
    </div>
  </div>

  <script src="{{ asset('js/scripts/jquery-3.7.1.min.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="{{ asset('js/scripts/datatables.min.js') }}"></script>

  <script src="{{ asset('js/reservas/table-reservas.js') }}"></script>
  <script src="{{ asset('js/reservas/reserva-delete.js') }}"></script>
  <script src="{{ asset('js/messages/alert.js') }}"></script>

@endsection