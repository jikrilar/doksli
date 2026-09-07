// ─── hoaxlin.id — Checker behavior (UI-v2 Phase 3) ───────────────────────────
// Owns the homepage checker: input tabs, video file/URL switch, character
// counter, URL preview, submit loading state. Scoped to #cek-berita so it is
// safe to load globally. Contracts preserved: form actions, field names,
// required attributes, ARIA tab wiring, element IDs, loading step IDs.
//
// Rules: behavior only (classList, attributes, textContent). No element.style
// paint; visual state goes through classes (ds-hidden, is-active, is-ok).

export function initChecker() {
    const root = document.getElementById('cek-berita');
    if (!root) return;

    initTabs(root);
    initVideoToggle(root);
    initCounter(root);
    initUrlPreview(root);
    initSubmitLoading(root);
}

/** Input type tabs (Teks / Gambar / Video / Tautan). */
function initTabs(root) {
    const tabs = Array.from(root.querySelectorAll('[role="tab"]'));
    const panels = Array.from(root.querySelectorAll('[role="tabpanel"]'));
    const overlay = document.getElementById('loading-overlay');
    if (tabs.length === 0 || panels.length === 0) return;

    const activate = (name) => {
        tabs.forEach((tab) => {
            tab.setAttribute('aria-selected', String(tab.dataset.tab === name));
        });
        panels.forEach((panel) => {
            panel.classList.toggle('active', panel.id === `panel-${name}`);
            panel.classList.remove('ds-hidden');
        });
        if (overlay) overlay.classList.add('ds-hidden');
    };

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => activate(tab.dataset.tab));
    });
}

/** Video sub-switch: file upload vs video URL. Preserves the
 *  video/video_url input_type contract and required-field swapping. */
function initVideoToggle(root) {
    const uploadBtn = document.getElementById('video-tab-upload');
    const urlBtn = document.getElementById('video-tab-url');
    const uploadPanel = document.getElementById('video-upload-panel');
    const urlPanel = document.getElementById('video-url-panel');
    const typeField = document.getElementById('video-input-type');
    const fileInput = document.getElementById('video-file-input');
    const urlInput = document.getElementById('video-url-input');
    if (!uploadBtn || !urlBtn || !typeField) return;

    const select = (mode) => {
        const isUpload = mode === 'upload';
        if (uploadPanel) uploadPanel.classList.toggle('active', isUpload);
        if (urlPanel) urlPanel.classList.toggle('active', !isUpload);
        uploadBtn.classList.toggle('is-active', isUpload);
        urlBtn.classList.toggle('is-active', !isUpload);
        uploadBtn.setAttribute('aria-pressed', String(isUpload));
        urlBtn.setAttribute('aria-pressed', String(!isUpload));
        typeField.value = isUpload ? 'video' : 'video_url';
        if (fileInput) {
            if (isUpload) fileInput.setAttribute('required', '');
            else fileInput.removeAttribute('required');
        }
        if (urlInput) {
            if (isUpload) urlInput.removeAttribute('required');
            else urlInput.setAttribute('required', '');
        }
    };

    uploadBtn.addEventListener('click', () => select('upload'));
    urlBtn.addEventListener('click', () => select('url'));
    // Sync with the server-rendered state (e.g. after a validation round-trip).
    select(typeField.value === 'video_url' ? 'url' : 'upload');
}

/** Live character counter with a 50-character validity hint. */
function initCounter(root) {
    const input = document.getElementById('teks-input');
    const count = document.getElementById('teks-count');
    if (!input || !count) return;

    const update = () => {
        const len = input.value.length;
        count.textContent = `${len} karakter`;
        count.classList.toggle('is-ok', len >= 50);
    };

    input.addEventListener('input', update);
    update();
}

/** URL preview: domain + full URL once the value parses as http(s). */
function initUrlPreview(root) {
    const input = document.getElementById('url-input');
    const preview = document.getElementById('url-preview');
    if (!input || !preview) return;
    const domain = document.getElementById('url-domain');
    const full = document.getElementById('url-full');

    const update = () => {
        const val = input.value.trim();
        if (val && val.startsWith('http')) {
            try {
                const url = new URL(val);
                if (domain) domain.textContent = url.hostname;
                if (full) full.textContent = val;
                preview.classList.remove('ds-hidden');
                return;
            } catch {
                // Fall through to hide the preview on invalid URLs.
            }
        }
        preview.classList.add('ds-hidden');
    };

    input.addEventListener('input', update);
    update();
}

/** Submit loading overlay with per-type messaging. The form submits
 *  naturally afterwards; this only swaps the visible state. */
function initSubmitLoading(root) {
    const overlay = document.getElementById('loading-overlay');
    if (!overlay) return;
    const panels = Array.from(root.querySelectorAll('[role="tabpanel"]'));
    const step1 = document.getElementById('step-1');
    const step1Text = document.getElementById('step-1-text');
    const step2 = document.getElementById('step-2');
    const step3 = document.getElementById('step-3');

    const messages = {
        teks: 'Membersihkan dan memvalidasi teks...',
        gambar: 'Mengekstraksi teks dari gambar (OCR)...',
        video: 'Mengekstraksi audio dan melakukan transkripsi...',
        url: 'Mengambil konten dari tautan...',
    };

    const markDone = (step) => {
        if (!step) return;
        step.classList.remove('active', 'pending');
        step.classList.add('done');
        const icon = step.querySelector('.step-icon');
        if (icon) {
            icon.classList.remove('active', 'pending');
            icon.classList.add('done');
        }
    };
    const markActive = (step) => {
        if (!step) return;
        step.classList.remove('pending', 'done');
        step.classList.add('active');
        const icon = step.querySelector('.step-icon');
        if (icon) {
            icon.classList.remove('pending', 'done');
            icon.classList.add('active');
        }
    };

    root.querySelectorAll('form[data-checker-form]').forEach((form) => {
        form.addEventListener('submit', () => {
            panels.forEach((panel) => panel.classList.add('ds-hidden'));
            overlay.classList.remove('ds-hidden');
            if (step1Text) {
                step1Text.textContent = messages[form.dataset.checkerForm] || 'Memproses input...';
            }
            window.setTimeout(() => {
                markDone(step1);
                markActive(step2);
            }, 1500);
            window.setTimeout(() => {
                markDone(step2);
                markActive(step3);
            }, 3500);
        });
    });
}
