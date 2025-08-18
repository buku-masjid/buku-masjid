document.addEventListener('DOMContentLoaded', function() {
    function updateTimeInfoNextShalat(shalatTimeData) {
        document.querySelectorAll("[data-time]").forEach((element) => {
            element.textContent = shalatTimeData.schedules[element.dataset.time];
        });

        const now = new Date();
        const currentMinutes = now.getHours() * 60 + now.getMinutes();
        const currentSeconds = 59 - now.getSeconds() ;

        for (let prop in shalatTimeData.schedules) {
            const value = shalatTimeData.schedules[prop];
            if (value.match(/^\d{2,}:\d{2}$/)) {
                const [hour, minute] = value.split(":").map(Number);
                if (hour * 60 + minute > currentMinutes) {
                    nextShalatTime = prop;
                    break;
                }
            }
        }

        document.getElementById('timeID').textContent = shalatDailySchedule[nextShalatTime];

        const elements = document.getElementsByClassName("jm-card");
        for (let i = 0; i < elements.length; i++) {
            elements[i].classList.remove("jm-card-active");
        }

        const element = document.getElementById(nextShalatTime);
        element.classList.add("jm-card-active");

        const [nextHour, nextMinute] = shalatTimeData.schedules[nextShalatTime].split(":").map(Number);
        const nextMinutes = nextHour * 60 + nextMinute;

        let remainingMinutes = nextMinutes - currentMinutes;
        if (remainingMinutes < 0) {
            remainingMinutes += 24 * 60;
        }

        const hoursLeft = Math.floor(remainingMinutes / 60);
        const minutesLeft = (remainingMinutes % 60) - 1;

        function addLeadingZero(number) {
            return (number < 10 ? '0' : '') + number;
        }

        document.getElementById('timeRemaining').textContent = addLeadingZero(hoursLeft) + " : " + addLeadingZero(minutesLeft) + " : " + addLeadingZero(currentSeconds); // Get seconds here!
    }

    // Ref: https://developer.mozilla.org/en-US/docs/Web/API/Window/setTimeout
    setInterval(updateTimeInfoNextShalat, 1000, shalatTimeData);
});