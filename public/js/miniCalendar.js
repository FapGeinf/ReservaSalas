document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var miniCalendarEl = document.getElementById('miniCalendar');

  window.calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'timeGridWeek',
    allDaySlot: false,
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

    // --- VISUAL ---
    height: 'auto',
    expandRows: true,
    stickyHeaderDates: true,
    dayMaxEvents: false,       // impede o agrupamento
    eventMaxStack: Infinity,   // permite pilha ilimitada
    slotEventOverlap: false,   // força exibição em linhas separadas
    eventOrder: "start", // garante que eventos com mesma hora sejam empilhados na ordem de início

    eventContent: function(arg) {
      const horaInicio = arg.event.extendedProps.hora_inicio || '';
      const horaFim = arg.event.extendedProps.hora_fim || '';
      const unidade = arg.event.extendedProps.unidade || '';
      const nomeSala = arg.event.title || '';

      return {
        html: `
          <div class="p-1 rounded-2 shadow-sm border" 
            style="background: rgba(255,255,255,0.7);
            backdrop-filter: blur(4px);
            border-left: 4px solid #6c757d;">

            <span class="fw-bold text-uppercase d-block" style="font-size: 13px; color: #333;">${nomeSala}</span>
            <span style="font-size: 12px; color: #555;">
              <i class="bi bi-clock" style="position: relative; top: -1px;"></i> 
              ${horaInicio} - ${horaFim}
            </span><br>
            <span style="font-size: 12px; color: #666;">${unidade}</span>
          </div>
        `
      };
    },

    eventDidMount: function(info) {
      const today = new Date();
      const eventEnd = new Date(info.event.end || info.event.start);
      today.setHours(0, 0, 0, 0);
      eventEnd.setHours(0, 0, 0, 0);

      // Opacidade em eventos passados
      if (eventEnd < today) {
        info.el.style.opacity = '0.5';
        info.el.style.filter = 'grayscale(20%)';
      }

      // Efeito hover
      info.el.addEventListener('mouseenter', () => {
        info.el.style.transform = 'scale(1.02)';
        info.el.style.transition = 'transform 0.15s ease-in-out';
        info.el.style.zIndex = '5';
      });
      info.el.addEventListener('mouseleave', () => {
        info.el.style.transform = 'scale(1)';
      });
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

  // --- Mini calendário ---
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
    },
    dayCellDidMount: function(info) {
      info.el.style.borderRadius = '6px';
      info.el.style.transition = 'background 0.2s ease-in-out';
      info.el.addEventListener('mouseenter', () => {
        info.el.style.background = 'rgba(0,0,0,0.05)';
      });
      info.el.addEventListener('mouseleave', () => {
        info.el.style.background = '';
      });
    }
  });

  window.miniCalendar.render();

  // --- Estilos para garantir rolagem ---
   calendarEl.style.overflowY = 'auto';
   calendarEl.style.maxHeight = '700px';
   miniCalendarEl.style.overflowY = 'auto';
   miniCalendarEl.style.maxHeight = '400px';
});
