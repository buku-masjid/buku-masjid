(function(root, factory) {
    root.PublicDisplayIqamahShalatModal = factory(root);
})(window, function(root) {
    function init(config) {
        const modalState = root.PublicDisplayModalState;
        const scheduleHelper = root.PublicDisplayShalatSchedule;
        if (!modalState) {
            console.error('Public display modal state helpers are not loaded.');
            return null;
        }
        if (!scheduleHelper) {
            console.error('Public display shalat schedule helpers are not loaded.');
            return null;
        }
        if (!config || !config.shalatTimeData || !config.shalatTimeData.schedules) {
            return null;
        }
        if (config.iqamahShalatModalInitialized) {
            return null;
        }
        config.iqamahShalatModalInitialized = true;

        const {
            STORAGE_KEY,
            createIqamahState,
            createShalatState,
            createFridayState,
            formatCountdown,
            getRemainingMilliseconds,
            getRemainingSeconds,
            hasModalExpired,
            parseModalState,
        } = modalState;
        const shalatModal = document.getElementById('shalatModal');
        const iqamahIntervalModal = document.getElementById('iqamahIntervalModal');
        const fridayModal = document.getElementById('fridayModal');
        const iqamahIntervalCountdown = document.getElementById('iqamahIntervalCountdown');
        const shalatModalCountdown = document.getElementById('shalatModalCountdown');
        const timeRemainingElement = document.getElementById('timeRemaining');
        let endTimeout = null;
        let countdownInterval = null;
        let closeWatcherInterval = null;
        let shalatCountdownInterval = null;
        let activeModalType = null;

        function playAudio() {
            if (!config.audio) {
                return;
            }

            config.audio.play().catch(() => {
                console.log('Agar beep bunyi ==> permission browser : sound harus enable');
            });
        }

        function saveModalState(state) {
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify(state));
        }

        function getModalState() {
            return parseModalState(sessionStorage.getItem(STORAGE_KEY));
        }

        function clearModalState() {
            sessionStorage.removeItem(STORAGE_KEY);
        }

        function clearActiveTimers() {
            if (countdownInterval) {
                clearInterval(countdownInterval);
                countdownInterval = null;
            }
            if (closeWatcherInterval) {
                clearInterval(closeWatcherInterval);
                closeWatcherInterval = null;
            }
            if (shalatCountdownInterval) {
                clearInterval(shalatCountdownInterval);
                shalatCountdownInterval = null;
            }
            if (endTimeout) {
                clearTimeout(endTimeout);
                endTimeout = null;
            }
        }

        function hideAllModals() {
            [iqamahIntervalModal, shalatModal, fridayModal].forEach((modal) => {
                if (modal) {
                    modal.classList.remove('show');
                }
            });
        }

        function resetActiveModal() {
            activeModalType = null;
            if (shalatModalCountdown) {
                shalatModalCountdown.textContent = '00:00';
            }
        }

        function renderShalatCountdown(endsAt) {
            if (!shalatModalCountdown) {
                return;
            }

            shalatModalCountdown.textContent = formatCountdown(getRemainingMilliseconds(endsAt, Date.now()));
        }

        function startModalCloseWatcher(modalElement, endsAt) {
            function closeModal() {
                modalElement.classList.remove('show');
                clearModalState();
                clearActiveTimers();
                resetActiveModal();
            }

            const remainingMs = getRemainingMilliseconds(endsAt, Date.now());
            endTimeout = setTimeout(closeModal, remainingMs);
            closeWatcherInterval = setInterval(() => {
                if (hasModalExpired(endsAt, Date.now())) {
                    closeModal();
                }
            }, 1000);
        }

        function ensureNextShalatTime() {
            config.nextShalatTime = scheduleHelper.findNextShalatTime(
                config.shalatTimeData.schedules,
                new Date(),
                config.nextShalatTime || 'imsak'
            );
        }

        function showShalatModal(shalatKey, endsAt) {
            if (!shalatModal) {
                clearModalState();
                resetActiveModal();
                return;
            }

            clearActiveTimers();
            hideAllModals();
            activeModalType = 'shalat';
            saveModalState(createShalatState(shalatKey, endsAt, Date.now()));
            shalatModal.classList.add('show');
            renderShalatCountdown(endsAt);
            shalatCountdownInterval = setInterval(() => {
                renderShalatCountdown(endsAt);
            }, 1000);
            startModalCloseWatcher(shalatModal, endsAt);
        }

        function showFridayModal(endsAt) {
            if (!fridayModal) {
                clearModalState();
                resetActiveModal();
                return;
            }

            clearActiveTimers();
            hideAllModals();
            activeModalType = 'friday';
            saveModalState(createFridayState(endsAt, Date.now()));
            fridayModal.classList.add('show');
            startModalCloseWatcher(fridayModal, endsAt);
        }

        function showIqamahModal(shalatKey, countdownEndsAt) {
            if (!iqamahIntervalModal || !iqamahIntervalCountdown) {
                clearModalState();
                resetActiveModal();
                return;
            }

            clearActiveTimers();
            hideAllModals();
            activeModalType = 'iqamah';
            saveModalState(createIqamahState(shalatKey, countdownEndsAt, Date.now()));
            iqamahIntervalModal.classList.add('show');

            function renderCountdown() {
                const remainingSeconds = getRemainingSeconds(countdownEndsAt, Date.now());
                const minutes = Math.floor(remainingSeconds / 60);
                const seconds = remainingSeconds % 60;
                iqamahIntervalCountdown.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

                if (remainingSeconds === 3) {
                    playAudio();
                }

                if (remainingSeconds <= 0) {
                    clearActiveTimers();
                    clearModalState();
                    iqamahIntervalModal.classList.remove('show');
                    const shalatEndsAt = Date.now() + (config.shalatIntervalInMinutes[shalatKey] || 0) * 60 * 1000;
                    setTimeout(() => {
                        showShalatModal(shalatKey, shalatEndsAt);
                    }, 500);
                }
            }

            renderCountdown();
            countdownInterval = setInterval(renderCountdown, 1000);
        }

        function restoreModalState() {
            const savedState = getModalState();
            if (!savedState) {
                clearModalState();
                return false;
            }

            if (hasModalExpired(savedState.endsAt, Date.now())) {
                clearModalState();
                resetActiveModal();
                return false;
            }

            if (savedState.type === 'iqamah') {
                showIqamahModal(savedState.shalatKey, savedState.countdownEndsAt);
                return true;
            }
            if (savedState.type === 'shalat') {
                showShalatModal(savedState.shalatKey, savedState.endsAt);
                return true;
            }
            if (savedState.type === 'friday') {
                showFridayModal(savedState.endsAt);
                return true;
            }

            clearModalState();
            resetActiveModal();
            return false;
        }

        function checkCountdown() {
            if (!timeRemainingElement || activeModalType) {
                return;
            }

            const timeText = timeRemainingElement.textContent.replace(/\s+/g, '');
            if (timeText === '00:00:03' || timeText === '00:03') {
                playAudio();
            }

            if (timeText !== '00:00:00' && timeText !== '00:00') {
                return;
            }

            clearActiveTimers();
            ensureNextShalatTime();
            const today = new Date();
            const isFriday = today.getDay() === 5;

            if (isFriday && config.nextShalatTime === 'dzuhr') {
                const fridayEndsAt = Date.now() + (config.shalatIntervalInMinutes.friday || 10) * 60 * 1000;
                showFridayModal(fridayEndsAt);
                return;
            }

            const iqamahMinutes = config.iqamahIntervalInMinutes[config.nextShalatTime];
            if (!iqamahMinutes) {
                return;
            }

            const iqamahEndsAt = Date.now() + iqamahMinutes * 60 * 1000;
            showIqamahModal(config.nextShalatTime, iqamahEndsAt);
        }

        ensureNextShalatTime();
        clearActiveTimers();

        if (restoreModalState()) {
            return null;
        }

        config.iqamahShalatModalInterval = setInterval(checkCountdown, 1000);

        return config.iqamahShalatModalInterval;
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
