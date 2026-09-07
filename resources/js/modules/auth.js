// ─── hoaxlin.id — Auth behavior (UI-v2 Phase 6) ───────────────────────────────
// Owns authentication page enhancements: double-submit prevention, register
// password strength + match check with submit guard. Password visibility
// toggles are handled globally by profile.js via [data-password-toggle].
// Safe to load globally; no-ops when its DOM is absent.
//
// Rules: behavior only (classList, attributes, dataset, disabled).

export function initAuth() {
    initPasswordMatch();
    initDoubleSubmit();
    initRegisterStrength();
}

/** Prevent double submits with busy feedback. Skips forms halted by another guard. */
function initDoubleSubmit() {
    document.querySelectorAll('form[data-double-submit]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (event.defaultPrevented) return;
            const btn = form.querySelector('[type="submit"]');
            if (!btn || btn.disabled) return;
            btn.disabled = true;
            btn.innerHTML = '<span class="ds-spinner ds-spinner-sm" aria-hidden="true"></span><span>Memproses...</span>';
        });
    });
}

/** Register password strength meter (register scoring, preserved). */
function initRegisterStrength() {
    const input = document.getElementById('password');
    const container = document.getElementById('register-strength-container');
    if (!input || !container) return;
    const meter = container.querySelector('.pwd-strength');
    const label = document.getElementById('register-strength-label');
    const labels = ['Lemah', 'Sedang', 'Kuat', 'Sangat Kuat'];

    input.addEventListener('input', () => {
        const val = input.value;
        container.classList.toggle('ds-hidden', val.length === 0);
        let score = 0;
        if (val.length >= 8) score += 1;
        if (val.length >= 12) score += 1;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score += 1;
        if (/[0-9]/.test(val) && /[^A-Za-z0-9]/.test(val)) score += 1;
        if (meter) meter.dataset.score = String(score);
        if (label) label.textContent = score > 0 ? labels[score - 1] : '';
    });
}

/** Live password confirmation check + submit guard (preserved behavior). */
function initPasswordMatch() {
    const pass = document.getElementById('password');
    const confirm = document.getElementById('password_confirmation');
    const hint = document.getElementById('confirm-match');
    const form = document.getElementById('register-form');
    if (!pass || !confirm || !hint || !form) return;

    const update = () => {
        if (confirm.value.length === 0) {
            hint.classList.add('ds-hidden');
            return;
        }
        hint.classList.remove('ds-hidden');
        const ok = pass.value === confirm.value;
        hint.textContent = ok ? 'Kata sandi cocok' : 'Kata sandi tidak cocok';
        hint.classList.toggle('match-ok', ok);
        hint.classList.toggle('match-bad', !ok);
        return ok;
    };

    pass.addEventListener('input', update);
    confirm.addEventListener('input', update);

    form.addEventListener('submit', (event) => {
        if (update() === false) event.preventDefault();
    });
}
