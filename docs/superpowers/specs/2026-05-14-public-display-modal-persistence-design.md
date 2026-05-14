# Public Display Modal Persistence Design

## Goal

Persist the active modal state for the public display (`/display`) so that when the page refreshes on the TV screen, the current modal remains visible and resumes from the correct remaining time.

This applies to:

- `iqamahIntervalModal`
- `shalatModal`
- `fridayModal`

The solution must:

- Resume from the exact remaining seconds after refresh.
- Clear automatically when the original modal end condition is reached.
- Persist only for the current browser tab/session.
- Avoid backend or database changes.

## Current Context

The display page is rendered from:

- `resources/views/layouts/public_display.blade.php`

Current modal behavior is controlled in:

- `public/js/public_display/iqamah-shalat-modal.js`

Supporting display timing is driven by:

- `public/js/public_display/next-shalat-counter.js`

Current behavior:

- The page fetches `shalatTimeData` and computes `nextShalatTime`.
- When the countdown reaches zero, the code shows either:
  - `fridayModal` directly on Friday `dzuhr`, or
  - `iqamahIntervalModal`, followed by `shalatModal`.
- Modal visibility exists only in memory, so a page refresh clears the current modal state.

## Requirements

### Functional

- If refresh happens while `iqamahIntervalModal` is showing, the modal must reappear and continue from the correct remaining seconds.
- If refresh happens while `shalatModal` is showing, the modal must reappear and stay visible until its original end time.
- If refresh happens while `fridayModal` is showing, the modal must reappear and stay visible until its original end time.
- When a modal has expired, any persisted state must be removed automatically.
- Persistence must be scoped to the current browser session.

### Non-Functional

- Keep the implementation fully client-side.
- Follow the existing public display JS structure.
- Minimize changes to Blade markup.
- Keep the logic understandable for future maintenance.

## Evaluated Approaches

### 1. `sessionStorage` with absolute timestamps

Store the active modal type and timing metadata in `sessionStorage`, using absolute timestamps to calculate remaining time after refresh.

Pros:

- Matches current-tab/current-session behavior.
- Supports exact remaining-time recovery.
- Does not require backend changes.
- Simple to debug in browser devtools.

Cons:

- State is local to one browser tab, not synchronized across multiple displays.

### 2. `localStorage` with daily keying

Use the same model as above, but persist in `localStorage`.

Pros:

- Survives browser restarts.

Cons:

- Too persistent for the kiosk requirement.
- Requires extra cleanup logic to avoid stale modals.

### 3. Backend-backed state

Save modal state on the server and restore it on page load.

Pros:

- Could synchronize across multiple clients.

Cons:

- Adds unnecessary complexity.
- Requires server-side data flow for a purely display-side problem.

## Chosen Approach

Use `sessionStorage` with absolute timestamps.

This is the best fit because the display is expected to run in a single TV browser tab, and the state should survive refresh but not persist indefinitely beyond the session.

## Design

### Storage Model

Persist a single object under a dedicated key:

- `public_display_active_modal`

Example payload:

```json
{
  "type": "iqamah",
  "shalatKey": "maghrib",
  "startedAt": 1778750000000,
  "endsAt": 1778750900000,
  "countdownEndsAt": 1778750900000
}
```

Field definitions:

- `type`: one of `iqamah`, `shalat`, or `friday`
- `shalatKey`: the prayer code associated with the modal, when relevant
- `startedAt`: when the modal started
- `endsAt`: when the modal should no longer be shown
- `countdownEndsAt`: used by `iqamah` to restore the exact countdown

Notes:

- For `iqamah`, `endsAt` and `countdownEndsAt` are the same timestamp.
- For `shalat` and `friday`, `endsAt` is sufficient for restore logic.

### JS Responsibilities

Refactor `public/js/public_display/iqamah-shalat-modal.js` so modal transitions go through helper functions instead of directly adding/removing the `show` class.

Introduce helper functions:

- `saveModalState(state)`
- `getModalState()`
- `clearModalState()`
- `hideAllModals()`
- `showIqamahModal(shalatKey, countdownEndsAt)`
- `showShalatModal(shalatKey, endsAt)`
- `showFridayModal(endsAt)`
- `restoreModalState()`

These helpers will centralize:

- DOM show/hide behavior
- `sessionStorage` writes and deletes
- timeout and interval cleanup
- restore handling on refresh

### Restore Flow On Page Load

On `DOMContentLoaded`:

1. Load and validate persisted state from `sessionStorage`.
2. If no valid state exists, continue with the current countdown-driven behavior.
3. If state exists but `Date.now() >= endsAt`, clear it and continue normally.
4. If state exists and is still active:
   - For `iqamah`, show `iqamahIntervalModal` and resume countdown using `countdownEndsAt - Date.now()`.
   - For `shalat`, show `shalatModal` and keep it visible until `endsAt`.
   - For `friday`, show `fridayModal` and keep it visible until `endsAt`.
5. When the restored modal reaches its end, clear the saved state and continue the normal sequence.

### Modal Transition Rules

#### Iqamah Modal

When the main countdown reaches zero for a non-Friday prayer flow:

1. Compute `countdownEndsAt = Date.now() + iqamahIntervalInMinutes[nextShalatTime] * 60 * 1000`.
2. Save the modal state with type `iqamah`.
3. Show `iqamahIntervalModal`.
4. Every second, compute the remaining seconds from `countdownEndsAt`.
5. Update `#iqamahIntervalCountdown`.
6. When countdown ends:
   - clear the interval
   - clear `iqamah` state
   - hide `iqamahIntervalModal`
   - transition to `shalatModal`

#### Shalat Modal

When iqamah completes:

1. Compute `endsAt = Date.now() + shalatIntervalInMinutes[nextShalatTime] * 60 * 1000`.
2. Save type `shalat`.
3. Show `shalatModal`.
4. Schedule hide based on `endsAt`.
5. When time is up:
   - hide the modal
   - clear persisted state

#### Friday Modal

When the main countdown reaches zero on Friday and `nextShalatTime === 'dzuhr'`:

1. Compute `endsAt = Date.now() + shalatIntervalInMinutes.friday * 60 * 1000`.
2. Save type `friday`.
3. Show `fridayModal`.
4. Schedule hide based on `endsAt`.
5. When time is up:
   - hide the modal
   - clear persisted state

## Data Flow

Normal path:

1. Page loads.
2. `shalatTimeData` is available.
3. Countdown reaches zero.
4. Modal helper shows the right modal and writes state to `sessionStorage`.
5. Modal ends and helper clears the saved state.

Refresh path:

1. Page reloads.
2. `restoreModalState()` reads `sessionStorage`.
3. If active state exists, it reconstructs the modal UI from timestamps.
4. Countdown or visibility resumes from the saved end time.
5. State is cleared when the original window ends.

## Error Handling

- If `sessionStorage` data is missing or malformed, ignore it and clear the key.
- If a modal referenced by saved state is not found in the DOM, clear the key and fail safely.
- If restored timestamps are already expired, clear the key and skip restore.
- If `shalatKey` is missing for `iqamah` or `shalat`, clear the key and skip restore.

## Testing Strategy

### Manual Verification

Test these scenarios in the browser:

1. Trigger `iqamahIntervalModal`, refresh, confirm countdown resumes from the remaining seconds.
2. Trigger `shalatModal`, refresh, confirm it remains visible and hides at the original end time.
3. Trigger `fridayModal`, refresh, confirm it remains visible and hides at the original end time.
4. Refresh after modal expiry, confirm no stale modal is restored.
5. Close the tab/session and reopen, confirm the state is gone.

### Automated Test Scope

No automated tests are required in this design phase.

If implementation testing is added later, the highest-value checks would be focused browser-level tests around state restoration and expiry.

## Out Of Scope

- Cross-device or multi-screen modal synchronization
- Backend persistence
- Refactoring unrelated public display scripts
- Changes to the modal copy from translation strings

## Implementation Notes

- This work should primarily touch:
  - `public/js/public_display/iqamah-shalat-modal.js`
- Blade changes should be avoided unless a small hook or data attribute is needed.
- The implementation should preserve the current Friday special-case behavior.

## Risks

- The display page depends on `shalatTimeData` and `nextShalatTime` timing; restore logic must not race with those globals.
- Duplicate timers can occur if restore logic and normal countdown logic both start modal flows at once.
- Time calculations should use absolute timestamps consistently to avoid drift on refresh.

## Mitigations

- Gate modal startup so only one active modal flow can run at a time.
- Always clear old intervals and timeouts before restoring or starting a modal.
- Centralize modal transitions in helper functions instead of manipulating modal classes in multiple places.
