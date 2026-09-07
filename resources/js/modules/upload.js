// ─── hoaxlin.id — Upload behavior (UI-v2 Phase 3) ────────────────────────────
// Owns checker file previews and drag-and-drop. Scoped to #cek-berita so it
// is safe to load globally. Contracts preserved: input IDs/names, accept
// filters, required attributes, preview element IDs.
//
// Rules: behavior only (classList, attributes, FileReader). No element.style
// paint and no emoji in generated labels.

export function initUpload() {
    const root = document.getElementById('cek-berita');
    if (!root) return;

    bindImage();
    bindVideo();
}

function formatMB(size) {
    return `${(size / 1024 / 1024).toFixed(2)} MB`;
}

function setFileLabel(label, file, fallbackTitle, fallbackSub) {
    if (!label) return;
    const name = label.querySelector('[data-file-name]');
    const size = label.querySelector('[data-file-size]');
    if (name) name.textContent = file ? file.name : fallbackTitle;
    if (size) size.textContent = file ? formatMB(file.size) : fallbackSub;
}

function bindDrop(zone, input, onFiles) {
    if (!zone || !input) return;
    zone.addEventListener('dragover', (event) => {
        event.preventDefault();
        zone.classList.add('is-dragover');
    });
    zone.addEventListener('dragleave', () => {
        zone.classList.remove('is-dragover');
    });
    zone.addEventListener('drop', (event) => {
        event.preventDefault();
        zone.classList.remove('is-dragover');
        const file = event.dataTransfer && event.dataTransfer.files && event.dataTransfer.files[0];
        if (!file) return;
        const transfer = new DataTransfer();
        transfer.items.add(file);
        input.files = transfer.files;
        onFiles();
    });
}

function bindImage() {
    const input = document.getElementById('gambar-input');
    if (!input) return;
    const img = document.getElementById('gambar-preview-img');
    const label = document.getElementById('gambar-preview-label');
    const zone = input.closest('.ds-upload');

    const preview = () => {
        const file = input.files && input.files[0];
        if (!file) return;
        if (img && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (event) => {
                img.src = event.target.result;
                img.classList.remove('ds-hidden');
            };
            reader.readAsDataURL(file);
        }
        setFileLabel(label, file, 'Klik atau seret gambar ke sini', 'PNG, JPG, WEBP, HEIC hingga 10 MB');
    };

    input.addEventListener('change', preview);
    bindDrop(zone, input, preview);
}

function bindVideo() {
    const input = document.getElementById('video-file-input');
    if (!input) return;
    const label = document.getElementById('video-preview-label');
    const zone = input.closest('.ds-upload');

    const preview = () => {
        const file = input.files && input.files[0];
        if (!file) return;
        setFileLabel(label, file, 'Klik atau seret video ke sini', 'MP4, MOV, AVI hingga 200 MB');
    };

    input.addEventListener('change', preview);
    bindDrop(zone, input, preview);
}
