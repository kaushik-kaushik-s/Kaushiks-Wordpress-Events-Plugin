document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('ed-calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: edEventsData,
        eventClick: function(info) {
            window.location.href = info.event.url;
            info.jsEvent.preventDefault();
        }
    });

    calendar.render();
});
