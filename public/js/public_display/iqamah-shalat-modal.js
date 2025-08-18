// Iqamah and Shalat Modal Logic
document.addEventListener('DOMContentLoaded', function() {
    const shalatModal = document.getElementById('shalatModal');
    const iqamahIntervalModal = document.getElementById('iqamahIntervalModal');
    const fridayModal = document.getElementById('fridayModal');
    const iqamahIntervalCountdown = document.getElementById('iqamahIntervalCountdown');
    const timeRemainingElement = document.getElementById('timeRemaining');
    let startTimeout, endTimeout, countdownInterval;

    const now = new Date();
    const currentMinutes = now.getHours() * 60 + now.getMinutes();
    const currentSeconds = 60 - now.getSeconds() ;

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

    // Clear any existing intervals/timeouts on page load
    if (countdownInterval) clearInterval(countdownInterval);
    if (startTimeout) clearTimeout(startTimeout);
    if (endTimeout) clearTimeout(endTimeout);

    // Check countdown timer
    function checkCountdown() {
        // console.log('start');
        if (timeRemainingElement) {
            // Remove spaces and check both formats
            const timeText = timeRemainingElement.textContent.replace(/\s+/g, '');
            if (timeText === '00:00:03' || timeText === '00:03') {
                // Ref https://github.com/fahroniganteng/Display-Masjid/blob/d66505ac6ae41d12dbb1d0b748daea4d38321346/display/index.php#L509-L513
                audio.play().then(() => {}).catch(() => {
                    console.log('Agar beep bunyi ==> permission browser : sound harus enable');
                });
            }

            if (timeText === '00:00:00' || timeText === '00:00') {
                // Clear any existing timeouts
                if (startTimeout) clearTimeout(startTimeout);
                if (endTimeout) clearTimeout(endTimeout);

                // --- SPECIAL CASE: Friday Ashar ---
                const today = new Date();
                const isFriday = today.getDay() === 5;

                if (isFriday && nextShalatTime === 'dzuhr') {
                    // Show Friday modal directly, skip interlude
                    if (fridayModal) {
                        fridayModal.classList.add('show');
                        endTimeout = setTimeout(() => {
                            fridayModal.classList.remove('show');
                        }, (window.shalatIntervalInMinutes.friday || 10) * 60 * 1000); // default 10 min if not set
                    }
                    return; // Stop further execution
                }

                // Show interlude modal with countdown
                if (iqamahIntervalModal && window.iqamahIntervalInMinutes[nextShalatTime]) {
                    iqamahIntervalModal.classList.add('show');
                    let countdown = window.iqamahIntervalInMinutes[nextShalatTime] * 60; // Convert to seconds

                    // Update countdown every second
                    countdownInterval = setInterval(() => {
                        const minutes = Math.floor(countdown / 60);
                        const seconds = countdown % 60;
                        iqamahIntervalCountdown.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                        if (countdown == 3) {
                            audio.play();
                        }

                        if (countdown <= 0) {
                            clearInterval(countdownInterval);
                            iqamahIntervalModal.classList.remove('show');

                            // Wait for fade-out animation to complete before showing next modal
                            setTimeout(() => {
                                // Show prayer modal
                                if (shalatModal) {
                                    shalatModal.classList.add('show');
                                    // Schedule modal to hide after shalatIntervalInMinutes minutes
                                    endTimeout = setTimeout(() => {
                                        // Add fade-out effect
                                        shalatModal.classList.remove('show');
                                    }, window.shalatIntervalInMinutes[nextShalatTime] * 60 * 1000);
                                }
                            }, 500);
                        }
                        countdown--;
                    }, 1000);
                    // console.log('Timer reached zero, scheduling modal');
                }
            }
        }
    }

    // Check every second
    setInterval(checkCountdown, 1000);
});