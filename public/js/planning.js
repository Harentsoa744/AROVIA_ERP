// planning.js — Initialisation du calendrier FullCalendar avec les vraies livraisons

document.addEventListener('DOMContentLoaded', function () {

    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {

        // Paramètres de base
        locale: 'fr',
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today: "Aujourd'hui",
            month: 'Mois',
            week:  'Semaine',
            list:  'Liste'
        },

        // Chargement des événements depuis le serveur
        events: function (fetchInfo, successCallback, failureCallback) {
            fetch('/planning/events')
                .then(function (response) { return response.json(); })
                .then(function (data) { successCallback(data); })
                .catch(function (error) {
                    console.error('Erreur chargement événements:', error);
                    failureCallback(error);
                });
        },

        // Couleur par statut via className
        eventDidMount: function (info) {
            var statut = info.event.extendedProps.statut || '';
            var className = 'evt-' + statut.toLowerCase().replace(/_/g, '-');
            info.el.classList.add(className);
        },

        // Clic sur un événement → redirection vers les détails
        eventClick: function (info) {
            var id = info.event.id;
            if (id) {
                window.location.href = '/planning/details/' + id;
            }
        },

        // Affichage
        height: 'auto',
        dayMaxEvents: 3,
        navLinks: true
    });

    calendar.render();
});
