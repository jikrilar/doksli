// ─── hoaxlin.id — Result behavior (UI-v2 Phase 4) ────────────────────────────
// Owns the result page enhancements: vanilla polling fallback (when Livewire
// is absent), feedback choice state, and the print button. Safe to load
// globally; every initializer no-ops when its DOM is absent.
//
// Rules: behavior only (classList, attributes). The single element.style write
// below sets a truly dynamic progress width, which is explicitly allowed.

export function initResult() {
    initPollFallback();
    initFeedback();
    initPrint();
}

/**
 * Vanilla polling fallback for the processing state. Mirrors the Livewire
 * component contract: GET data-status-url every 2s, update [role=progressbar],
 * the aria-live percentage, and [data-stage-label], then reload once the
 * backend reports completion or failure. Skipped when Livewire is present
 * and when the tab is hidden.
 */
function initPollFallback() {
    const wrapper = document.getElementById('submission-progress-wrapper');
    if (!wrapper) return;
    const hasLivewire = wrapper.querySelector('[wire\\:id]')
        || wrapper.querySelector('[wire\\:poll]')
        || typeof window.Livewire !== 'undefined';
    if (hasLivewire) return;

    const url = wrapper.dataset.statusUrl;
    if (!url) return;

    const timer = window.setInterval(async () => {
        if (document.hidden) return;
        try {
            const res = await fetch(url, { headers: { Accept: 'application/json' }, credentials: 'same-origin' });
            if (!res.ok) return;
            const data = await res.json();
            const bar = wrapper.querySelector('[role="progressbar"]');
            const pct = wrapper.querySelector('[aria-live="polite"]');
            const stages = wrapper.querySelectorAll('[data-stage-label]');
            if (bar && typeof data.progress === 'number') {
                bar.setAttribute('aria-valuenow', String(data.progress));
                const fill = bar.firstElementChild;
                if (fill) fill.style.width = `${data.progress}%`;
            }
            if (pct && typeof data.progress === 'number') pct.textContent = `${data.progress}%`;
            stages.forEach((el) => {
                if (data.stage_label) el.textContent = data.stage_label;
            });
            if (data.is_completed || data.is_failed || data.has_result) {
                window.clearInterval(timer);
                window.setTimeout(() => window.location.reload(), 800);
            }
        } catch {
            // Transient polling failure: keep the last rendered state.
        }
    }, 2000);
}

/** Feedback choice: fills the hidden is_correct field, marks the pressed
 *  button, and reveals the comment area. */
function initFeedback() {
    const form = document.getElementById('feedback-form');
    const btnCorrect = document.getElementById('fb-correct');
    const btnWrong = document.getElementById('fb-wrong');
    const hidden = document.getElementById('feedback-is-correct');
    const area = document.getElementById('feedback-comment-area');
    if (!form || !btnCorrect || !btnWrong || !hidden || !area) return;

    const select = (isCorrect) => {
        hidden.value = isCorrect ? '1' : '0';
        btnCorrect.classList.toggle('is-active', isCorrect);
        btnWrong.classList.toggle('is-active', !isCorrect);
        btnCorrect.setAttribute('aria-pressed', String(isCorrect));
        btnWrong.setAttribute('aria-pressed', String(!isCorrect));
        area.classList.remove('ds-hidden');
        if (!isCorrect) {
            const comment = document.getElementById('feedback-comment');
            if (comment) comment.focus({ preventScroll: true });
            area.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
    };

    btnCorrect.addEventListener('click', () => select(true));
    btnWrong.addEventListener('click', () => select(false));
}

/** Print fallback on the processing state (replaces inline onclick). */
function initPrint() {
    const btn = document.getElementById('print-btn');
    if (!btn) return;
    btn.addEventListener('click', () => window.print());
}
