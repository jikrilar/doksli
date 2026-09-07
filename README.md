# Doksli

**Doksli** adalah aplikasi web untuk membantu pengguna memeriksa indikasi kebenaran informasi dan berita berbahasa Indonesia. Sistem menerima input berupa **teks, gambar, video, tautan video, dan URL berita**, mengekstraksi kontennya menjadi teks, lalu menjalankan klasifikasi menggunakan model **IndoBERT** yang telah di-*fine-tune* untuk deteksi hoax.

Hasil pemeriksaan ditampilkan sebagai salah satu dari tiga status:

- **Valid** — model memiliki keyakinan tinggi bahwa pola teks lebih dekat dengan kelas berita valid.
- **Hoax** — model memiliki keyakinan tinggi bahwa pola teks lebih dekat dengan kelas hoax.
- **Meragukan** — confidence model belum melewati threshold yang ditentukan sehingga hasil tidak dipaksakan menjadi valid atau hoax.

> Doksli adalah alat bantu pemeriksaan informasi, bukan lembaga pemeriksa fakta dan bukan sumber putusan hukum. Hasil sistem bersifat probabilistik dan tetap perlu dibandingkan dengan sumber tepercaya.

---

## Fitur Utama

- Pemeriksaan berita melalui teks langsung.
- OCR gambar atau tangkapan layar menggunakan OpenAI Vision.
- Transkripsi video menjadi teks menggunakan OpenAI.
- Pemeriksaan artikel dari URL berita.
- Dukungan input tautan video.
- Klasifikasi berita menggunakan layanan IndoBERT terpisah berbasis FastAPI.
- Confidence score dan status `valid`, `hoax`, atau `meragukan`.
- Penjelasan hasil dalam bahasa yang lebih mudah dipahami.
- Progress pemrosesan asynchronous melalui Laravel Queue.
- Registrasi, login, verifikasi email, reset password, dan pengelolaan profil.
- Riwayat pemeriksaan untuk pengguna terdaftar.
- Feedback terhadap hasil deteksi.
- Ekspor riwayat ke CSV.
- Ekspor hasil pemeriksaan ke PDF.
- Halaman statistik publik.
- Panel administrator menggunakan Filament.
- Pengelolaan dataset dan data operasional.
- Rate limiting, CAPTCHA sederhana, dan validasi upload.

---

## Cara Kerja

Alur utama Doksli:

```text
Pengguna mengirim informasi
        │
        ▼
Validasi input
        │
        ▼
Ekstraksi teks
├── Teks       → normalisasi
├── Gambar     → OCR
├── Video      → transkripsi
├── Video URL  → ekstraksi/transkripsi
└── URL berita → ekstraksi artikel
        │
        ▼
IndoBERT Classification
        │
        ├── confidence >= threshold → Valid / Hoax
        └── confidence < threshold  → Meragukan
        │
        ▼
Pembuatan penjelasan
        │
        ▼
Hasil disimpan dan ditampilkan
```

Model BERT adalah **classifier utama**. OpenAI digunakan sebagai layanan pendukung untuk kebutuhan seperti OCR, transkripsi, translasi bila diperlukan, dan penyusunan penjelasan hasil.

---

## Tech Stack

### Web Application

- PHP 8.2+
- Laravel 12
- Livewire 4
- Filament 5
- Blade
- Tailwind CSS 4
- Vite
- MySQL
- Laravel Queue
- DomPDF

### Machine Learning Service

- Python 3.10+
- FastAPI
- Uvicorn
- PyTorch
- Hugging Face Transformers
- IndoBERT

### External Services

- OpenAI API
  - OCR / Vision
  - Transcription
  - Translation
  - Explanation generation

---

## Arsitektur Singkat

Doksli menggunakan dua service utama:

```text
┌───────────────────────────────┐
│          Browser              │
└───────────────┬───────────────┘
                │
                ▼
┌───────────────────────────────┐
│ Laravel 12                    │
│                               │
│ Blade / Livewire              │
│ Authentication                │
│ Validation                    │
│ Queue Jobs                    │
│ History & Feedback            │
│ Filament Admin                │
└───────────────┬───────────────┘
                │
       ┌────────┴─────────┐
       ▼                  ▼
┌──────────────┐   ┌───────────────┐
│ MySQL        │   │ FastAPI BERT  │
│              │   │               │
│ submissions  │   │ IndoBERT      │
│ results      │   │ inference     │
│ users        │   └───────────────┘
│ feedback     │
└──────────────┘
       │
       └──────────────► OpenAI API
                        OCR / transcription /
                        explanation
```

Layanan FastAPI sengaja dipisahkan dari Laravel agar proses machine learning tetap berada pada ekosistem Python dan dapat dikembangkan atau di-*deploy* secara independen.

---

# Instalasi

## Prasyarat

Pastikan perangkat sudah memiliki:

- PHP 8.2 atau lebih baru.
- Composer 2.x.
- Node.js dan npm.
- MySQL 8+.
- Python 3.10+.
- Git.

Opsional:

- FFmpeg / FFprobe untuk kebutuhan pemrosesan video.
- ClamAV jika malware scanning media diaktifkan.

Periksa versi:

```bash
php -v
composer --version
node -v
npm -v
python --version
git --version
```

---

## 1. Clone Repository

```bash
git clone https://github.com/jikrilar/doksli.git
cd doksli
```

## 2. Install Dependency Laravel

```bash
composer install
```

## 3. Install Dependency Frontend

```bash
npm install
```

## 4. Buat Environment Laravel

Linux/macOS:

```bash
cp .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Untuk branding lokal, sesuaikan minimal:

```env
APP_NAME="Doksli"
APP_URL=http://localhost:8000
APP_TIMEZONE=Asia/Jakarta
```

---

## 5. Konfigurasi Database

Buat database MySQL:

```sql
CREATE DATABASE hoax_detector
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
```

Kemudian sesuaikan `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hoax_detector
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migration dan seeder:

```bash
php artisan migrate --seed
```

Buat storage link:

```bash
php artisan storage:link
```

Seeder menyediakan data development untuk pengguna, dataset, submission, detection result, feedback, dan admin log.

---

# Konfigurasi AI

## IndoBERT Service

Laravel tidak menjalankan model BERT secara langsung. Model disajikan melalui service FastAPI pada folder:

```text
bert-service/
```

Konfigurasi Laravel di `.env`:

```env
BERT_SERVICE_URL=http://127.0.0.1:8001
BERT_SERVICE_TIMEOUT=30
BERT_SERVICE_CONNECT_TIMEOUT=3
BERT_SERVICE_TOKEN=change-this-internal-token
BERT_SERVICE_TRIES=5
BERT_MODEL_VERSION=v1.0.0
BERT_CONFIDENCE_THRESHOLD=0.99
```

`BERT_SERVICE_TOKEN` harus sama dengan token pada konfigurasi FastAPI.

### Setup Python Service

Masuk ke folder service:

```bash
cd bert-service
```

Buat virtual environment.

Linux/macOS:

```bash
python -m venv .venv
source .venv/bin/activate
```

Windows PowerShell:

```powershell
py -m venv .venv
.\.venv\Scripts\Activate.ps1
```

Install dependency:

```bash
python -m pip install --upgrade pip
python -m pip install -r requirements.txt
```

Buat file environment:

Linux/macOS:

```bash
cp .env.example .env
```

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Konfigurasi:

```env
BERT_SERVICE_TOKEN=change-this-internal-token
BERT_MODEL_PATH=/absolute/path/to/models/indobert-hoax/v1.0.0
BERT_MODEL_VERSION=v1.0.0
BERT_LOCAL_FILES_ONLY=true
BERT_MAX_CONCURRENCY=2
BERT_MAX_TEXT_LENGTH=20000
BERT_MAX_SEQUENCE_LENGTH=512
```

> Repository tidak mengandalkan model besar sebagai bagian dari source code aplikasi. `BERT_MODEL_PATH` harus menunjuk ke artifact model sequence-classification yang kompatibel dan memiliki mapping label `valid` dan `hoax`. Status `meragukan` diturunkan dari confidence threshold, bukan menjadi kelas training ketiga.

Detail training, dataset preparation, model serving, dan API contract tersedia di [`bert-service/README.md`](bert-service/README.md).

---

## OpenAI

Untuk workflow multimodal dan penjelasan hasil, isi konfigurasi berikut pada `.env` Laravel:

```env
OPENAI_API_KEY=
OPENAI_CHAT_MODEL=gpt-4o-mini
OPENAI_TRANSLATION_MODEL=gpt-4o-mini
OPENAI_CONNECT_TIMEOUT=5
OPENAI_TIMEOUT=45
OPENAI_TRIES=3
```

Tanpa OpenAI API, fungsi yang bergantung pada OCR, transkripsi, translasi, atau pembuatan penjelasan tidak dapat bekerja penuh.

---

# Menjalankan Development Environment

Doksli membutuhkan **Laravel application** dan **BERT service** berjalan bersamaan.

## Terminal 1 — Laravel, Queue, Logs, dan Vite

Dari root project:

```bash
composer run dev
```

Command tersebut menjalankan:

- `php artisan serve`
- queue listener untuk `extract-text`, `extract-media`, `inference`, `explanation`, dan `default`
- Laravel Pail
- Vite development server

Aplikasi tersedia di:

```text
http://127.0.0.1:8000
```

> Menjalankan `php artisan serve` saja tidak cukup untuk workflow deteksi karena ekstraksi, inference, dan explanation diproses melalui queue.

## Terminal 2 — FastAPI IndoBERT

Dari `bert-service/`:

Windows PowerShell:

```powershell
.\.venv\Scripts\python.exe -m uvicorn app.main:app --host 127.0.0.1 --port 8001 --reload
```

Linux/macOS:

```bash
.venv/bin/python -m uvicorn app.main:app --host 127.0.0.1 --port 8001 --reload
```

### Health Check

Liveness:

```text
GET http://127.0.0.1:8001/health/live
```

Readiness:

```text
GET http://127.0.0.1:8001/health/ready
```

`/health/live` memastikan service hidup. `/health/ready` hanya mengembalikan status siap jika model berhasil dimuat dari `BERT_MODEL_PATH`.

---

# Workflow Penggunaan Website

## Pemeriksaan Tanpa Login

Pengguna dapat melakukan pemeriksaan langsung dari halaman utama.

1. Buka halaman utama.
2. Pilih jenis input:
   - **Teks**
   - **Gambar**
   - **Video**
   - **Tautan video**
   - **URL berita**
3. Masukkan konten yang ingin diperiksa.
4. Selesaikan verifikasi keamanan/CAPTCHA.
5. Klik tombol analisis.
6. Sistem membuat submission dan memasukkannya ke processing pipeline.
7. Halaman hasil menampilkan progress hingga proses selesai.
8. Setelah selesai, pengguna dapat melihat label, confidence, dan penjelasan hasil.
9. Hasil dapat diekspor ke PDF jika tersedia.

## Pengguna Terdaftar

Pengguna yang membuat akun mendapatkan fitur tambahan:

1. Register melalui `/register`.
2. Login melalui `/login`.
3. Verifikasi email.
4. Lakukan pemeriksaan seperti biasa.
5. Buka `/riwayat` untuk melihat pemeriksaan sebelumnya.
6. Buka detail riwayat untuk melihat kembali hasil.
7. Berikan feedback jika hasil dirasa benar atau kurang tepat.
8. Ekspor riwayat ke CSV jika diperlukan.
9. Kelola nama, email, password, dan akun melalui halaman profil.

---

# Processing Pipeline

Pipeline asynchronous Doksli menggunakan beberapa queue:

```text
Submission
   │
   ▼
ProcessSubmission
   │
   ▼
ExtractSubmissionText
   ├── extract-text
   └── extract-media
   │
   ▼
ClassifySubmission
   └── inference
   │
   ▼
GenerateSubmissionExplanation
   └── explanation
   │
   ▼
Completed / Failed
```

Queue yang digunakan oleh development script:

```text
extract-text
extract-media
inference
explanation
default
```

Untuk menjalankan worker secara manual:

```bash
php artisan queue:work --queue=extract-text,extract-media,inference,explanation,default --tries=3
```

Cek failed jobs:

```bash
php artisan queue:failed
```

Retry semua failed jobs:

```bash
php artisan queue:retry all
```

---

# Halaman Utama

| Route | Fungsi |
|---|---|
| `/` | Homepage dan checker utama |
| `/cara-kerja` | Penjelasan cara kerja sistem |
| `/tentang` | Informasi tentang Doksli |
| `/kebijakan-privasi` | Kebijakan privasi |
| `/statistik` | Statistik pemeriksaan |
| `/hasil/{id}` | Hasil pemeriksaan |
| `/login` | Login pengguna |
| `/register` | Registrasi pengguna |
| `/profil` | Pengaturan profil |
| `/riwayat` | Riwayat pengguna terverifikasi |
| `/admin` | Filament Admin Panel |

---

# Administrator

Panel admin menggunakan **Filament** dan dapat digunakan untuk mengelola serta memonitor data aplikasi.

Data development dapat dibuat dengan:

```bash
php artisan migrate --seed
```

Seeder saat ini menyediakan akun administrator development:

```text
Email    : admin@hoaxlin.id
Password : password
```

> Credential tersebut hanya untuk development/testing. Jangan gunakan credential seed di production.

Buka:

```text
http://127.0.0.1:8000/admin
```

Panel admin mencakup resource seperti:

- Users
- Datasets
- Submissions
- Detection Results
- Feedback

---

# Testing

## Laravel

Jalankan seluruh test:

```bash
php artisan test
```

## BERT Service

```bash
cd bert-service
python -m pytest -q
```

## Frontend Production Build

```bash
npm run build
```

## PHP Code Style

```bash
./vendor/bin/pint
```

Windows:

```powershell
.\vendor\bin\pint
```

---

# Struktur Project

```text
doksli/
├── app/
│   ├── Actions/
│   ├── Enums/
│   ├── Filament/
│   ├── Http/
│   ├── Jobs/
│   ├── Models/
│   └── Services/
├── bert-service/
│   ├── app/                 # FastAPI inference service
│   ├── dataset/             # Dataset preparation pipeline
│   ├── train/               # Fine-tuning/evaluation pipeline
│   ├── tests/
│   └── README.md
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
├── routes/
├── storage/
├── tests/
├── AGENTS.md
├── DESIGN_SYSTEM.md
├── PRD.md
├── PROJECT_PROGRESS.md
├── TASK.md
└── UI_REFACTOR_PLAN.md
```

---

# Dokumentasi Project

Repository memiliki beberapa dokumen tambahan:

- [`PRD.md`](PRD.md) — requirement dan tujuan produk.
- [`DESIGN_SYSTEM.md`](DESIGN_SYSTEM.md) — prinsip dan aturan desain antarmuka Doksli.
- [`UI_REFACTOR_PLAN.md`](UI_REFACTOR_PLAN.md) — rencana refactoring UI.
- [`TASK.md`](TASK.md) — task implementasi/refactoring.
- [`PROJECT_PROGRESS.md`](PROJECT_PROGRESS.md) — catatan audit/progress project pada waktu tertentu.
- [`AGENTS.md`](AGENTS.md) — aturan kerja untuk coding agent yang berkontribusi pada repository.
- [`bert-service/README.md`](bert-service/README.md) — dokumentasi lengkap dataset, training, serving, dan pengujian IndoBERT.

> Beberapa dokumen progress bersifat snapshot pada waktu audit tertentu. Untuk perilaku aplikasi terbaru, jadikan source code dan test suite sebagai acuan utama.

---

# Troubleshooting

## Submission berhenti pada proses klasifikasi

Pastikan queue worker berjalan:

```bash
php artisan queue:work --queue=extract-text,extract-media,inference,explanation,default --tries=3
```

Kemudian periksa:

```bash
php artisan queue:failed
```

## BERT Service Tidak Ready

Periksa:

```text
http://127.0.0.1:8001/health/live
http://127.0.0.1:8001/health/ready
```

Jika liveness berhasil tetapi readiness gagal, biasanya model tidak dapat dimuat. Periksa:

- `BERT_MODEL_PATH`
- file model dan tokenizer
- `BERT_MODEL_VERSION`
- kompatibilitas label map

## Laravel Mendapat 401 dari BERT

Pastikan nilai berikut identik:

```text
Laravel .env               → BERT_SERVICE_TOKEN
bert-service/.env          → BERT_SERVICE_TOKEN
```

Setelah mengubah `.env`:

```bash
php artisan optimize:clear
```

## Asset Frontend Tidak Muncul

Development:

```bash
npm run dev
```

Production:

```bash
npm run build
```

## Storage atau File Upload Bermasalah

```bash
php artisan storage:link
```

Pastikan direktori `storage` dan `bootstrap/cache` dapat ditulis oleh proses PHP.

## Database Belum Siap

```bash
php artisan migrate --seed
```

Untuk reset database development:

```bash
php artisan migrate:fresh --seed
```

> Perintah `migrate:fresh` akan menghapus seluruh data database aktif.

---

# Deployment Notes

Sebelum deployment production:

- gunakan `APP_ENV=production`;
- gunakan `APP_DEBUG=false`;
- gunakan credential database production;
- gunakan token internal BERT yang kuat dan berbeda dari development;
- jangan expose FastAPI inference endpoint langsung ke publik tanpa proteksi;
- jalankan queue worker menggunakan process manager;
- build frontend dengan `npm run build`;
- gunakan HTTPS;
- konfigurasi email provider production;
- pastikan private media tidak dapat diakses tanpa authorization;
- gunakan model artifact yang sudah diverifikasi;
- monitor failed jobs dan error log;
- gunakan credential admin production sendiri;
- jangan menggunakan akun/password dari seeder.

Contoh optimasi Laravel:

```bash
php artisan optimize
```

---

## Repository

Doksli dikembangkan sebagai proyek sistem deteksi berita hoax berbasis IndoBERT untuk Bahasa Indonesia.

**Repository:** `jikrilar/doksli`
