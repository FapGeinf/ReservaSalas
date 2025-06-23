@extends('layouts.app')
@section('content')
@section('title') {{ 'Início' }} @endsection

<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/calendar-page.css') }}">

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
        $nomeSala = strtolower(trim($sala->nome));

        // Define a classe de borda com base no nome da sala
        $classeBorda = '';
        if (str_contains($nomeSala, 'aquário')) {
        $classeBorda = 'border-aquário';
        } elseif (str_contains($nomeSala, 'daf')) {
        $classeBorda = 'border-daf';
        } elseif (str_contains($nomeSala, 'pres')) {
        $classeBorda = 'border-pres';
        } elseif (str_contains($nomeSala, 'audit')) {
        $classeBorda = 'border-audit';
        }
        @endphp

        <div class="sala-card {{ $classeBorda }}">
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
                    {{-- <button class="botao-reservar" onclick="abrirModalCalendario({{ $sala->id }})"></button> --}}
                    @else
                    <button class="button-grey" data-bs-toggle="modal" data-bs-target="#verReservasModal"
                        data-sala-id="{{ $sala->id }}">
                        Ver Reservas
                    </button>
                    @endif

                </div>
            </div>
        </div>
        @endforeach
    </div>


    <div class="container-fluid mt-3">
        <div class="row">
            <!-- Mini calendário -->
            <div class="col-md-3">
                <div id="miniCalendar"></div>
            </div>

            <!-- Calendário -->
            <div class="caixa-calendario">
                <div class="area-calendario">
                    <div id="calendar" class="calendar-container" style="margin-top: 20px;"></div>
                </div>

                <div class="mt-1">
                    <span style="font-size: 14px; color: #374151;">
                        <i class="bi bi-lightbulb-fill text-warning"></i>
                        Clique em uma data para reservar uma sala ou visualizar agendamentos.
                    </span>
                </div>
            </div>

            @if (session('error'))
            <div class="alert alert-danger text-center mx-auto" style="max-width: 30%;">
                {{ session('error') }}
            </div>
            @endif


            <!-- Modal de Reserva -->
            <div class="modal fade" id="modalReserva" tabindex="-1" aria-labelledby="modalReservaLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fw-bold" id="modalReservaLabel">Nova Reserva</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Fechar"></button>
                        </div>

                        <div class="modal-body">
                            <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
                                @csrf

                                <input type="hidden" name="data_reserva" id="data_reserva">
                                <input type="hidden" id="sala_fk_hidden">

                                <div class="mb-3">
                                    <label for="sala_fk" class="fw-bold">Sala:</label>
                                    <select name="sala_fk" id="sala_fk" class="input-custom" required>
                                        <option value="" disabled selected>Selecione uma sala</option>
                                        @foreach($salas as $sala)
                                        <option value="{{ $sala->id }}">{{ $sala->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="mb-3">
                                    <label for="hora_inicio" class="fw-bold">Hora de Início:</label>
                                    <input type="time" name="hora_inicio" id="hora_inicio" class="input-custom"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="hora_termino" class="fw-bold">Hora de Término:</label>
                                    <input type="time" name="hora_termino" id="hora_termino" class="input-custom"
                                        required>
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


            <!-- Modal de Edição de Reserva -->
            <div class="modal fade" id="modal-editar-reserva" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-md">

                    <div class="modal-content p-30__no-bottom">
                        <div class="modal-header border-0 p-2 justify-content-end">
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Fechar"></button>
                        </div>

                        <div class="mx-auto form_create-800">
                            <div class="row justify-content-center">
                                <div class="col">
                                    <div class="box__no-border no-margin-bottom title-bg">
                                        <h3 class="text-center fw-bold">Editar Reserva</h3>
                                        <div class="text-center">
                                            <span id="reserva-numero" class="fs-19" style="color: #374151;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mx-auto form_create__no-border-800">
                                <div class="row justify-content-center">
                                    <div class="col">
                                        <div class="box__no-border">
                                            <div style="padding: 0 !important;">
                                                <form method="POST" id="form-editar-reserva">
                                                    @csrf
                                                    <input type="hidden" name="_method" value="PUT">

                                                    <div class="row g-3">
                                                        <div class="col-12 col-md-6">
                                                            <label for="sala_id" class="fw-bold fs-16">Sala:</label>
                                                            <select name="sala_id" id="sala_id"
                                                                class="form-select pointer" required>
                                                                <!-- opções preenchidas via JavaScript -->
                                                            </select>
                                                        </div>

                                                        <div class="col-12 col-md-6">
                                                            <label for="data_inicio" class="fw-bold fs-16">Data:</label>
                                                            <input type="date" name="data_inicio" id="data_inicio"
                                                                class="input-custom pointer" required>
                                                        </div>
                                                    </div>

                                                    <div class="row g-3 mt-1">
                                                        <div class="col-12 col-md-6">
                                                            <label for="hora_inicio" class="fw-bold fs-16">Hora
                                                                Início:</label>
                                                            <input type="time" name="hora_inicio" id="hora_inicio" class="input-custom pointer" step="60" required>
                                                        </div>

                                                        <div class="col-12 col-md-6">
                                                            <label for="data_fim" class="fw-bold fs-16">Hora
                                                                Término:</label>
                                                            <input type="time" name="data_fim" id="data_fim" class="input-custom pointer" step="60" required>

                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-end mt-5">
                                                        <button type="submit" class="button-blue">Salvar
                                                            Alterações</button>
                                                        <button type="button" class="btn btn-secondary ms-2"
                                                            data-bs-dismiss="modal">Cancelar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <script>
            document.getElementById('dataSelecionada').addEventListener('change', function() {
                const dataSelecionada = new Date(this.value);
                const hoje = new Date();

                // Remover a hora para comparar apenas a data
                hoje.setHours(0, 0, 0, 0);
                dataSelecionada.setHours(0, 0, 0, 0);

                if (dataSelecionada < hoje) {
                    Swal.fire({
                        title: 'Erro!',
                        text: 'A data selecionada já passou. Escolha uma data futura.',
                        icon: 'error'
                    });
                }
            });
            </script>

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

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
            <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
            <!-- FullCalendar com suporte a pt-br -->
            <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

            <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendarEl = document.getElementById('calendar');
                var miniCalendarEl = document.getElementById('miniCalendar');

                // Calendário principal
                window.calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    slotDuration: '00:15:00',
                    slotLabelInterval: '00:30:00',
                    slotMinTime: '07:00:00',
                    slotMaxTime: '21:00:00',
                    eventOverlap: false,
                    timeZone: 'local',
                    locale: 'pt-br',

                    hiddenDays: [0, 6], // Oculta domingo e sábado

                    events: '/eventos',
                    selectable: true,
                    editable: false,
                    eventDisplay: 'block',

                    buttonText: {
                        today: 'Hoje',
                        month: 'Mês',
                        week: 'Semana',
                        day: 'Dia',
                        list: 'Lista'
                    },

                    eventContent: function(arg) {
                        const horaInicio = arg.event.extendedProps.hora_inicio || '';
                        const horaFim = arg.event.extendedProps.hora_fim || '';
                        const unidade = arg.event.extendedProps.unidade || '';
                        const nomeSala = arg.event.title || '';

                        return {
                            html: `
                <div style="font-size: 0.95em; color: #555555;">
                    <span class="fw-bold text-uppercase">${nomeSala}</span><br>
                    <i class="bi bi-clock" style="font-size: 11px; position: relative; top: -1px;"></i> 
                    ${horaInicio} - ${horaFim}<br>
                    ${unidade}
                </div>
                `
                        };
                    },

                    eventDidMount: function(info) {
                        const today = new Date();
                        const eventEnd = new Date(info.event.end || info.event.start);
                        today.setHours(0, 0, 0, 0);
                        eventEnd.setHours(0, 0, 0, 0);

                        if (eventEnd < today) {
                            info.el.style.opacity = '0.4';
                            info.el.style.filter = 'grayscale(10%)';
                        }
                    },

                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,listWeek'
                    },

                    dateClick: function(info) {
                        document.getElementById('data_reserva').value = info.dateStr.split('T')[0];
                        document.getElementById('hora_inicio').value = info.dateStr.substring(11,
                            16);

                        var modalReserva = new bootstrap.Modal(document.getElementById(
                            'modalReserva'));
                        modalReserva.show();

                        setTimeout(() => {
                            document.getElementById('sala_fk').focus();
                        }, 500);
                    },

                    eventClick: function(info) {
                        Swal.fire({
                            html: `
            <div class="p-4 rounded border" style="background-color: #f8f9fa;">
                <h5 class="fw-bold text-center" style="color: #394151;">📅 Detalhes da Reserva</h5>
                <hr>
                <div class="mb-3 mt-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-semibold" style="color: #394151;">Sala:</span>
                        <span class="text-end fw-bold" style="color: #6c757d;">${info.event.title}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-semibold" style="color: #394151;">Unidade:</span>
                        <span class="text-end fw-bold" style="color: #6c757d;">${info.event.extendedProps.unidade}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-semibold" style="color: #394151;">Horário:</span>
                        <span class="text-end fw-bold" style="color: #6c757d;">
                            ${info.event.extendedProps.hora_inicio} - ${info.event.extendedProps.hora_fim}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fw-semibold" style="color: #394151;">Responsável:</span>
                        <span class="text-end fw-bold" style="color: #6c757d;">${info.event.extendedProps.responsavel}</span>
                    </div>
                    <div class="dropdown text-center">
                        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownSwal" data-bs-toggle="dropdown" aria-expanded="false">
                            Opções
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownSwal">
                            <li><a class="dropdown-item" href="#" id="editarReservaSwal"><i class="bi bi-pencil-square me-1"></i> Editar</a></li>
                            <li><a class="dropdown-item text-danger" href="#" id="excluirReservaSwal"><i class="bi bi-trash me-1"></i> Excluir</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        `,
                            showConfirmButton: false,
                            showCloseButton: true,
                            didOpen: () => {
                                document.getElementById('editarReservaSwal')
                                    .addEventListener('click', () => {
                                        abrirModalEdicao(
                                            info.event.id,
                                            info.event.extendedProps
                                            .hora_inicio,
                                            info.event.extendedProps.hora_fim,
                                            info.event.extendedProps
                                            .data_inicio,
                                            info.event.extendedProps.sala_id
                                        );
                                        Swal.close();
                                    });

                                document.getElementById('excluirReservaSwal')
                                    .addEventListener('click', () => {
                                        setDeleteId(info.event.id);
                                        const modal = new bootstrap.Modal(document
                                            .getElementById(
                                                'confirmDeleteModal'));
                                        modal.show();
                                        Swal.close();
                                    });
                            }
                        });
                    }

                });

                window.calendar.render();

                // Mini calendário (FullCalendar)
                window.miniCalendar = new FullCalendar.Calendar(miniCalendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: false,
                    locale: 'pt-br',
                    dateClick: function(info) {
                        window.calendar.gotoDate(info.date);
                    }
                });

                window.miniCalendar.render();
            });
            </script>

            <script>
            function abrirModalEdicao(id, horaInicio, horaFim, dataInicio, salaId) {
                if (!id) {
                    alert('Erro: ID da reserva ausente!');
                    return;
                }

                // Preenche os campos
                document.getElementById('data_inicio').value = dataInicio;
                document.getElementById('hora_inicio').value = horaInicio;
                document.getElementById('data_fim').value = horaFim;
                document.getElementById('reserva-numero').textContent = `Reserva ${id}`;
                document.getElementById('form-editar-reserva').action = `/reservas/${id}`;

                // Preenche o select de salas
                const selectSala = document.getElementById('sala_id');
                selectSala.innerHTML = ''; // limpa opções anteriores

                salasDisponiveis.forEach(sala => {
                    const option = document.createElement('option');
                    option.value = sala.id;
                    option.textContent = sala.nome;
                    if (sala.id === salaId) {
                        option.selected = true;
                    }
                    selectSala.appendChild(option);
                });

                // Abre o modal
                const modal = new bootstrap.Modal(document.getElementById('modal-editar-reserva'));
                modal.show();
            }
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
                        '<span class="spinner-border spinner-border-sm" role="status"></span> Salvando...'
                    );

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
                                    confirmButtonText: 'OK',
                                    customClass: {
                                        confirmButton: 'button-green'
                                    }
                                }).then((result) => {
                                    // Redireciona para a home após clicar em OK
                                    window.location.href = "{{ route('home') }}";
                                });
                            }
                        },

                        error: function(xhr) {
                            Swal.fire({
                                title: 'Desculpe!',
                                text: xhr.responseJSON?.message ||
                                    'Erro ao realizar reserva',
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
                            $('#disponibilidade-status').html(
                                '<span class="text-success">Horário disponível</span>');
                            $('.btn-submit').prop('disabled', false);
                        } else {
                            $('#disponibilidade-status').html('<span class="text-danger">' + response
                                .mensagem +
                                '</span>');
                            $('.btn-submit').prop('disabled', true);
                        }
                    }
                });
            }
            </script>

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
                                const unidade = reserva.user?.unidade?.nome ??
                                    'Unidade Desconhecida';
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

            <!-- Modal para Ver Reservas -->
            <div class="modal fade" id="verReservasModal" tabindex="-1" aria-labelledby="verReservasModalLabel"
                aria-hidden="true">
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
                                    <i class="bi bi-arrow-repeat" style="color: #2a64e7;"></i> Carregando reservas...
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            // Função para definir o ID da reserva a ser excluída
            function setDeleteId(reservaId) {
                // Define a ação do formulário com a rota correta
                $('#deleteForm').attr('action', `/reservas/${reservaId}`);

                // Armazena o ID para uso posterior se necessário
                $('#confirmDeleteModal').data('reserva-id', reservaId);
            }

            // Evento de submit do formulário de exclusão
            $('#deleteForm').on('submit', function(e) {
                e.preventDefault();

                const form = this;

                // Aqui você pode adicionar uma animação de loading se quiser
                $(form).find('button[type="submit"]').prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm" role="status"></span> Excluindo...');

                // Envia a requisição AJAX
                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: $(form).serialize(),
                    success: function(response) {
                        // Fecha o modal
                        $('#confirmDeleteModal').modal('hide');

                        // Mostra mensagem de sucesso
                        Swal.fire({
                            title: 'Sucesso!',
                            text: 'Reserva excluída com sucesso!',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            customClass: {
                                confirmButton: 'button-green'
                            }
                        }).then(() => {
                            // Recarrega a página ou atualiza a tabela
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Erro!',
                            text: xhr.responseJSON?.message || 'Erro ao excluir reserva',
                            icon: 'error'

                        });
                    },
                    complete: function() {
                        // Reativa o botão
                        $(form).find('button[type="submit"]').prop('disabled', false).text(
                            'Excluir');
                    }
                });
            });
            </script>

            <script>
            const salasDisponiveis = @json($salas);
            </script>



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
                    text: '{{ session('
                    success ') }}',
                    icon: 'success',
                    confirmButtonText: 'Fechar',
                    customClass: {
                        confirmButton: 'button-red'
                    }
                });
            });
            </script>
            @endif

            @endsection