@extends('layouts.app')
@section('content')
@section('title') {{ 'Início' }} @endsection

<style>
  .modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5) !important;
  }

  .dataTables_wrapper .dataTables_info {
    line-height: 39px;
    padding-bottom: 0 !important;
}
</style>

<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">

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

<div class="pagina-container">
  <div class="salas-grid">

    @foreach($salas as $sala)
    @php
      $situacao = strtolower(trim($sala->situacao));
    @endphp
    
    <div class="sala-card">
      <div class="sala-card-conteudo" style="position: relative;">
        
        <img src="{{ asset('img/salas/' . $sala->imagem) }}" alt="Imagem {{ $sala->nome }}" class="imagem-sala">
    
        <div class="sala-info">

          <div class="titulo-sala">
            <span class="text-uppercase fw-semibold">
              {{ $sala->nome }}
            </span>
            
            @if($situacao === 'inativa')
            <span class="d-block s-manutencao fw-medium" style="font-size: 14px;">
              Sala em manutenção
            </span>
              
            @else
            <span class="d-block s-disponivel fw-medium" style="font-size: 14px;">
              Sala disponível
            </span>

            @endif
          </div>
    
          @if($situacao === 'ativa')
            <button class="botao-reservar" onclick="abrirModalCalendario({{ $sala->id }})">
              Reservar
            </button>
          @else
            <button class="botao-reservar" onclick="carregarReservas({{ $sala->id }})"
              data-bs-toggle="modal"
              data-bs-target="#verReservasModal" style="background-color: gray;">
              Ver Reservas
            </button>
          @endif
    
        </div>
      </div>
    </div>
    @endforeach
  </div>

  <!-- Calendário -->
  <div class="caixa-calendario">
    <div class="titulo-calendario">
      <span style="border-bottom: 2px solid #ccc; color: #333;">Calendário</span>
    </div>

    <div class="area-calendario">
      <div id="calendar" class="calendar-container" style="margin-top: 20px;"></div>
    </div>
  </div>
  
  @if (session('error'))
    <div class="alert alert-danger text-center mx-auto" style="max-width: 30%;">
      {{ session('error') }}
    </div>
  @endif

  <div class="tabela-main-page">
    <span>Reuniões Marcadas</span>
    <!-- Adicionar ID novamente se quiser ativar o DataTable -->
    <!-- <table id="reservas"> -->
    <table class="table-reservas" id="reservas">
      <thead>
        <tr>
            <th>
                <label class="text-light">Id</label>
            </th>

            <th>
                <label class="text-light">Sala</label>
            </th>

            <th>
                <label class="text-light">Hora Início</label>
            </th>

            <th>
                <label class="text-light">Hora Término</label>
            </th>

            <th>
                <label class="text-light">Reservado Por</label>
            </th>

            <th>
                <label class="text-light">Unidade</label>
            </th>

            <th>
                <label class="text-light">Opções</label>
            </th>
        </tr>
      </thead>

      <tbody>
        @foreach($reservas as $reserva)
        <tr>
          <td data-label="Id">
            {{ $reserva->id }}
          </td>

          <td data-label="Sala">
            <div class="mt-1">
              <p class="mb-1 text-uppercase">
              {{ $reserva->sala ? $reserva->sala->nome : 'Sala não encontrada' }}</p>
            </div>
          </td>

          <td data-label="Hora Início">
            {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y | H:i') }}
          </td>

          <td data-label="Hora Término">
            {{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y | H:i') }}
          </td>

          <td data-label="Reservado Por">
            {{ $reserva->user ? $reserva->user->name : '' }}
          </td>

          <td data-label="Unidade">
            {{ $reserva->user && $reserva->user->unidade ? $reserva->user->unidade->nome : '' }}
          </td>

          <td data-label="Opções">
            <div class="dropdown-custom">
              <button class="btn-dropdown" onclick="toggleDropdown(this)">
                <i class="fas fa-ellipsis-v"></i>
              </button>

              <div class="dropdown-menu-custom">
                <button class="btn-dropdown dropdown-item">
                  <a href="{{ route('reservas.show', $reserva->id) }}" class="text-decoration-none text-pattern">
                    Detalhes
                  </a>
                </button>

                <button class="btn-dropdown dropdown-item">
                  <a href="{{ route('reservas.edit', $reserva->id) }}" class="text-decoration-none text-pattern">
                    Editar
                  </a>
                </button>

                <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" class="m-0">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn-dropdown dropdown-item text-danger"
                    data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                    onclick="setDeleteAction('{{ route('reservas.destroy', $reserva->id) }}')">
                    Excluir
                  </button>
                </form>
              </div>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>

<!-- Modal de Reserva -->
<div class="modal fade" id="modalReserva" tabindex="-1" aria-labelledby="modalReservaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="modalReservaLabel">Nova Reserva</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
      </div>

      <div class="modal-body">
        <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
          @csrf
          <input type="hidden" name="data_reserva" id="data_reserva">
          <input type="hidden" name="sala_fk" id="sala_fk">

          <div class="mb-3">
            <label for="sala_fk" class="fw-bold">Sala:</label>
            <select name="sala_fk" id="sala_fk" class="input-custom" required>
              <option value="">Selecione uma sala</option>
              @foreach($salas as $sala)
                <option value="{{ $sala->id }}">{{ $sala->nome }}</option>
              @endforeach
            </select>
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

<!-- Modal de Detalhes da Reserva -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
  <div id="toastReserva" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
      <strong class="me-auto">Detalhes da Reserva</strong>
      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fechar"></button>
    </div>

    <div class="toast-body" id="toastBodyReserva"></div>

  </div>
</div>

<script>
function toggleDropdown(button) {
  const dropdown = button.parentElement;
  dropdown.classList.toggle("open");

  // Fecha o dropdown ao clicar fora dele
  document.addEventListener("click", function closeDropdown(event) {
    if (!dropdown.contains(event.target)) {
      dropdown.classList.remove("open");
      document.removeEventListener("click", closeDropdown);
    }
  });
}
</script>

<!-- Adicione a biblioteca SweetAlert2 no <head> -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
  
      var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'timeGridWeek',
          locale: 'pt-br', // Idioma
          events: '/eventos', // URL para buscar os eventos
          selectable: true,
          editable: false,
          eventDisplay: 'block',
  
          // Campo que mostrar as reservas no calendar do evento
          eventContent: function(arg) {
              const horaInicio = arg.event.extendedProps.hora_inicio || '';
              const horaFim = arg.event.extendedProps.hora_fim || '';
              const responsavel = arg.event.extendedProps.responsavel || '';
              const nomeSala = arg.event.title || '';
  
              let innerHtml = `
          <div style="font-size: 0.95em;">
              <strong>${nomeSala}</strong><br>
              ${horaInicio} - ${horaFim}<br>
              ${responsavel}
          </div>
      `;
              return {
                  html: innerHtml
              };
          },
  
          // Configuração da barra de ferramentas
          headerToolbar: {
              left: 'prev,next today',
              center: 'title',
              right: 'dayGridMonth,timeGridWeek,listWeek'
          },
  
          // Personaliza o formato do título
          // titleFormat: {
          //     month: 'long', // Nome completo do mês
          //     year: 'numeric' // Ano
          // },
  
          // Evento ao clicar em uma data
          dateClick: function(info) {
              // Define a data no campo do modal
              document.getElementById('data_reserva').value = info.dateStr;
  
              // Abre o modal de reserva
              var modalReserva = new bootstrap.Modal(document.getElementById('modalReserva'));
              modalReserva.show();
  
              // Define o foco no campo de seleção de sala
              setTimeout(function() {
                  document.getElementById('sala_fk').focus();
              }, 500);
          },
  
          // Evento ao clicar em um evento existente
          eventClick: function(info) {
              Swal.fire({
                  title: 'Detalhes da Reserva',
                  html: `
                      <strong>Sala:</strong> ${info.event.title}<br>
                      <strong>Unidade:</strong> ${info.event.extendedProps.unidade}<br>
                      <strong>Horário:</strong> ${info.event.extendedProps.hora_inicio} - ${info.event.extendedProps.hora_fim}<br>
                      <strong>Responsável:</strong> ${info.event.extendedProps.responsavel}
                  `,
                  confirmButtonText: 'Fechar'
              });
          }
      });
  
      calendar.render();
  });
  </script>
  


<script>
// Função para abrir o modal do calendário e selecionar uma sala
function abrirModalCalendario(salaId) {
    console.log("Sala selecionada:", salaId);
    $('#sala_fk').val(salaId); // Define a sala no formulário
    $('#modalCalendario').modal('show');
}


$(document).ready(function() {
    $('#reservaForm').submit(function(e) {
        e.preventDefault();

        // Mostra o loader no botão
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status"></span> Salvando...');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    // Fecha o modal de reserva
                    $('#modalReserva').modal('hide');

                    // Mostra mensagem de sucesso
                    Swal.fire({
                        title: 'Sucesso!',
                        text: 'Reserva realizada com sucesso!',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        // Redireciona para a home após clicar em OK
                        window.location.href = "{{ route('home') }}";
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Erro!',
                    text: xhr.responseJSON?.message || 'Erro ao realizar reserva',
                    icon: 'error'
                });
            },
            complete: function() {
                // Restaura o botão
                submitBtn.prop('disabled', false).html('Salvar Reserva');
            }
        });
    });
});

// Verificação em tempo real
$('#hora_inicio, #hora_termino').change(function() {
    verificarDisponibilidade();
});


function verificarDisponibilidade() {
    const salaId = $('#sala_fk').val();
    const data = $('#data_reserva').val();
    const horaInicio = $('#hora_inicio').val();
    const horaTermino = $('#hora_termino').val();

    if (!salaId || !data || !horaInicio || !horaTermino) return;

    $.ajax({
        url: '/verificar-disponibilidade',
        type: 'POST',
        data: {
            sala_id: salaId,
            data_reserva: data,
            hora_inicio: horaInicio,
            hora_termino: horaTermino,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.disponivel) {
                $('#disponibilidade-status').html('<span class="text-success">Horário disponível</span>');
                $('.btn-submit').prop('disabled', false);
            } else {
                $('#disponibilidade-status').html('<span class="text-danger">' + response.mensagem +
                    '</span>');
                $('.btn-submit').prop('disabled', true);
            }
        }
    });
}
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#reservas').DataTable({
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
        scrollCollapse: true,
        paging: true,         // <<< Desativa a paginação
        searching: false,      // <<< Remove a barra de pesquisa
        lengthChange: false    // <<< Remove o select de quantidade de registros
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

    $('#reservasContainer').html(
        '<p class="text-center"><i class="fa-regular fa-spinner" style="color: #2a64e7;"></i> Carregando reservas...</p>'
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
                '<p class="text-center"><i class="fa-solid fa-x me-1" style="color: #b22720;"></i> Erro ao carregar reservas.</p>'
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



<!-- Modal para Ver Reservas -->
<div class="modal fade" id="verReservasModal" tabindex="-1" aria-labelledby="verReservasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content custom-modal">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="verReservasModalLabel">Reservas</h5>
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
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
    aria-hidden="true">

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

@if (session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Sucesso!',
            text: '{{ session('success') }}',
            icon: 'success',
            confirmButtonText: 'Fechar'
        });
    });
</script>
@endif