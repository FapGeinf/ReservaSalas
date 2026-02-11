document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var miniCalendarEl = document.getElementById('miniCalendar');

  window.calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    dayHeaderFormat: { weekday: 'long' },
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
    contentHeight: 'auto',
    aspectRatio: 1.2,
    windowResize: function(view) {
      this.updateSize();
    },

    expandRows: true,
    stickyHeaderDates: true,
    dayMaxEvents: false,       // impede o agrupamento
    eventMaxStack: Infinity,   // permite pilha ilimitada
    slotEventOverlap: false,   // força exibição em linhas separadas
    eventOrder: "start",       // garante que eventos com mesma hora sejam empilhados na ordem de início

    // Substitua sua dayCellDidMount atual por esta
    dayCellDidMount: function(info) {
      const el = info.el;
      // pega o fundo atual computado (estado "normal")
      const computedBg = window.getComputedStyle(el).backgroundColor || 'transparent';

      // aplica o estado base como inline para manter sempre o mesmo aspecto
      el.style.background = computedBg;
      el.style.backgroundImage = 'none';
      el.style.boxShadow = 'none';
      el.style.transition = 'none';
      el.style.cursor = 'default';

      // também aplica na "frame" interna, caso o FullCalendar pinte ali
      const frame = el.querySelector('.fc-daygrid-day-frame') || el;
      frame.style.background = computedBg;
      frame.style.backgroundImage = 'none';
      frame.style.boxShadow = 'none';
      frame.style.transition = 'none';
      frame.style.cursor = 'default';

      // força manter o mesmo fundo enquanto o mouse estiver sobre a célula
      const keepBg = () => {
        el.style.background = computedBg;
        el.style.backgroundImage = 'none';
        el.style.boxShadow = 'none';
        frame.style.background = computedBg;
        frame.style.backgroundImage = 'none';
        frame.style.boxShadow = 'none';
      };

      // assegura que qualquer hover aplicado pelo FullCalendar seja sobrescrito
      el.addEventListener('mouseenter', keepBg);
      el.addEventListener('mouseover', keepBg);
      el.addEventListener('mouseleave', keepBg);
    },

    eventContent: function(arg) {
      const horaInicio = arg.event.extendedProps.hora_inicio || '';
      const horaFim = arg.event.extendedProps.hora_fim || '';
      const unidade = arg.event.extendedProps.unidade || '';
      const nomeSala = arg.event.title || '';

      return {
        html: `
          <div class="p-0 rounded-2 shadow-sm border" 
            style="background: rgba(255,255,255,0.7);
            backdrop-filter: blur(4px);
            border-left: 4px solid #6c757d;
            cursor: pointer;">

            <span class="fw-medium text-uppercase d-block" style="font-size: 12px; color: #333;">${nomeSala}</span>
            <span style="font-size: 11px; color: #555;">
              <i class="bi bi-clock" style="position: relative; top: -1px;"></i> 
              ${horaInicio} - ${horaFim}
            </span><br>
            <!-- <span style="font-size: 12px; color: #666;">${unidade}</span> -->
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
      info.el.style.transition = 'box-shadow 0.25s ease, transform 0.25s ease';
      info.el.style.willChange = 'box-shadow, transform';
      info.el.style.backfaceVisibility = 'hidden';
      info.el.style.transformStyle = 'flat';

      info.el.addEventListener('mouseenter', () => {
        info.el.style.transform = 'translateY(-3px)';
        info.el.style.boxShadow = '0 6px 16px rgba(0, 0, 0, 0.15)';
        info.el.style.zIndex = '10';
      });

      info.el.addEventListener('mouseleave', () => {
        info.el.style.transform = 'translateY(0)';
        info.el.style.boxShadow = 'none';
        info.el.style.zIndex = '1';
      });
    },

    headerToolbar: {
      left: 'today',
      center: 'title',
      // right: 'dayGridMonth,timeGridWeek' Disponível visualização somente em formato de calendário.
      right: 'prev,next'
    },

    datesSet: function() {
      const headerCenter = document.querySelector('.fc-toolbar-title');

      if (headerCenter && !document.querySelector('.fc-title-subtext')) {
        const subText = document.createElement('div');
        subText.className = 'fc-title-subtext';
        subText.innerText = 'Selecione uma reserva para visualizar detalhes';
        subText.style.fontSize = '13px';
        subText.style.color = '#212529bf';
        subText.style.marginTop = '4px';
        headerCenter.parentNode.appendChild(subText);
      }
    },

    dateClick: function(info) {
      // DESATIVADO! ESTE BLOCO PERMITE AGENDAR SALA APENAS CLICANDO NO BLOCO DO DIA DESEJADO
      // ESTÁ ASSIM PARA QUE ESSE CALENDARIO SEJA USADO SOMENTE PRA CONSULTA

      // const dataClicada = new Date(info.dateStr);
      // const hoje = new Date();

      // // Normaliza para comparar apenas o dia (ano, mês, dia)
      // const dataClicadaStr = dataClicada.toISOString().split('T')[0];
      // const hojeStr = hoje.toISOString().split('T')[0];

      // // Calcula "ontem" baseado na data de hoje
      // const ontem = new Date(hoje);
      // ontem.setDate(hoje.getDate() - 1);
      // const ontemStr = ontem.toISOString().split('T')[0];

      // // Bloqueia datas até ontem
      // if (dataClicadaStr <= ontemStr) {
      //   const modalErro = new bootstrap.Modal(document.getElementById('modalErroDataPassada'));
      //   modalErro.show();
      //   return;
      // }

      // // Abre modal normalmente para hoje ou futuras
      // document.getElementById('data_reserva').value = dataClicadaStr;
      // document.getElementById('hora_inicio').value = info.dateStr.substring(11, 16);

      // const modalReserva = new bootstrap.Modal(document.getElementById('modalReserva'));
      // modalReserva.show();

      // setTimeout(() => {
      //   document.getElementById('sala_fk').focus();
      // }, 500);
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
   calendarEl.style.maxHeight = '600px';
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
