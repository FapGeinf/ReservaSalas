@extends('layouts.app')
@section('content')
@section('title') {{ 'Início' }} @endsection

<link rel="stylesheet" href="{{ asset('css/user.css') }}">
<link rel="stylesheet" href="{{ asset('css/bg.css') }}">
<link rel="stylesheet" href="{{ asset('css/input-text.css') }}">
<!-- <link rel="stylesheet" href="{{ asset('css/custom.css') }}"> -->
<link rel="stylesheet" href="{{ asset('css/main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/table-main-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/calendar-page.css') }}">
<link rel="stylesheet" href="{{ asset('css/form-custom.css') }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
<!-- <script src="js/custom.js"></script> -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>

<style>
    .fc .fc-toolbar.fc-header-toolbar {
        margin-bottom: 1rem;
    }

    .fc-timegrid-event {
        border-radius: 6px;
        padding: 2px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        border: none;
    }
</style>

<div class="pagina-container">
    <div class="d-flex flex-wrap flex-lg-nowrap">
        <!-- Coluna esquerda: Mini calendário + Cards -->
        <div class="col-lg-3 col-12 mb-3">
            <div class="salas-grid d-flex flex-lg-column gap-2 h-100 flex-column flex-md-row">

                <!-- Cards de salas -->
                <div class="w-100 w-md-50 d-flex flex-column gap-2" data-help="cards-salas">
                    @foreach($salas as $sala)
                    @php
                        $situacao = strtolower(trim($sala->situacao));
                        $nomeSala = strtolower(trim($sala->nome));
                        $classeBorda = '';

                        if (str_contains($nomeSala, 'aquário')) $classeBorda = 'border-aquário';
                        elseif (str_contains($nomeSala, 'daf')) $classeBorda = 'border-daf';
                        elseif (str_contains($nomeSala, 'pres')) $classeBorda = 'border-pres';
                        elseif (str_contains($nomeSala, 'audit')) $classeBorda = 'border-audit';

                        // Nome reduzido
                        $nomeCurto = $sala->nome;
                        if (str_contains($nomeSala, 'auditório tauató')) {
                            $nomeCurto = 'Tauató';
                        } elseif (str_contains($nomeSala, 'presidência')) {
                            $nomeCurto = 'Pres.';
                        }
                    @endphp

                    <div class="sala-card {{ $classeBorda }}width-100" style="border-left: 6px solid {{ $sala->cor }}; border-radius:10px;">
                        <div class="sala-card-conteudo d-flex align-items-center flex-wrap" style="gap: 1rem;">
                            <!-- Imagem à esquerda -->
                            <div style="flex: 0 0 100px;">
                                <img src="{{ asset('img/salas/' . $sala->imagem) }}" alt="Imagem {{ $sala->nome }}" class="imagem-sala" style="width: 100px;">
                            </div>
                            <!-- Nome e estado ao centro -->
                            <div class="flex-grow-1">
                                <div class="titulo-sala">
                                    <span class="text-uppercase fw-semibold d-block">
                                        <span class="nome-sala-full">{{ $sala->nome }}</span>
                                        <span class="nome-sala-short d-none">{{ $nomeCurto }}</span>
                                    </span>
                                    @if($situacao === 'inativa')
                                        <span class="s-manutencao fw-medium" style="font-size: 14px;">Sala em manutenção</span>
                                    @else
                                        <span class="s-disponivel fw-medium" style="font-size: 14px;">Sala disponível</span>
                                    @endif
                                </div>
                            </div>
                            <!-- Botão à direita -->
                            @if($situacao !== 'ativa')
                            <div style="flex-shrink: 0;">
                                <button class="button-grey" data-bs-toggle="modal" data-bs-target="#verReservasModal"
                                    data-sala-id="{{ $sala->id }}">
                                    Ver Reservas
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Mini calendário -->
                <div class="calendar-container w-100 w-md-50 border shadow-sm flex-grow-1 d-flex flex-column mt-3 mt-md-0" style="background-color: #fff;" data-help="mini-calendario">
                    <div class="text-center" style="margin-top: 13px;">
                        <span class="fw-bold" style="color: #374151; font-size: 15px;">CALENDÁRIO MENSAL</span>
                    </div>
                    <div id="miniCalendar" class="w-100 flex-fill" style="border-bottom: 1px solid #dee2e6;"></div>
                </div>
            </div>
        </div>

        <!-- Coluna direita: Calendário principal -->
        <div class="col-lg-9 col-12 px-lg-3">
            <div class="caixa-calendario" data-help="calendario-principal">
                <div class="area-calendario">
                    <div id="calendar" class="calendar-container main-calendar" style="margin-top: 15px;"></div>
                </div>
                <div class="mt-1">
                    <span style="font-size: 14px; color: #374151;">
                        <i class="bi bi-lightbulb-fill text-warning"></i>
                        Clique em uma data para reservar uma sala ou visualizar agendamentos.
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const tutorialJaVisto = {{ auth()->user()->tutorial_exibido ? 'true' : 'false' }};
    const steps = [
        {
            element: '[data-help="cards-salas"]',
            text: "📋 Aqui estão listadas todas as salas com seus estados (disponível ou em manutenção). Você pode ver o nome, imagem e a cor identificadora.",
        },
        {
            element: '[data-help="mini-calendario"]',
            text: "🗓️ Este é o mini calendário. Use-o para navegar rapidamente entre os meses.",
        },
        {
            element: '[data-help="calendario-principal"]',
            text: "📆 Este é o calendário principal. Clique em uma data para ver ou fazer uma reserva.",
        },
    ];

    let currentStep = 0;

    function showStep(index) {
        if (index >= steps.length) {
            // Marcar tutorial como visto no banco
            fetch("{{ route('usuario.marcarTutorial') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({})
            });
            return;
        }

        const step = steps[index];
        const el = document.querySelector(step.element);
        if (!el) return;

        const tooltip = document.createElement('div');
        tooltip.innerText = step.text;
        tooltip.className = 'tutorial-tooltip';
        Object.assign(tooltip.style, {
            position: 'absolute',
            background: '#fff',
            border: '1px solid #ccc',
            padding: '10px',
            borderRadius: '8px',
            boxShadow: '0 2px 10px rgba(0,0,0,0.1)',
            zIndex: 1000,
            maxWidth: '300px',
        });

        document.body.appendChild(tooltip);
        const rect = el.getBoundingClientRect();
        tooltip.style.top = (rect.top + window.scrollY + 20) + 'px';
        tooltip.style.left = (rect.left + window.scrollX + 20) + 'px';

        el.style.outline = '3px solid #0d6efd';

        const next = document.createElement('button');
        next.innerText = (index === steps.length - 1) ? 'Finalizar' : 'Próximo';
        next.className = 'btn btn-primary btn-sm mt-2';
        next.onclick = () => {
            tooltip.remove();
            el.style.outline = '';
            showStep(index + 1);
        };
        tooltip.appendChild(document.createElement('br'));
        tooltip.appendChild(next);
    }

    if (!tutorialJaVisto) {
        showStep(currentStep);
    }
});
</script>


<style>
    .tutorial-tooltip {
        animation: fadeIn 0.3s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
</style>

<style>
    @media (max-width: 991.98px) {
        .salas-grid {
            flex-direction: row !important;
        }
        .salas-grid > .w-100.w-md-50 {
            width: 49% !important;
            max-width: 49%;
        }
        .salas-grid > .calendar-container.w-100.w-md-50 {
            width: 49% !important;
            max-width: 49%;
            margin-top: 0 !important;
        }
    }
    @media (max-width: 767.98px) {
        .salas-grid {
            flex-direction: column !important;
        }
        .salas-grid > .w-100.w-md-50,
        .salas-grid > .calendar-container.w-100.w-md-50 {
            width: 100% !important;
            max-width: 100%;
        }
        .calendar-container {
            margin-top: 1rem !important;
        }
    }
</style>

@if (session('error'))
<div class="alert alert-danger text-center mx-auto" style="max-width: 30%;">
    {{ session('error') }}
</div>
@endif

<!-- Modal de Reserva -->
<div class="modal fade" id="modalReserva" tabindex="-1" aria-labelledby="modalReservaLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalReservaLabel">Nova Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm" class="mb-3">
                    @csrf

                    <input type="hidden" name="data_reserva" id="data_reserva">
                    <input type="hidden" id="sala_fk_hidden">

                    <div class="row mb-3">
                        <div class="col-8">
                            <label for="sala_fk" class="fw-bold">Sala:</label>

                            <select name="sala_fk" id="sala_fk" class="form-select input-custom pointer" required>
                            <option value="" disabled selected>Selecione uma sala</option>
                            @foreach($salas as $sala)
                                <option value="{{ $sala->id }}">{{ $sala->nome }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>

                     <div class="row mb-3">
                        <div class="col-8">
                            <label for="tipo_reserva" class="fw-bold">Tipo de Reserva:</label>
                            <select name="tipo_reserva" id="tipo_reserva" class="form-select pointer" required>
                                <option value="" selected disabled>Selecione uma opção</option>
                                <option value="interno">Uso interno</option>
                                <option value="pesquisador">Pesquisador externo</option>
                            </select>
                        </div>
                    </div>

                    <div class="row align-items-end">
                        <div class="col-4">
                            <label for="hora_inicio" class="fw-bold">Hora de Início:</label>
                            <input type="time" name="hora_inicio" id="hora_inicio" class="input-custom" required>
                        </div>

                        <div class="col-4">
                            <label for="hora_termino" class="fw-bold">Hora de Término:</label>
                            <input type="time" name="hora_termino" id="hora_termino" class="input-custom" required>
                        </div>

                        <div class="col-4 d-flex align-items-center" style="margin-bottom: 20px;">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="diaInteiro">
                                <label class="form-check-label fw-bold" for="diaInteiro">Dia inteiro</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="button-grey" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="reservaForm" class="button-green">Salvar Reserva</button>
            </div>
        </div>
    </div>
</div>

{{-- 
<!-- Modal de Detalhes da Reserva -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="toastReserva" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Detalhes da Reserva</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fechar"></button>
        </div>

        <div class="toast-body" id="toastBodyReserva">
            <!-- Dados inseridos aqui dinamicamente -->
        </div>

    </div>
</div> --}}

<!-- Modal de Edição de Reserva -->
<div class="modal fade" id="modal-editar-reserva" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modal-editar-reserva-label">Editar Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body pb-4">
                <form method="POST" id="form-editar-reserva">
                    @csrf

                    <input type="hidden" name="_method" value="PUT">

                    <div class="text-center pb-3">
                        <span id="reserva-numero" class="fw-semibold" style="color: #374151;"></span>
                    </div>

                    <div class="row mb-4">
                        <div class="col-7">
                            <label for="sala_id" class="fw-bold">Sala:</label>
                            <select name="sala_id" id="sala_id" class="form-select pointer" required>
                                <!-- opções preenchidas via JavaScript -->
                            </select>
                        </div>

                        <div class="col-5">
                            <label for="data_inicio" class="fw-bold fs-16">Data:</label>
                            <input type="date" name="data_inicio" id="data_inicio" class="input-custom pointer" required>
                        </div>
                    </div>

                    <div class="row align-items-end">

                        <div class="col-4">
                            <label for="hora_inicio" class="fw-bold fs-16">Hora Início:</label>
                            <input type="time" name="hora_inicio" id="hora_inicio" class="input-custom pointer" step="60" required>
                        </div>

                        <div class="col-4">
                            <label for="data_fim" class="fw-bold fs-16">Hora Término:</label>
                            <input type="time" name="data_fim" id="data_fim" class="input-custom pointer" step="60" required>
                        </div>

                        <div class="col-4 d-flex align-items-center" style="margin-bottom: 20px;">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="diaInteiro">
                                <label class="form-check-label fw-bold" for="diaInteiro">Dia inteiro</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="button-grey" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="form-editar-reserva" class="button-green">Salvar Alterações</button>
            </div>
                    
        </div>
    </div>
</div>
                   
<!-- Modal Detalhes da Reserva -->
<div class="modal fade" id="modalDetalhesReserva" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Detalhes da Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>

            <div class="modal-body">
                <div class="row pb-3">
                    <div class="col-5">
                        <label class="fw-bold">Sala:</label>
                        <span id="detalheSala" class="input-custom-disabled"></span>
                    </div>

                    <div class="col-7">
                        <label class="fw-bold">Unidade:</label>
                        <span id="detalheUnidade" class="input-custom-disabled"></span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-5">
                        <label class="fw-bold">Horário:</label>
                        <span id="detalheHorario" class="input-custom-disabled"></span>
                    </div>

                    <div class="col-7">
                        <label class="fw-bold">Responsável:</label>
                        <span id="detalheResponsavel" class="input-custom-disabled"></span> 
                    </div>
                </div>  
            </div>

            <div class="modal-footer">
                <button type="button" id="btnEditar" class="button-blue">Editar</button>
                <button type="button" id="btnExcluir" class="button-red">Excluir</button>
            </div>
        </div>
    </div>
</div>

<script>
    function abrirModalDetalhes(event) {
        document.getElementById('detalheSala').innerText = event.title || '';
        document.getElementById('detalheUnidade').innerText = event.extendedProps.unidade || '';
        document.getElementById('detalheHorario').innerText = `${event.extendedProps.hora_inicio} - ${event.extendedProps.hora_fim}` || '';
        document.getElementById('detalheResponsavel').innerText = event.extendedProps.responsavel || '';

        document.getElementById('btnEditar').onclick = function() {
            abrirModalEdicao(
                event.id,
                event.extendedProps.hora_inicio,
                event.extendedProps.hora_fim,
                event.extendedProps.data_inicio,
                event.extendedProps.sala_id
            );
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalDetalhesReserva'));
            modal.hide();
        };

        document.getElementById('btnExcluir').onclick = function() {
            setDeleteId(event.id);
            var modal = bootstrap.Modal.getInstance(document.getElementById('modalDetalhesReserva'));
            modal.hide();
            var confirmModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            confirmModal.show();
        };

        var modalDetalhes = new bootstrap.Modal(document.getElementById('modalDetalhesReserva'));
        modalDetalhes.show();
    }
</script>

<script>
document.getElementById('diaInteiro').addEventListener('change', function () {
    const inicio = document.getElementById('hora_inicio');
    const termino = document.getElementById('hora_termino');
    if (this.checked) {
        // marca com o dia inteiro: define horários e desativa Edição

        inicio.value = '08:00';
        termino.value = '20:00';
        inicio.readOnly = true;
        termino.readOnly = true;
    } else {
        // permite editar novamente
        inicio.readOnly = false;
        termino.readOnly = false;
        inicio.value = '';
        termino.value = "";
    }
    }
);

</script>

<!-- <script>
    document.getElementById('horaSelecionada').addEventListener('change', function() {
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
</script> -->

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const campoHora = document.getElementById('horaSelecionada');
    if (campoHora) {
      campoHora.addEventListener('change', function () {
        const dataSelecionada = new Date(this.value);
        const hoje = new Date();

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

        window.calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'timeGridWeek',
            slotDuration: '00:15:00',
            slotLabelInterval: '00:30:00',
            slotMinTime: '07:00:00',
            slotMaxTime: '21:00:00',
            eventOverlap: true,
            timeZone: 'local',
            locale: 'pt-br',
            eventMaxStack: true,
            hiddenDays: [0, 6],
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
                    <div style="font-size: 1rem; color: #555555;">
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
                document.getElementById('hora_inicio').value = info.dateStr.substring(11, 16);

                var modalReserva = new bootstrap.Modal(document.getElementById('modalReserva'));
                modalReserva.show();

                setTimeout(() => {
                    document.getElementById('sala_fk').focus();
                }, 500);
            },
            eventClick: function(info) {
                // Abre modal Bootstrap separado
                abrirModalDetalhes(info.event);
            }
        });

        window.calendar.render();

        window.miniCalendar = new FullCalendar.Calendar(miniCalendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: '',
                center: 'title',
                right: ''
            },
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
<div class="modal fade" id="verReservasModal" tabindex="-1" aria-labelledby="verReservasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content custom-modal">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="verReservasModalLabel">Reservas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label for="dataSelecionada" class="fw-bold">Selecione a Data:</label>
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
                $(form).find('button[type="submit"]').prop('disabled', false).text('Excluir');
            }
        });
    });
</script>

<script>const salasDisponiveis = @json($salas);</script>

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
                <button type="button" class="button-grey" data-bs-dismiss="modal">Cancelar</button>

                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button-red">Excluir</button>
                </form>
                
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




