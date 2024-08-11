document.addEventListener('DOMContentLoaded', function () {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    // initialDate: '2023-01-12',
    initialView: 'timeGridWeek',
    headerToolbar: {
      left: 'prev,next,today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
    },
    height: 'auto',
    navLinks: true, // can click day/week names to navigate views
    editable: true,
    locale: 'pt-br',
    selectable: true,
    selectMirror: true,
    nowIndicator: true,
    events: './class/controller/calendario.php',
  });

  calendar.render();
});
