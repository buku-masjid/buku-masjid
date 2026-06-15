const test = require('node:test');
const assert = require('node:assert/strict');

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
    isActiveModalState,
} = require('../../../public/js/public_display/modal-state');

test('createIqamahState stores countdown end timestamps and shalat key', () => {
    const now = 1_700_000_000_000;
    const countdownEndsAt = now + 90_000;

    const state = createIqamahState('maghrib', countdownEndsAt, now);

    assert.equal(state.type, 'iqamah');
    assert.equal(state.shalatKey, 'maghrib');
    assert.equal(state.startedAt, now);
    assert.equal(state.endsAt, countdownEndsAt);
    assert.equal(state.countdownEndsAt, countdownEndsAt);
});

test('createShalatState stores visibility timing for the active prayer', () => {
    const now = 1_700_000_000_000;
    const endsAt = now + 10 * 60 * 1000;

    const state = createShalatState('isya', endsAt, now);

    assert.deepEqual(state, {
        type: 'shalat',
        shalatKey: 'isya',
        startedAt: now,
        endsAt,
    });
});

test('createFridayState stores friday timing without a shalat key', () => {
    const now = 1_700_000_000_000;
    const endsAt = now + 40 * 60 * 1000;

    const state = createFridayState(endsAt, now);

    assert.deepEqual(state, {
        type: 'friday',
        startedAt: now,
        endsAt,
    });
});

test('getRemainingSeconds rounds up and never returns a negative value', () => {
    assert.equal(getRemainingSeconds(5_000, 0), 5);
    assert.equal(getRemainingSeconds(5_000, 4_001), 1);
    assert.equal(getRemainingSeconds(5_000, 5_001), 0);
});

test('getRemainingMilliseconds preserves exact remaining time and floors at zero', () => {
    assert.equal(getRemainingMilliseconds(5_000, 0), 5_000);
    assert.equal(getRemainingMilliseconds(5_000, 4_001), 999);
    assert.equal(getRemainingMilliseconds(5_000, 5_001), 0);
});

test('formatCountdown renders remaining milliseconds as mm:ss for modal review timers', () => {
    assert.equal(formatCountdown(125_000), '02:05');
    assert.equal(formatCountdown(59_000), '00:59');
    assert.equal(formatCountdown(0), '00:00');
});

test('parseModalState rejects malformed JSON and invalid modal shapes', () => {
    assert.equal(parseModalState('{bad json'), null);
    assert.equal(parseModalState(JSON.stringify({ type: 'unknown' })), null);
    assert.equal(parseModalState(JSON.stringify({ type: 'shalat', endsAt: 123 })), null);
});

test('parseModalState accepts valid iqamah, shalat, and friday states', () => {
    const iqamah = createIqamahState('fajr', 10_000, 1_000);
    const shalat = createShalatState('dzuhr', 20_000, 2_000);
    const friday = createFridayState(30_000, 3_000);

    assert.deepEqual(parseModalState(JSON.stringify(iqamah)), iqamah);
    assert.deepEqual(parseModalState(JSON.stringify(shalat)), shalat);
    assert.deepEqual(parseModalState(JSON.stringify(friday)), friday);
});

test('isActiveModalState reports whether a persisted modal has expired', () => {
    const active = createShalatState('ashr', 20_000, 1_000);
    const expired = createFridayState(9_000, 1_000);

    assert.equal(isActiveModalState(active, 19_999), true);
    assert.equal(isActiveModalState(active, 20_000), false);
    assert.equal(isActiveModalState(expired, 10_000), false);
});

test('hasModalExpired reports when a modal should be force-closed on restore', () => {
    assert.equal(hasModalExpired(10_000, 9_999), false);
    assert.equal(hasModalExpired(10_000, 10_000), true);
    assert.equal(hasModalExpired(10_000, 10_001), true);
});

test('exports the shared storage key used by browser persistence', () => {
    assert.equal(STORAGE_KEY, 'public_display_active_modal');
});
