// ─── hoaxlin.id — Shell behavior (UI-v2 Phase 2) ────────────────────────────
// Single owner of global chrome behavior: mobile menu, account dropdown,
// flash banner. Replaces the former inline layout script plus the duplicate
// navbar/flash listeners that lived in app.js.
//
// Rules: behavior only (classList, hidden, aria-*). No element.style paint
// except body scroll-locking, which has no class equivalent.

export function initNavigation() {
    initMobileMenu();
    initAccountMenu();
    initFlash();
}

/**
 * Mobile menu: simple dropdown panel (not a fullscreen overlay).
 * Keeps: close button, body scroll-lock, link-click close, Escape.
 */
function initMobileMenu() {
    const toggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('mobile-menu');
    const closeBtn = document.getElementById('menu-close');
    if (!toggle || !menu) return;

    const iconOpen = document.getElementById('menu-icon-open');
    const iconClose = document.getElementById('menu-icon-close');

    const setOpen = (open, refocus = false) => {
        menu.classList.toggle('open', open);
        toggle.setAttribute('aria-expanded', String(open));
        toggle.setAttribute('aria-label', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
        if (iconOpen) iconOpen.hidden = open;
        if (iconClose) iconClose.hidden = !open;
        document.body.style.overflow = open ? 'hidden' : '';
        if (!open && refocus) toggle.focus();
    };

    toggle.addEventListener('click', () => {
        setOpen(!menu.classList.contains('open'));
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', () => setOpen(false, true));
    }

    menu.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setOpen(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && menu.classList.contains('open')) {
            setOpen(false, true);
        }
    });
}

/**
 * Account dropdown: toggle on click, close on outside click / Escape.
 * Preserves the previous aria-expanded contract.
 */
function initAccountMenu() {
    const wrapper = document.getElementById('user-menu-wrapper');
    const btn = document.getElementById('user-menu-btn');
    const menu = document.getElementById('user-dropdown');
    if (!wrapper || !btn || !menu) return;

    const setOpen = (open, refocus = false) => {
        menu.classList.toggle('open', open);
        wrapper.classList.toggle('open', open);
        btn.setAttribute('aria-expanded', String(open));
        if (!open && refocus) btn.focus();
    };

    btn.addEventListener('click', (event) => {
        event.stopPropagation();
        setOpen(!menu.classList.contains('open'));
    });

    document.addEventListener('click', (event) => {
        if (!wrapper.contains(event.target)) setOpen(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && menu.classList.contains('open')) {
            setOpen(false, true);
        }
    });
}

/**
 * Flash banner: manual dismiss + 4s auto-dismiss (timing preserved).
 */
function initFlash() {
    const banner = document.getElementById('flash-banner');
    if (!banner) return;

    const dismiss = () => {
        banner.classList.add('flash-leaving');
        window.setTimeout(() => banner.remove(), 450);
    };

    banner.querySelectorAll('[data-flash-close]').forEach((btn) => {
        btn.addEventListener('click', dismiss);
    });

    window.setTimeout(() => {
        if (document.body.contains(banner)) dismiss();
    }, 4000);
}
