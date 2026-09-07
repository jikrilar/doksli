// ─── hoaxlin.id — Account behavior (UI-v2 Phase 5) ────────────────────────────
// Owns the profile page: password visibility toggles, strength meter,
// double-submit prevention, and the delete-account modal (open/close,
// Escape, backdrop close, focus trap with return-focus). Safe to load
// globally; no-ops when its DOM is absent.
//
// Rules: behavior only (classList, attributes, dataset). The password score
// colors live in CSS (.pwd-strength[data-score]); text carries the meaning.

export function initProfile() {
    initPasswordToggles();
    initStrengthMeter();
    initDoubleSubmit();
    initDeleteModal();
}

/** Show/hide toggles for password fields. */
function initPasswordToggles() {
    document.querySelectorAll('[data-password-toggle]').forEach((btn) => {
        const input = document.getElementById(btn.dataset.passwordToggle);
        if (!input) return;
        btn.addEventListener('click', () => {
            const show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            btn.setAttribute('aria-pressed', String(show));
            const base = btn.getAttribute('aria-label') || 'Kata sandi';
            btn.setAttribute('aria-label', show ? base.replace('Tampilkan', 'Sembunyikan') : base.replace('Sembunyikan', 'Tampilkan'));
        });
    });
}

/** Password strength meter. Scoring preserved from the legacy page. */
function initStrengthMeter() {
    const input = document.getElementById('password');
    const container = document.getElementById('strength-container');
    if (!input || !container) return;
    const meter = container.querySelector('.pwd-strength');
    const label = document.getElementById('strength-label');
    const labels = ['Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];

    input.addEventListener('input', () => {
        const val = input.value;
        container.classList.toggle('ds-hidden', val.length === 0);
        let score = 0;
        if (val.length >= 8) score += 1;
        if (/[A-Z]/.test(val)) score += 1;
        if (/[0-9]/.test(val)) score += 1;
        if (/[^A-Za-z0-9]/.test(val)) score += 1;
        if (meter) meter.dataset.score = String(score);
        if (label) label.textContent = score > 0 ? labels[score - 1] : '';
    });
}

/** Prevent double submits on account forms, with busy feedback. */
function initDoubleSubmit() {
    ['profile-form', 'password-form'].forEach((id) => {
        const form = document.getElementById(id);
        if (!form) return;
        form.addEventListener('submit', () => {
            const btn = form.querySelector('[type="submit"]');
            if (!btn || btn.disabled) return;
            btn.disabled = true;
            btn.innerHTML = '<span class="ds-spinner ds-spinner-sm" aria-hidden="true"></span><span>Memproses...</span>';
        });
    });
}

/** Delete-account modal with focus management and a lightweight trap. */
function initDeleteModal() {
    const overlay = document.getElementById('delete-modal');
    if (!overlay) return;
    const panel = overlay.querySelector('.ds-modal');
    let opener = null;

    const focusables = () => Array.from(panel.querySelectorAll(
        'a[href], button:not([disabled]), input:not([disabled]), select, textarea, [tabindex]:not([tabindex="-1"])'
    )).filter((el) => el.offsetParent !== null);

    const open = (source) => {
        opener = source || null;
        overlay.classList.remove('ds-hidden');
        document.body.style.overflow = 'hidden';
        const input = document.getElementById('confirm-password');
        if (input) input.focus();
    };

    const close = () => {
        overlay.classList.add('ds-hidden');
        document.body.style.overflow = '';
        if (opener) opener.focus();
    };

    document.querySelectorAll('[data-open-modal="delete-modal"]').forEach((btn) => {
        btn.addEventListener('click', () => open(btn));
    });
    overlay.querySelectorAll('[data-close-modal]').forEach((btn) => {
        btn.addEventListener('click', close);
    });
    overlay.addEventListener('click', (event) => {
        if (event.target === overlay) close();
    });
    document.addEventListener('keydown', (event) => {
        if (overlay.classList.contains('ds-hidden')) return;
        if (event.key === 'Escape') {
            close();
            return;
        }
        if (event.key !== 'Tab') return;
        const items = focusables();
        if (items.length === 0) return;
        const first = items[0];
        const last = items[items.length - 1];
        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    });
}
