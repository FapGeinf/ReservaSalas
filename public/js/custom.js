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
      events: 
    []
    });

    calendar.render();
  });