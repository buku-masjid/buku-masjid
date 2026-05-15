const STORAGE_KEY = 'public_display_active_modal';
const VALID_MODAL_TYPES = ['iqamah', 'shalat', 'friday'];

function isFiniteNumber(value) {
    return typeof value === 'number' && Number.isFinite(value);
}

function createIqamahState(shalatKey, countdownEndsAt, now = Date.now()) {
    return {
        type: 'iqamah',
        shalatKey,
        startedAt: now,
        endsAt: countdownEndsAt,
        countdownEndsAt,
    };
}

function createShalatState(shalatKey, endsAt, now = Date.now()) {
    return {
        type: 'shalat',
        shalatKey,
        startedAt: now,
        endsAt,
    };
}

function createFridayState(endsAt, now = Date.now()) {
    return {
        type: 'friday',
        startedAt: now,
        endsAt,
    };
}

function getRemainingSeconds(endsAt, now = Date.now()) {
    return Math.max(0, Math.ceil((endsAt - now) / 1000));
}

function getRemainingMilliseconds(endsAt, now = Date.now()) {
    return Math.max(0, endsAt - now);
}

function formatCountdown(remainingMilliseconds) {
    const totalSeconds = Math.max(0, Math.ceil(remainingMilliseconds / 1000));
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;

    return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
}

function hasModalExpired(endsAt, now = Date.now()) {
    return now >= endsAt;
}

function isValidBaseState(state) {
    return Boolean(
        state &&
        VALID_MODAL_TYPES.includes(state.type) &&
        isFiniteNumber(state.startedAt) &&
        isFiniteNumber(state.endsAt)
    );
}

function isValidIqamahState(state) {
    return isValidBaseState(state)
        && typeof state.shalatKey === 'string'
        && state.shalatKey.length > 0
        && isFiniteNumber(state.countdownEndsAt);
}

function isValidShalatState(state) {
    return isValidBaseState(state)
        && typeof state.shalatKey === 'string'
        && state.shalatKey.length > 0;
}

function isValidFridayState(state) {
    return isValidBaseState(state);
}

function parseModalState(rawState) {
    try {
        const parsedState = JSON.parse(rawState);
        if (parsedState.type === 'iqamah' && isValidIqamahState(parsedState)) {
            return parsedState;
        }
        if (parsedState.type === 'shalat' && isValidShalatState(parsedState)) {
            return parsedState;
        }
        if (parsedState.type === 'friday' && isValidFridayState(parsedState)) {
            return parsedState;
        }
    } catch (error) {
        return null;
    }

    return null;
}

function isActiveModalState(state, now = Date.now()) {
    return Boolean(isValidBaseState(state) && !hasModalExpired(state.endsAt, now));
}

module.exports = {
    STORAGE_KEY,
    createIqamahState,
    createShalatState,
    createFridayState,
    formatCountdown,
    getRemainingMilliseconds,
    getRemainingSeconds,
    hasModalExpired,
    parseModalState,
    isActiveModalState,
};
