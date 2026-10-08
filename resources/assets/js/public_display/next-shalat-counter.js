(function(root, factory) {
    root.PublicDisplayNextShalatCounter = factory(root);
})(window, function(root) {
    function init(config) {
        const scheduleHelper = root.PublicDisplayShalatSchedule;
        if (!scheduleHelper) {
            console.error('Public display shalat schedule helpers are not loaded.');
            return null;
        }

        if (!config || !config.shalatTimeData || !config.shalatTimeData.schedules) {
            return null;
        }

        if (config.nextShalatCounterInterval) {
            return config.nextShalatCounterInterval;
        }

        function updateTimeInfoNextShalat() {
            const shalatTimeData = config.shalatTimeData;

            document.querySelectorAll('[data-time]').forEach((element) => {
                element.textContent = shalatTimeData.schedules[element.dataset.time];
            });

            config.nextShalatTime = scheduleHelper.findNextShalatTime(
                shalatTimeData.schedules,
                new Date(),
                config.nextShalatTime || 'imsak'
            );

            const timeID = document.getElementById('timeID');
            if (timeID) {
                timeID.textContent = config.shalatDailySchedule[config.nextShalatTime];
            }

            const elements = document.getElementsByClassName('jm-card');
            for (let i = 0; i < elements.length; i++) {
                elements[i].classList.remove('jm-card-active');
            }

            const element = document.getElementById(config.nextShalatTime);
            if (element) {
                element.classList.add('jm-card-active');
            }

            const timeRemaining = document.getElementById('timeRemaining');
            if (timeRemaining) {
                timeRemaining.textContent = scheduleHelper.formatTimeParts(
                    scheduleHelper.getRemainingTimeParts(shalatTimeData.schedules[config.nextShalatTime], new Date())
                );
            }
        }

        updateTimeInfoNextShalat();
        config.nextShalatCounterInterval = setInterval(updateTimeInfoNextShalat, 1000);

        return config.nextShalatCounterInterval;
    }

    function initWhenReady() {
        const config = root.PublicDisplayConfig;

        if (!config) {
            return;
        }

        if (config.shalatTimeData && config.shalatTimeData.schedules) {
            init(config);
            return;
        }

        root.addEventListener('PublicDisplayShalatTimeDataReady', () => init(config), { once: true });
    }

    document.addEventListener('DOMContentLoaded', initWhenReady);

    return {
        init,
    };
});
