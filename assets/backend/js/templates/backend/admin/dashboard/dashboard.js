document.addEventListener('DOMContentLoaded', function () {
    const currentHourElement = document.getElementById("dashboard-current-hour");

    if (currentHourElement) {
        // Function to update the hour
        function updateCurrentHour() {
            const currentHour = new Date();
            let hours = currentHour.getHours();
            let minutes = currentHour.getMinutes();
            let seconds = currentHour.getSeconds();

            // Add a zero in front of the minutes and seconds if they are less than 10
            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            // Update HTML element with formatted hour
            currentHourElement.innerHTML = hours + ":" + minutes + ":" + seconds;
        }

        // Update current hour immediately
        updateCurrentHour();

        // Update current hour every second
        setInterval(updateCurrentHour, 1000);
    }
});