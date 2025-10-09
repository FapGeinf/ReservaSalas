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
      // hiddenDays: [0, 6],
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
          <!-- <div style="font-size: 1rem; color: #555555;"> -->
          <div>
            <span class="fw-bold text-uppercase">${nomeSala}</span><br>
            <i class="bi bi-clock" style="position: relative; top: -1px;"></i> 
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
