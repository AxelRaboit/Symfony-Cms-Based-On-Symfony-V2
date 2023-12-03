document.addEventListener('DOMContentLoaded', function () {
    const franceHourElement = document.getElementById("dashboard-france-hour");
    const quebecHourElement = document.getElementById("dashboard-quebec-hour");
    const losAngelesHourElement = document.getElementById("dashboard-los-angeles-hour");
    const taiwanHourElement = document.getElementById("dashboard-taiwan-hour");
    const japanHourElement = document.getElementById("dashboard-japan-hour");
    const londonHourElement = document.getElementById("dashboard-london-hour");

    const timezones = {
        paris: 'Europe/Paris',
        london: 'Europe/London',
        quebec: 'America/Toronto',
        losAngeles: 'America/Los_Angeles',
        taiwan: 'Asia/Taipei',
        japan: 'Asia/Tokyo'
    };

    /**
     * Update the hour for a given timezone
     * @param element
     * @param timezone
     */
    function updateLocalHour(element, timezone) {
        element.innerHTML = new Date().toLocaleTimeString('fr-FR', { timeZone: timezone });
    }

    if (franceHourElement && londonHourElement && quebecHourElement && losAngelesHourElement && taiwanHourElement && japanHourElement) {
        // Update current hour immediately
        updateLocalHour(franceHourElement, timezones.paris);
        updateLocalHour(londonHourElement, timezones.london);
        updateLocalHour(quebecHourElement, timezones.quebec);
        updateLocalHour(losAngelesHourElement, timezones.losAngeles);
        updateLocalHour(taiwanHourElement, timezones.taiwan);
        updateLocalHour(japanHourElement, timezones.japan);

        // Update current hour every second
        setInterval(function() {
            updateLocalHour(franceHourElement, timezones.paris);
            updateLocalHour(londonHourElement, timezones.london);
            updateLocalHour(quebecHourElement, timezones.quebec);
            updateLocalHour(losAngelesHourElement, timezones.losAngeles);
            updateLocalHour(taiwanHourElement, timezones.taiwan);
            updateLocalHour(japanHourElement, timezones.japan);
        }, 1000);
    }
});
