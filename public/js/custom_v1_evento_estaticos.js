// Executar quando o documento HTML for completatamente carregado
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },

    //   definir idioma usado no calendário
      locale: 'pt-br',


      initialDate: '2023-01-12',
    //  initialDate: '2025-03-27',


    //  para permitir clicar nos nomes dos dias da semana
      navLinks: true, 

    //   para permitir clicar e arrastar para selecionar um intervalo de tempo
      selectable: true,

    //   visualisar a area que será selecionada antes  que o usuario solte o botão do mouse para confirmar a seleção
      selectMirror: true,


    //   select: function(arg) {
    //     var title = prompt('Event Title:');
    //     if (title) {
    //       calendar.addEvent({
    //         title: title,
    //         start: arg.start,
    //         end: arg.end,
    //         allDay: arg.allDay
    //       })
    //     }
    //     calendar.unselect()
    //   },
    //   eventClick: function(arg) {
    //     if (confirm('Are you sure you want to delete this event?')) {
    //       arg.event.remove()
    //     }
    //   },


      editable: true,

    //   numero maximo de eventos que podem ser exibidos em um dia, se for true, o numero de eventos 
    // será limitado a altura da celula do dia
      dayMaxEvents: true, 
      events: [
        {
          title: 'All Day Event',
          start: '2023-01-01'
        },
        {
          title: 'Long Event',
          start: '2023-01-07',
          end: '2023-01-10'
        },
        {
          groupId: 999,
          title: 'Repeating Event',
          start: '2023-01-09T16:00:00'
        },
        {
          groupId: 999,
          title: 'Repeating Event',
          start: '2023-01-16T16:00:00'
        },
        {
          title: 'Conference',
          start: '2023-01-11',
          end: '2023-01-13'
        },
        {
          title: 'Meeting',
          start: '2023-01-12T10:30:00',
          end: '2023-01-12T12:30:00'
        },
        {
          title: 'Lunch',
          start: '2023-01-12T12:00:00'
        },
        {
          title: 'Meeting',
          start: '2023-01-12T14:30:00'
        },
        {
          title: 'Happy Hour',
          start: '2023-01-12T17:30:00'
        },
        {
          title: 'Dinner',
          start: '2023-01-12T20:00:00'
        },
        {
          title: 'Birthday Party',
          start: '2023-01-13T07:00:00'
        },
        {
          title: 'Click for Google',
          url: 'http://google.com/',
          start: '2023-01-28'
        }
      ]
    });

    calendar.render();
  });