@extends('layouts.app')
@section('content')

@section('title') {{ 'Início' }} @endsection


<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/responsive-table.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">

<style>
.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5) !important;
    /* Mais claro que o padrão (0.5) */
}
</style>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

<script src="js/custom.js"></script>


<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- FullCalendar CSS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">

<!-- FullCalendar JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>



<div class="">
    <div class="p-30 mx-auto mt-5 divCards">

        <div class="row">
            @foreach($salas as $index => $sala)

            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                <div class="card border position-relative">

                    <!-- Imagem da Sala com Sobreposição -->
                    <div class="bg__card_pattern bg__card_pattern_footer text-light text-center mb-0 position-relative">
                        <img class="fit-image" src="{{ asset('img/salas/' . $sala->imagem) }}" alt="{{ $sala->nome }}">

                        <!-- Sobreposição para Sala Inativa -->
                        @php
                        $situacao = strtolower(trim($sala->situacao)); // Normaliza o valor de 'situacao'
                        @endphp

                        @if($situacao === 'inativa')
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                            style="background-color: rgba(0, 0, 0, 0.7);">
                            <span class="text-white fw-bold fs-6">Sala em Manutenção</span>
                        </div>
                        @endif
                    </div>

                    <!-- Corpo do Card -->
                    <div class="card-body card-fofinho">
                        <div class="title-teste text-center d-flex flex-column"
                            style="margin-bottom: 1rem; margin-top: .5rem;">
                            <span>Local</span>
                            <h3 class="fw-bold text-uppercase" style="word-wrap:normal;">{{ $sala->nome }}</h3>
                            <span class="mt-2">Descrição</span>
                            <span class="" style="color:rgb(134, 132, 132); font-size: 14px;">
                                <p class="card-text">{{ $sala->descricao }}</p>
                            </span>
                        </div>
                    </div>

                    <!-- Botões de Reserva e Ver Reservas -->
                    <div class="card-body card-fofinho" style="background-color: #f1f1f1; /* padding: 10px 30px; */">
                        <div class="title-teste text-center d-flex flex-column">
                            <div class="d-flex justify-content-center gap-3 py-2">

                                <button type="button"
                                    class="button-green-index {{ $situacao === 'inativa' ? 'disabled d-none' : '' }}"
                                    onclick="{{ $situacao === 'ativa' ? 'abrirModalCalendario(' . $sala->id . ')' : 'return false;' }}"
                                    {{ $situacao === 'inativa' ? 'disabled' : '' }}>
                                    Reservar
                                </button>


                                <!-- <button type="button" class="button-blue" data-bs-toggle="modal"
                                    data-bs-target="#verReservasModal" data-sala-id="{{ $sala->id }}"
                                    onclick="carregarReservas({{ $sala->id }})" style="font-size: 15px;">
                                    Ver Reservas
                                </button> -->

                                @if ($situacao === 'inativa')
                                <button type="button" class="button-blue" data-bs-toggle="modal"
                                    data-bs-target="#verReservasModal" data-sala-id="{{ $sala->id }}"
                                    onclick="carregarReservas({{ $sala->id }})" style="font-size: 15px;">
                                    Ver Reservas
                                </button>
                                @endif
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
    <div class="table-container">
        <table id="reservas">
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
                        @if($reserva->sala && $reserva->sala->imagem)
                        <img src="{{ asset('img/salas/' . $reserva->sala->imagem) }}" alt=""
                            style="width: 45px; height: 45px" class="square img-table" />

                        @else
                        <p>Imagem não disponível</p>
                        @endif

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
                                    <a href="{{ route('reservas.show', $reserva->id) }}"
                                        class="text-decoration-none text-pattern">
                                        {{-- <i class="fas fa-info-circle"></i> --}}
                                        Detalhes
                                    </a>
                                </button>

                                <button class="btn-dropdown dropdown-item">
                                    <a href="{{ route('reservas.edit', $reserva->id) }}"
                                        class="text-decoration-none text-pattern">
                                        {{-- <i class="fa-regular fa-pen-to-square"></i> --}}
                                        Editar
                                    </a>
                                </button>


                                <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-dropdown dropdown-item text-danger"
                                        data-bs-toggle="modal" data-bs-target="#confirmDeleteModal"
                                        onclick="setDeleteAction('{{ route('reservas.destroy', $reserva->id) }}')">
                                        {{-- <i class="fa-solid fa-trash"></i> --}}
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

<!-- Modal -->
<!-- <div class="modal fade" id="modalReserva" tabindex="-1" aria-labelledby="modalReservaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReservaLabel">Nova Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
                    @csrf
                    <input type="hidden" name="sala_fk" id="sala_fk">

                    <div class="mb-3">
                        <label for="data_reserva" class="fw-bold">Data:</label>
                        <input type="date" name="data_reserva" id="data_reserva" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="hora_inicio" class="fw-bold">Hora de Início:</label>
                        <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="hora_termino" class="fw-bold">Hora de Término:</label>
                        <input type="time" name="hora_termino" id="hora_termino" class="form-control" required>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="reservaForm" class="btn btn-primary">Salvar Reserva</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div> -->




<div class="modal fade" id="modalReserva" tabindex="-1" aria-labelledby="modalReservaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalReservaLabel">Nova Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
                    @csrf
                    <input type="hidden" name="sala_fk" id="sala_fk">

                    <div class="mb-3">
                        <label for="data_reserva" class="fw-bold">Data:</label>
                        <input type="date" name="data_reserva" id="data_reserva" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="hora_inicio" class="fw-bold">Hora de Início:</label>
                        <input type="time" name="hora_inicio" id="hora_inicio" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="hora_termino" class="fw-bold">Hora de Término:</label>
                        <input type="time" name="hora_termino" id="hora_termino" class="form-control" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="reservaForm" class="btn btn-primary">Salvar Reserva</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>




<!-- Modal do Calendário -->
<div class="modal fade" id="modalCalendario" tabindex="-1" aria-labelledby="modalCalendarioLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <!-- Adicionado modal-xl para aumentar o tamanho -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCalendarioLabel">Escolha uma Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div id="calendar"></div> <!-- O calendário ficará maior agora -->
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



<script>
// document.addEventListener('DOMContentLoaded', function() {
//     var calendarEl = document.getElementById('calendar');

//     var calendar = new FullCalendar.Calendar(calendarEl, {
//         initialView: 'dayGridMonth',
//         locale: 'pt-br',
//         events: '/eventos',
//         selectable: true,
//         editable: false,
//         eventDisplay: 'block',

//         // Personalize a aparência dos eventos
//         eventContent: function(arg) {
//             // Cria um elemento personalizado para o evento
//             var eventEl = document.createElement('div');
//             eventEl.className = 'fc-event-content';

//             // Adiciona as informações que você quer mostrar
//             eventEl.innerHTML = `
//                 <div class="fc-event-title">
//                     <strong>${arg.event.title}</strong>
//                 </div>
//                 <div class="fc-event-details">
//                     <small>${arg.event.extendedProps.hora_inicio} - ${arg.event.extendedProps.hora_fim}</small><br>
//                     <small>${arg.event.extendedProps.unidade}</small>
//                 </div>
//             `;

//             return {
//                 domNodes: [eventEl]
//             };
//         },

//         // Evento ao clicar em uma data
//         dateClick: function(info) {
//             var dataFormatada = info.dateStr;
//             document.getElementById('data_reserva').value = dataFormatada;

//             var modalCalendario = bootstrap.Modal.getInstance(document.getElementById(
//                 'modalCalendario'));
//             modalCalendario.hide();

//             var modalReserva = new bootstrap.Modal(document.getElementById('modalReserva'));
//             modalReserva.show();

//             setTimeout(function() {
//                 document.getElementById('hora_inicio').focus();
//             }, 500);
//         },

//         // Evento ao clicar em um evento existente
//         eventClick: function(info) {
//             Swal.fire({
//                 title: 'Detalhes da Reserva',
//                 html: `
//                     <strong>Sala:</strong> ${info.event.title}<br>
//                     <strong>Unidade:</strong> ${info.event.extendedProps.unidade}<br>
//                     <strong>Horário:</strong> ${info.event.extendedProps.hora_inicio} - ${info.event.extendedProps.hora_fim}<br>
//                     <strong>Responsável:</strong> ${info.event.extendedProps.responsavel}
//                 `,
//                 confirmButtonText: 'Fechar'
//             });
//         }
//     });

//     calendar.render();
// });



document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // Visualização inicial (mês)
        locale: 'pt-br', // Idioma
        events: '/eventos', // URL para buscar os eventos
        selectable: true,
        editable: false,
        eventDisplay: 'block',

        // Configuração da barra de ferramentas
        headerToolbar: {
            left: 'prev,next today', // Botões de navegação
            center: 'title', // Título do calendário
            right: 'dayGridMonth,timeGridWeek,listWeek' // Modos de visualização
        },

        // Personalize a aparência dos eventos
        eventContent: function(arg) {
            var eventEl = document.createElement('div');
            eventEl.className = 'fc-event-content';

            eventEl.innerHTML = `
                <div class="fc-event-title">
                    <strong>${arg.event.title}</strong>
                </div>
                <div class="fc-event-details">
                    <small>${arg.event.extendedProps.hora_inicio} - ${arg.event.extendedProps.hora_fim}</small><br>
                    <small>${arg.event.extendedProps.unidade}</small>
                </div>
            `;

            return {
                domNodes: [eventEl]
            };
        },

        // Evento ao clicar em uma data
        dateClick: function(info) {
            var dataFormatada = info.dateStr;
            document.getElementById('data_reserva').value = dataFormatada;

            var modalCalendario = bootstrap.Modal.getInstance(document.getElementById('modalCalendario'));
            modalCalendario.hide();

            var modalReserva = new bootstrap.Modal(document.getElementById('modalReserva'));
            modalReserva.show();

            setTimeout(function() {
                document.getElementById('hora_inicio').focus();
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
        // scrollY: '200px',
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


// function carregarReservas(salaId) {
//     const dataSelecionada = document.getElementById('dataSelecionada').value;

//     $('#reservasContainer').html(
//         '<p class="text-center"><i class="fa-regular fa-spinner" style="color: #2a64e7;"></i> Carregando reservas...</p>'
//     );

//     $.ajax({
//         url: '/reservas/sala/' + salaId,
//         type: 'GET',
//         data: {
//             data: dataSelecionada
//         },
//         success: function(reservas) {
//             if (reservas.length === 0) {
//                 $('#reservasContainer').html('<p class="reserva-vazia">Nenhuma reserva para esta data.</p>');
//             } else {
//                 // Limpa os eventos existentes no calendário
//                 const calendar = FullCalendar.getCalendar('calendar');
//                 calendar.removeAllEvents();

//                 // Adiciona os eventos ao calendário
//                 reservas.forEach(reserva => {
//                     const unidade = reserva.user?.unidade?.nome ?? 'Unidade Desconhecida';
//                     const horaInicio = reserva.data_inicio;
//                     const horaFim = reserva.data_fim;

//                     calendar.addEvent({
//                         title: `Unidade: ${unidade}`,
//                         start: horaInicio,
//                         end: horaFim,
//                         extendedProps: {
//                             unidade: unidade
//                         }
//                     });
//                 });

//                 // Atualiza o calendário
//                 calendar.render();
//             }
//         },
//         error: function() {
//             $('#reservasContainer').html(
//                 '<p class="text-center"><i class="fa-solid fa-x me-1" style="color: #b22720;"></i> Erro ao carregar reservas.</p>'
//             );
//         }
//     });
// }







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


<!-- <div class="modal fade" id="verReservasModal" tabindex="-1" aria-labelledby="verReservasModalLabel" aria-hidden="true">
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
</div> -->




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