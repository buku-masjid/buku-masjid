// Prayer Modal Iqumah Logic
document.addEventListener('DOMContentLoaded', function() {
    const prayerModal = document.getElementById('prayerModal');
    const prayerInterludeModal = document.getElementById('prayerInterludeModal');
    const interludeCountdown = document.getElementById('interludeCountdown');
    const timeRemainingElement = document.getElementById('timeRemaining');
    let startTimeout, endTimeout, countdownInterval;

    // Clear any existing intervals/timeouts on page load
    if (countdownInterval) clearInterval(countdownInterval);
    if (startTimeout) clearTimeout(startTimeout);
    if (endTimeout) clearTimeout(endTimeout);

    // Toggle modal visibility
    function toggleModal() {
        if (prayerInterludeModal) {
            prayerInterludeModal.classList.toggle('show');
        }
    }

    // Handle spacebar press
    document.addEventListener('keydown', function(event) {
        if (event.code === 'Space' && window.location.pathname === '/jam-masjid') {
            event.preventDefault();
            toggleModal();
        }
    });

    // Check countdown timer
    function checkCountdown() {
        console.log('start');
        if (timeRemainingElement) {
            console.log('Current time:', timeRemainingElement.textContent);
            // Remove spaces and check both formats
            const timeText = timeRemainingElement.textContent.replace(/\s+/g, '');
            if (timeText === '00:00:00' || timeText === '00:00') {
                // Clear any existing timeouts
                if (startTimeout) clearTimeout(startTimeout);
                if (endTimeout) clearTimeout(endTimeout);

                // Show interlude modal with countdown
                if (prayerInterludeModal) {
                    prayerInterludeModal.classList.add('show');
                    let countdown = window.prayerStartIn * 60; // Convert to seconds
                    
                    // Update countdown every second
                    countdownInterval = setInterval(() => {
                        const minutes = Math.floor(countdown / 60);
                        const seconds = countdown % 60;
                        interludeCountdown.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                        
                        if (countdown <= 0) {
                            clearInterval(countdownInterval);
                            prayerInterludeModal.classList.remove('show');
                            
                            // Wait for fade-out animation to complete before showing next modal
                            setTimeout(() => {
                                // Show prayer modal
                                if (prayerModal) {
                                    prayerModal.classList.add('show');
                                    // Schedule modal to hide after prayerEndIn minutes
                                    endTimeout = setTimeout(() => {
                                        // Add fade-out effect
                                        prayerModal.classList.remove('show');
                                    }, window.prayerEndIn * 60 * 1000);
                                }
                            }, 500);
                        }
                        countdown--;
                    }, 1000);
                    console.log('Timer reached zero, scheduling modal');
                }
            }
        }
    }

    // Check every second
    setInterval(checkCountdown, 1000);
});