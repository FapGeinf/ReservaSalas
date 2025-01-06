@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    

        <div id='calendar'></div>

</div>

   

<script>

$(document).ready(function () {
    var SITEURL = "{{ url('/') }}";

    // Configuração do token CSRF
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Inicializar o FullCalendar
    var calendar = $('#calendar').fullCalendar({
        editable: true,
        events: SITEURL + "/reserva",
        displayEventTime: true,
        selectable: true,
        selectHelper: true,
        
        // Ao selecionar um intervalo
        select: function (start, end, allDay) {
            $('#start').val(moment(start).format('YYYY-MM-DD'));
            $('#end').val(moment(end).format('YYYY-MM-DD'));
            $('#reservaModal').modal('show');
        },
        
        // Ao clicar em um evento
        eventClick: function (event) {
            $('#reserva_id').val(event.id);
            $('#sala_fk').val(event.sala_fk);
            $('#data_reserva').val(moment(event.start).format('YYYY-MM-DD'));
            $('#hora_inicio').val(moment(event.start).format('HH:mm'));
            $('#hora_fim').val(moment(event.end).format('HH:mm'));
            $('#reservaModal').modal('show');
        },

        // Atualizar evento ao arrastar
        eventDrop: function (event, delta) {
            var start = moment(event.start).format('YYYY-MM-DD HH:mm:ss');
            var end = moment(event.end).format('YYYY-MM-DD HH:mm:ss');
            $.ajax({
                url: SITEURL + '/reserva/update',
                data: {
                    id: event.id,
                    start: start,
                    end: end
                },
                type: "POST",
                success: function (response) {
                    displayMessage("Reserva atualizada com sucesso!");
                }
            });
        }
    });

    // Salvar Reserva via AJAX
    $('#saveReserva').click(function () {
        var formData = $('#reservaForm').serialize();
        $.ajax({
            url: SITEURL + "/reserva/store",
            data: formData,
            type: "POST",
            success: function (response) {
                $('#reservaModal').modal('hide');
                calendar.fullCalendar('refetchEvents');
                displayMessage("Reserva criada/atualizada com sucesso!");
            },
            error: function (response) {
    console.log(response.responseJSON); // Mostra o erro no console do navegador
    alert('Erro ao salvar a reserva.');
}

        });
    });
});

// Função para exibir mensagens de sucesso
function displayMessage(message) {
    toastr.success(message, 'Sucesso');
}

</script>
    @endpush
@extends('layouts.app')

@section('content')
<div class="container">
<div id="calendar"></div>

    <!-- Botão para abrir o modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#criarReservaModal">
        Criar Reserva
    </button>
       

    <!-- Modal -->
    <div class="modal fade" id="reservaModal" tabindex="-1" aria-labelledby="reservaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservaModalLabel">Nova Reserva</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="reservaForm">
                    @csrf
                    <input type="hidden" name="id" id="reserva_id">
                    <input type="hidden" name="start" id="start">
                    <input type="hidden" name="end" id="end">

                    <div class="mb-3">
                        <label for="sala_fk" class="form-label">Sala</label>
                        <select name="sala_fk" id="sala_fk" class="form-control" required>
                            <option value="">Selecione uma Sala</option>
                            @foreach($salas as $sala)
                                <option value="{{ $sala->id }}">{{ $sala->nome }}</option>
                            @endforeach
                        </select>
                    </div>

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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="saveReserva" class="btn btn-primary">Salvar Reserva</button>
            </div>
        </div>
    </div>
</div>


<div class="container mt-5">
    <h2 class="mt-5">Reservas</h2>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Sala</th>
                        <th>Hora Início</th>
                        <th>Hora Término</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservas as $reserva)
                        <tr>
                            <td>{{ $reserva->sala->nome }}</td>
                            <td>{{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y | H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y | H:i')}}</td>
                            <td>
                                <a href="{{ route('reservas.show', $reserva->id) }}" class="btn btn-info btn-sm">Detalhes</a>
                                <a href="{{ route('reservas.edit', $reserva->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Cancelar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
@endsection

@section('scripts')
<script src="{{ mix('js/calendar.js') }}"></script>
@endsection
