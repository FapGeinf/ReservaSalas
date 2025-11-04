document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var miniCalendarEl = document.getElementById('miniCalendar');

  window.calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    allDaySlot: false,
    slotDuration: '00:15:00',
    slotLabelInterval: '00:30:00',
    slotMinTime: '07:00:00',
    slotMaxTime: '21:00:00',
    eventOverlap: true,
    timeZone: 'local',
    locale: 'pt-br',
    eventMaxStack: true,
    /* Esconde sábado e domingo */
    hiddenDays: [0, 6],
    events: '/eventos',
    selectable: false,
    editable: false,
    eventDisplay: 'block',
    buttonText: {
      today: 'Hoje',
      month: 'Mês',
      week: 'Semana',
      day: 'Dia',
      list: 'Lista'
    },

    height: 'auto',
    expandRows: true,
    stickyHeaderDates: true,
    dayMaxEvents: false,       // impede o agrupamento
    eventMaxStack: Infinity,   // permite pilha ilimitada
    slotEventOverlap: false,   // força exibição em linhas separadas
    eventOrder: "start",       // garante que eventos com mesma hora sejam empilhados na ordem de início

    dayCellDidMount: function(info) {
      const today = new Date();
      today.setHours(0, 0, 0, 0);
      const cellDate = new Date(info.date);
      cellDate.setHours(0, 0, 0, 0);

      if (cellDate < today) {
        info.el.style.backgroundColor = '#f7f7f7'; // cinza claro
      }
    },

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
      // right: 'dayGridMonth,timeGridWeek,listWeek ("lista" removida dos botões)'
      right: 'dayGridMonth,timeGridWeek'
    },

    dateClick: function(info) {
      const dataClicada = new Date(info.dateStr);
      const hoje = new Date();

      // Normaliza para comparar apenas o dia (ano, mês, dia)
      const dataClicadaStr = dataClicada.toISOString().split('T')[0];
      const hojeStr = hoje.toISOString().split('T')[0];

      // Calcula "ontem" baseado na data de hoje
      const ontem = new Date(hoje);
      ontem.setDate(hoje.getDate() - 1);
      const ontemStr = ontem.toISOString().split('T')[0];

      // Bloqueia datas até ontem
      if (dataClicadaStr <= ontemStr) {
        const modalErro = new bootstrap.Modal(document.getElementById('modalErroDataPassada'));
        modalErro.show();
        return;
      }

      // Abre modal normalmente para hoje ou futuras
      document.getElementById('data_reserva').value = dataClicadaStr;
      document.getElementById('hora_inicio').value = info.dateStr.substring(11, 16);

      const modalReserva = new bootstrap.Modal(document.getElementById('modalReserva'));
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

// --- Corrige largura da última coluna após carregamento completo ---
window.addEventListener('load', () => {
  setTimeout(() => {
    if (window.calendar) {
      window.calendar.updateSize();
      window.calendar.render();
    }
  }, 300);
});
