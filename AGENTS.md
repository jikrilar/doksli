# AGENTS.md

## 1. Project Overview

**Doksli** adalah aplikasi web untuk membantu pengguna memeriksa indikasi kebenaran suatu informasi atau berita.

Sistem menerima beberapa jenis input:

* teks,
* gambar / screenshot,
* video,
* tautan artikel atau media,

kemudian mengubah konten menjadi teks apabila diperlukan dan melakukan klasifikasi menggunakan model BERT.

Output utama sistem:

* `Valid`
* `Hoax`
* `Meragukan`

beserta confidence score dan penjelasan yang dapat dipahami pengguna.

Doksli adalah **alat bantu pemeriksaan informasi**, bukan sumber kebenaran absolut dan bukan pengganti fact-checking independen.

---

# 2. Technology Stack

## Application

* PHP 8.2+
* Laravel 12
* Blade
* Livewire 4
* Filament 5
* MySQL
* Laravel Queue

## Frontend

* Tailwind CSS 4
* Vite 6
* Vanilla JavaScript
* Axios

## Machine Learning

* Python 3.10+
* FastAPI
* BERT / IndoBERT inference service

BERT service berada di:

```text
bert-service/
```

## Supporting Services

OpenAI digunakan sebagai layanan pendukung untuk fungsi tertentu seperti:

* OCR,
* transkripsi,
* translation jika diperlukan,
* penyusunan penjelasan hasil.

**BERT tetap merupakan classifier utama.**

Jangan mengubah arsitektur sehingga OpenAI menjadi classifier utama kecuali task secara eksplisit meminta perubahan metodologi.

---

# 3. Read Before Making Changes

Sebelum melakukan perubahan signifikan, baca dokumen yang relevan.

Urutan referensi utama:

```text
AGENTS.md
PRD.md
DESIGN_SYSTEM.md
UI_REFACTOR_PLAN.md
TASK.md
PROJECT_PROGRESS.md
README.md
```

Tidak semua dokumen harus dibaca penuh untuk setiap task.

Gunakan dokumen sesuai scope:

### Product behavior

```text
PRD.md
```

### UI / UX

```text
DESIGN_SYSTEM.md
```

### UI refactor

```text
UI_REFACTOR_PLAN.md
```

### Existing task/status

```text
TASK.md
PROJECT_PROGRESS.md
```

### Setup / usage

```text
README.md
```

---

# 4. Source of Truth

Gunakan prioritas berikut saat mengambil keputusan:

1. Task/instruction yang sedang diberikan user.
2. `AGENTS.md`.
3. Dokumen domain yang relevan.
4. Existing tests.
5. Existing application behavior.
6. Existing implementation.

Jika dokumentasi dan implementasi berbeda:

* jangan langsung mengubah salah satunya,
* verifikasi behavior sebenarnya,
* identifikasi apakah dokumentasi stale atau implementasi yang salah.

Jangan membuat asumsi besar tanpa bukti dari codebase.

---

# 5. High-Level Architecture

Alur utama aplikasi:

```text
User
  ↓
Laravel Web Application
  ↓
Submission
  ↓
Queue Pipeline
  ├── extract-text
  ├── extract-media
  ├── inference
  ├── explanation
  └── default
  ↓
BERT Service / Supporting Services
  ↓
Detection Result
  ↓
Laravel
  ↓
User Result Page
```

Admin interface menggunakan Filament dan merupakan bagian yang terpisah dari public-facing UI.

---

# 6. Architectural Boundaries

Pertahankan separation of concerns.

## Controllers

Bertanggung jawab terhadap:

* menerima request,
* koordinasi application flow,
* memilih response/view,
* authorization tingkat request.

Jangan memasukkan business logic besar ke controller.

---

## Form Requests / Validation

Gunakan untuk:

* validation,
* authorization terkait request,
* input constraints.

Jangan duplikasi validation yang sudah tersedia.

---

## Services

Gunakan service untuk logic yang:

* reusable,
* berinteraksi dengan external service,
* melakukan domain processing yang tidak cocok berada di controller/model.

---

## Jobs

Gunakan Laravel Jobs untuk proses berat atau asynchronous.

Contoh:

* OCR,
* media extraction,
* BERT inference,
* explanation generation.

Jangan memindahkan proses berat ke request lifecycle tanpa alasan kuat.

---

## Models

Gunakan model untuk:

* relationship,
* casting,
* query scopes,
* domain behavior sederhana.

Hindari controller-style business workflow di model.

---

## Views

Blade harus fokus pada:

* presentation,
* rendering state,
* minimal view-specific conditional logic.

Jangan memasukkan business logic baru ke Blade.

---

# 7. Critical Domain Rules

Agent harus menjaga aturan berikut.

## Classification Labels

Label utama:

```text
valid
hoax
meragukan
```

Jangan:

* rename,
* menambah label,
* mengubah semantic meaning,

tanpa task functional yang eksplisit.

---

## BERT

BERT adalah classifier utama proyek.

Jangan mengganti BERT dengan:

* OpenAI classification,
* rule-based classification,
* model lain,

hanya karena implementasinya lebih mudah.

Perubahan metode classifier merupakan perubahan arsitektur/metodologi dan berada di luar scope UI refactor.

---

## Confidence

Confidence score adalah estimasi model.

Jangan mengubahnya menjadi klaim:

```text
akurasi faktual
```

atau:

```text
probabilitas berita benar secara absolut
```

---

## Result Explanation

Penjelasan harus membantu user memahami output classifier.

Penjelasan tidak boleh diposisikan sebagai bukti bahwa suatu berita pasti benar atau salah.

---

# 8. UI Refactor Rules

Saat mengerjakan UI refactor, baca:

```text
DESIGN_SYSTEM.md
UI_REFACTOR_PLAN.md
```

sebelum membuat perubahan.

UI refactor secara default adalah:

```text
presentation refactor
```

bukan:

```text
functional rewrite
```

---

# 9. UI Functional Contracts

Selama refactor, pertahankan existing contract berikut kecuali task secara eksplisit mengatakan sebaliknya:

* route names,
* URL behavior,
* HTTP methods,
* CSRF,
* form actions,
* request field names,
* validation rules,
* CAPTCHA field,
* honeypot,
* rate limiting,
* file upload constraints,
* authentication flow,
* authorization,
* Livewire directives,
* polling behavior,
* submission IDs,
* result labels,
* result properties,
* queue names,
* PDF export,
* private media access,
* feedback flow,
* history filtering,
* pagination.

Jangan mengubah backend hanya agar markup baru lebih mudah dibuat.

---

# 10. Design Guardrails

`DESIGN_SYSTEM.md` adalah source of truth untuk public UI.

Secara default, jangan memperkenalkan:

* glassmorphism,
* backdrop blur sebagai surface,
* glow orb,
* neon effect,
* decorative gradient,
* gradient text,
* oversized gradient headline,
* animated background grid,
* floating decoration,
* excessive pill,
* card untuk setiap section,
* emoji sebagai primary UI icon,
* decorative entrance animations,
* unnecessary hover elevation.

Doksli harus terasa:

```text
simple
calm
credible
editorial
functional
information-first
```

bukan:

```text
futuristic
flashy
AI-looking
template-like
```

---

# 11. Styling Rules

Gunakan Tailwind CSS sebagai styling utama.

Prefer:

```html
class="..."
```

daripada:

```html
style="..."
```

Jangan menambahkan inline static style baru tanpa alasan.

Inline style diperbolehkan jika nilainya benar-benar dynamic.

Contoh valid:

```blade
<div style="width: {{ $progress }}%"></div>
```

---

# 12. CSS Rules

`resources/css/app.css` digunakan untuk:

* Tailwind import,
* theme tokens,
* base styles,
* reusable styles yang benar-benar diperlukan,
* accessibility rules,
* reduced-motion handling.

Jangan menjadikan `app.css` sebagai tempat style unik untuk setiap halaman.

Sebelum menambahkan custom CSS:

1. periksa apakah Tailwind utility sudah cukup,
2. periksa apakah reusable component sudah tersedia,
3. baru tambahkan custom CSS jika memang diperlukan.

---

# 13. JavaScript Rules

JavaScript menangani **behavior**, bukan menjadi styling engine.

Prefer:

```js
element.classList.add('active');
element.classList.remove('active');
```

daripada:

```js
element.style.background = '...';
element.style.color = '...';
```

Gunakan state melalui:

* class,
* `data-*`,
* ARIA attributes,

jika memungkinkan.

---

# 14. JavaScript Organization

Global initialization berada di:

```text
resources/js/app.js
```

Behavior yang cukup kompleks dapat dipisah menjadi:

```text
resources/js/modules/
```

Contoh:

```text
navigation.js
checker.js
upload.js
result.js
profile.js
```

Jangan membuat duplicate event listener di:

```text
app.js
```

dan inline `<script>` Blade untuk behavior yang sama.

---

# 15. Blade Components

Gunakan Blade component untuk pola yang benar-benar reusable.

Candidate:

```text
resources/views/components/ui/
```

seperti:

```text
button
alert
badge
input
textarea
empty-state
page-header
```

Layout component:

```text
resources/views/components/layout/
```

seperti:

```text
navbar
footer
```

Jangan over-componentize.

Jika suatu markup hanya digunakan sekali dan tidak kompleks, tetap boleh berada langsung di page view.

---

# 16. Accessibility

Public interface harus menargetkan minimal:

```text
WCAG 2.1 AA
```

Saat mengubah UI, periksa:

* semantic HTML,
* heading order,
* input labels,
* visible focus,
* keyboard navigation,
* sufficient contrast,
* meaningful button names,
* alt text,
* `aria-live` untuk async status,
* accessible modal behavior,
* status tidak bergantung pada warna saja.

Jangan menghapus existing ARIA behavior tanpa replacement yang lebih baik.

---

# 17. Responsive Design

Perubahan UI harus tetap usable minimal pada:

```text
320px
375px
768px
1024px
1440px
```

Periksa khusus:

* navbar,
* mobile navigation,
* checker tabs,
* textarea,
* upload area,
* result page,
* history,
* auth forms,
* profile,
* modal,
* footer.

Tidak boleh ada horizontal overflow yang tidak disengaja.

---

# 18. Development Setup

## Install PHP Dependencies

```bash
composer install
```

## Install Frontend Dependencies

```bash
npm install
```

## Environment

Jika `.env` belum tersedia:

```bash
cp .env.example .env
php artisan key:generate
```

Pada Windows PowerShell dapat menggunakan:

```powershell
Copy-Item .env.example .env
php artisan key:generate
```

Jangan commit `.env`.

---

# 19. Database

Jalankan migration:

```bash
php artisan migrate
```

Jangan menjalankan command destruktif seperti:

```bash
php artisan migrate:fresh
php artisan db:wipe
```

tanpa kebutuhan task yang eksplisit.

Jangan menghapus atau mereset data user untuk menyelesaikan bug kecuali scope memang meminta database reset.

---

# 20. Main Development Command

Gunakan:

```bash
composer run dev
```

Command ini menjalankan development process Laravel yang diperlukan, termasuk:

* Laravel server,
* queue listener,
* application logs,
* Vite.

Queue listener memproses:

```text
extract-text
extract-media
inference
explanation
default
```

---

# 21. BERT Service

BERT service dijalankan secara terpisah.

Masuk ke:

```bash
cd bert-service
```

Aktifkan virtual environment.

Windows PowerShell:

```powershell
.\.venv\Scripts\Activate.ps1
```

Kemudian jalankan:

```bash
fastapi dev app\main.py --port 8001
```

Jangan menganggap `composer run dev` otomatis menjalankan BERT service.

Jika task tidak membutuhkan real inference, jangan menjalankan BERT service tanpa alasan.

---

# 22. Admin

Filament admin panel tersedia pada:

```text
/admin
```

Pembuatan admin user:

```bash
php artisan make:filament-user
```

Jangan mengubah Filament UI saat mengerjakan public UI refactor kecuali task secara eksplisit mencakup admin panel.

---

# 23. Testing

Test suite Laravel tersedia di:

```text
tests/Feature/
tests/Unit/
```

Jalankan seluruh test:

```bash
php artisan test
```

Untuk test tertentu:

```bash
php artisan test --filter=TestName
```

Saat memperbaiki bug:

1. jalankan test paling relevan dahulu,
2. lakukan fix,
3. jalankan kembali test relevan,
4. kemudian jalankan broader regression jika perubahan memiliki impact luas.

---

# 24. Important Existing Test Areas

Existing feature tests mencakup area seperti:

* authentication,
* submission,
* submission history,
* submission progress,
* profile,
* feedback,
* BERT classifier,
* AI pipeline,
* pipeline integration,
* translation,
* real BERT inference,
* database seeding.

Jangan menghapus atau melemahkan assertion hanya agar perubahan lolos test.

Jika test gagal setelah perubahan:

* tentukan apakah regression,
* test stale,
* atau behavior memang sengaja berubah.

Dokumentasikan alasannya sebelum mengubah test expectation.

---

# 25. Real External / Inference Tests

Beberapa test dapat membutuhkan:

* BERT service,
* external service,
* environment tertentu.

Jangan menganggap seluruh test harus melakukan network request nyata.

Untuk perubahan UI sederhana, tidak perlu memanggil external AI service hanya untuk membuktikan markup berubah.

Gunakan test scope yang sebanding dengan perubahan.

---

# 26. Code Formatting

Untuk PHP gunakan Laravel Pint.

Check:

```bash
vendor/bin/pint --test
```

Fix:

```bash
vendor/bin/pint
```

Jangan melakukan project-wide formatting tanpa alasan ketika task hanya menyentuh beberapa file.

Hindari diff noise.

---

# 27. Frontend Validation

Setelah perubahan CSS/Blade/JavaScript yang signifikan:

```bash
npm run build
```

Build harus selesai tanpa error.

Jangan menganggap development server yang berjalan berarti production build valid.

---

# 28. Browser Verification

Browser verification berguna untuk perubahan visual atau interaction.

Gunakan jika task menyentuh:

* layout,
* responsive behavior,
* dropdown,
* tabs,
* modal,
* upload interaction,
* result rendering,
* authenticated UI.

Browser automation **bukan langkah wajib untuk setiap task**.

Jangan membuka Playwright/browser untuk:

* dokumentasi,
* backend-only refactor,
* database-only change,
* task yang dapat diverifikasi dengan test/unit command.

Jika browser digunakan, fokus hanya pada flow yang relevan dengan perubahan.

---

# 29. Debugging Workflow

Untuk bug atau regression:

## 1. Reproduce

Pastikan masalah dapat direproduksi.

## 2. Identify Layer

Tentukan apakah masalah berada pada:

* UI,
* JavaScript,
* Laravel request,
* validation,
* controller/service,
* queue,
* external service,
* BERT service,
* database.

## 3. Find Root Cause

Jangan langsung patch symptom.

## 4. Make Minimal Fix

Ubah area sekecil mungkin.

## 5. Verify

Jalankan test dan verification yang relevan.

## 6. Regression

Pastikan flow terkait tidak rusak.

---

# 30. No Speculative Fixes

Jangan melakukan perubahan berdasarkan dugaan jika repository dapat diperiksa.

Sebelum mengubah code:

* cari implementasi terkait,
* baca call path,
* baca test terkait,
* periksa configuration jika relevan.

Jika root cause belum diketahui, investigasi terlebih dahulu.

---

# 31. Scope Discipline

Jangan memperbaiki issue lain yang ditemukan secara tidak sengaja jika tidak diperlukan untuk menyelesaikan task.

Jika menemukan issue tambahan:

* catat,
* laporkan,
* tetapi jangan otomatis mengubahnya.

Exception hanya jika issue tersebut:

* memblokir task,
* security critical,
* atau perubahan tidak dapat diselesaikan dengan benar tanpa memperbaikinya.

Jelaskan apabila exception digunakan.

---

# 32. Dependency Policy

Jangan menambahkan package baru hanya untuk mempercepat implementasi sederhana.

Sebelum menambah dependency:

1. cek capability Laravel,
2. cek Livewire,
3. cek Tailwind,
4. cek browser API,
5. cek dependency existing.

Tambahkan dependency hanya jika memberikan manfaat nyata yang tidak layak dibuat dengan stack existing.

Jangan mengganti stack frontend selama UI refactor.

---

# 33. Security

Jangan:

* commit `.env`,
* commit API keys,
* hardcode credentials,
* expose private storage URL,
* disable CSRF,
* bypass authorization,
* menghapus rate limiting,
* menghapus validation keamanan,
* mematikan CAPTCHA/honeypot hanya demi testing.

Jangan log:

* password,
* API key,
* sensitive token,
* private user content secara berlebihan.

---

# 34. Uploaded Media

Uploaded media harus mengikuti existing privacy/storage rules.

Jangan mengubah private media menjadi public asset hanya karena lebih mudah ditampilkan pada UI.

Pertahankan signed/private access mechanism existing.

---

# 35. Database Changes

Database migration hanya dibuat jika schema memang perlu berubah.

Untuk UI refactor:

```text
database migration should normally be zero.
```

Jika agent merasa UI refactor membutuhkan schema baru, evaluasi kembali desain terlebih dahulu.

---

# 36. Git Safety

Sebelum perubahan signifikan:

```bash
git status
```

Hormati perubahan existing milik user.

Jangan:

* `git reset --hard`,
* checkout dan membuang perubahan user,
* menghapus untracked files milik user,
* force push,
* rewrite history,

tanpa instruksi eksplisit.

Jangan commit atau push kecuali user meminta.

---

# 37. Refactor Safety

Saat refactor:

* pertahankan behavior sebelum memperindah structure,
* hindari rename besar bersamaan dengan behavior change,
* hindari memindahkan banyak file sekaligus tanpa kebutuhan,
* lakukan perubahan dalam logical unit.

Jika memungkinkan:

```text
structure refactor
```

dan:

```text
behavior change
```

dilakukan secara terpisah.

---

# 38. UI Refactor Implementation Order

Untuk redesign Doksli, ikuti urutan pada:

```text
UI_REFACTOR_PLAN.md
```

Secara umum:

```text
Baseline
↓
Design Foundation
↓
Application Shell
↓
Homepage + Checker
↓
Processing + Result
↓
History + Profile
↓
Authentication
↓
Informational Pages
↓
Cleanup + Regression
```

Jangan menghapus seluruh legacy CSS pada fase awal.

Hapus style lama hanya setelah tidak ada consumer yang bergantung padanya.

---

# 39. Documentation Updates

Jika suatu task mengubah:

* architecture,
* product behavior,
* setup command,
* design system,
* implementation plan,

update dokumentasi terkait.

Jangan mengubah dokumen hanya untuk membuatnya terlihat “updated”.

Dokumentasi harus menggambarkan state sebenarnya.

---

# 40. TASK / Progress Rules

Jika bekerja berdasarkan checklist:

* jangan mark task selesai sebelum diverifikasi,
* jangan mark phase selesai hanya karena code sudah ditulis,
* verification termasuk bagian Definition of Done.

Jika sebagian task belum selesai, laporkan sebagai partial.

Jangan menyembunyikan pending regression atau known failure.

---

# 41. Definition of Done

Sebuah task implementation dianggap selesai apabila:

1. Scope task terpenuhi.
2. Root cause dipahami untuk bug fix.
3. Existing behavior yang tidak termasuk scope tetap dipertahankan.
4. Relevant tests berjalan.
5. Frontend build berjalan jika frontend berubah.
6. Responsive state diperiksa jika UI berubah.
7. Accessibility dasar diperiksa jika UI berubah.
8. Tidak ada secret baru.
9. Tidak ada unrelated change.
10. Dokumentasi diperbarui jika memang terdampak.

---

# 42. UI Definition of Done

Untuk task UI, tambahan wajib:

* mengikuti `DESIGN_SYSTEM.md`,
* tidak memperkenalkan AI-slop pattern,
* desktop usable,
* mobile usable,
* keyboard interaction tetap bekerja,
* focus state terlihat,
* tidak ada horizontal overflow,
* existing backend contract tetap bekerja,
* loading/error/empty states tetap ditangani.

---

# 43. Completion Report

Setelah menyelesaikan task, laporan harus ringkas dan faktual.

Gunakan struktur:

```text
Status:
COMPLETED / PARTIAL / BLOCKED

Changes:
- ...

Files changed:
- ...

Verification:
- command/result
- command/result

Notes:
- regression risk / known issue / none
```

Jangan mengatakan:

```text
semuanya bekerja
```

jika tidak benar-benar diverifikasi.

Bedakan:

```text
implemented
```

dengan:

```text
verified
```

---

# 44. When Blocked

Jika task benar-benar blocked:

1. jelaskan blocker spesifik,
2. jelaskan bukti yang ditemukan,
3. jelaskan apa yang sudah dicoba,
4. jangan membuat workaround berisiko tanpa persetujuan.

Jika masih ada bagian task yang dapat diselesaikan secara aman, selesaikan bagian tersebut terlebih dahulu.

---

# 45. Final Agent Principle

Ketika ragu, pilih perubahan yang:

```text
lebih kecil,
lebih mudah diverifikasi,
lebih konsisten dengan arsitektur existing,
lebih mudah dirawat,
dan lebih sedikit mengubah behavior yang sudah bekerja.
```

Untuk UI, pilih solusi yang:

```text
lebih sederhana,
lebih jelas,
lebih accessible,
dan lebih sesuai DESIGN_SYSTEM.md.
```

Doksli bukan proyek untuk menunjukkan seberapa banyak teknologi atau efek visual yang dapat digunakan.

Tujuan utama adalah menghasilkan aplikasi pemeriksaan informasi yang **jelas, kredibel, stabil, dan dapat dipertanggungjawabkan**.
