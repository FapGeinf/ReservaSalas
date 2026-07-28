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
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="{{ asset('css/reservas/lista-reservas.css') }}">

<style>
  table.dataTable.no-footer {
    border-bottom: inherit !important;
  }
</style>

<div id="flash-messages" 
  data-success="{{ session('success') }}" 
  data-error="{{ session('error') }}">
</div>

<div class="container mt-5">
  <div class="tabela-main-page shadow-sm rounded-4 p-4 bg-white">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="title-meetings mb-0">Lista de Reuniões</h4>
        <p class="text-muted small mb-0">Visualize e gerencie todas as reservas do sistema</p>
      </div>
    </div>

    <div class="table-responsive">
      <table id="reservas" class="table table-striped align-middle my-3">
        <thead class="table-light">
          <tr>
            <th class="fs-13 text-center">ID</th>
            <th class="fs-13">Sala</th>
            <th class="fs-13 text-center">Período</th>
            <th class="fs-13">Reservado Por</th>
            <th class="fs-13">Unidade</th>
            <th class="fs-13 text-center">Tipo</th>
            <th class="fs-13 text-center" style="width: 50px;">Ações</th>
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
                  <button class="nav-buttons" type="button" data-bs-toggle="dropdown" style="padding: 4px 7px;">
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

                    <li><hr class="dropdown-divider"></li>

                    <li>
                      <button class="dropdown-item text-danger fs-13 py-2" 
                          data-bs-toggle="modal" 
                          data-bs-target="#confirmDeleteModal" 
                          onclick="setDeleteId({{ $reserva->id }})">
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

<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-top modal-sm">
    <div class="modal-content border-0 shadow">
      <div class="modal-body text-center p-4">
        <i class="bi bi-exclamation-triangle text-danger fs-1 mb-3"></i>
        <h6 class="fw-bold">Excluir Reserva?</h6>
        <p class="text-muted small">Esta ação não pode ser revertida.</p>
          
        <div class="d-flex justify-content-center gap-2 mt-4">
          <button class="btn btn-light fs-13 border px-3" data-bs-dismiss="modal">Voltar</button>
          <form id="deleteForm" method="POST">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger fs-13 px-3 shadow-sm">Confirmar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script src="{{ asset('js/reservas/table-reservas.js') }}"></script>
<script src="{{ asset('js/reservas/reserva-delete.js') }}"></script>
<script src="{{ asset('js/messages/alert.js') }}"></script>

@endsection