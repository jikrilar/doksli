# Doksli — Design System

## 1. Purpose

Dokumen ini menjadi sumber acuan utama untuk seluruh desain antarmuka **Doksli**.

Tujuan design system ini adalah memastikan seluruh antarmuka:

* sederhana,
* kredibel,
* mudah dipahami,
* konsisten,
* responsif,
* accessible,
* information-first,
* dan tidak memiliki karakter visual generik khas AI-generated interface.

Design system ini berlaku untuk seluruh public interface Doksli, termasuk:

* Homepage
* Checker / form pemeriksaan
* Hasil pemeriksaan
* Riwayat
* Profil
* Authentication
* Cara Kerja
* Tentang
* Kebijakan Privasi
* Statistik
* Empty states
* Loading states
* Error states
* Modal
* Flash message
* Navigation
* Footer

Design system ini **tidak mengatur tampilan Filament Admin Panel**, kecuali ada kebutuhan khusus di masa depan.

---

# 2. Product Design Direction

Doksli adalah **alat bantu pemeriksaan informasi**, bukan showcase teknologi AI.

Antarmuka harus memberikan kesan:

> reliable, calm, clear, editorial, utilitarian, trustworthy.

Bukan:

> futuristic, AI-powered, neon, experimental, flashy, startup landing page.

Teknologi seperti IndoBERT, OCR, transkripsi, dan AI tetap penting secara produk, tetapi tidak boleh mendominasi identitas visual.

Prioritas informasi:

1. Apa yang dapat dilakukan pengguna.
2. Apa hasil pemeriksaannya.
3. Mengapa hasil tersebut muncul.
4. Apa keterbatasan hasilnya.
5. Detail teknis.

---

# 3. Core Design Principles

## 3.1 Utility First

Setiap halaman harus membantu pengguna menyelesaikan tugas.

Elemen dekoratif tidak boleh mengalahkan fungsi utama.

Contoh:

Homepage harus memprioritaskan **form pemeriksaan**, bukan hero marketing besar.

---

## 3.2 Information Before Decoration

Gunakan:

* typography,
* hierarchy,
* spacing,
* borders,
* alignment,

untuk membedakan informasi.

Jangan otomatis membungkus semua konten dalam card.

---

## 3.3 Trust Over Excitement

Doksli membahas informasi yang bisa benar, salah, atau meragukan.

Karena itu desain harus terasa tenang dan objektif.

Hindari visual yang terlalu dramatis.

---

## 3.4 Progressive Disclosure

Informasi teknis tidak harus langsung tampil.

Contoh:

* model version,
* prompt version,
* translation model,
* internal processing information,

ditempatkan pada bagian **Detail Teknis** yang dapat dibuka pengguna.

---

## 3.5 Consistency Over Novelty

Komponen yang melakukan fungsi sama harus menggunakan pola visual dan interaksi yang sama.

Jangan membuat variasi styling baru hanya untuk satu halaman tanpa alasan yang kuat.

---

# 4. Anti AI-Slop Rules

Bagian ini bersifat **mandatory**.

Agent atau developer tidak boleh memperkenalkan pola berikut tanpa alasan khusus yang terdokumentasi.

## 4.1 Dilarang Menggunakan

### Glassmorphism

Jangan gunakan:

```css
backdrop-filter: blur(...)
background: rgba(..., 0.x)
```

sebagai surface utama.

---

### Glow Effects

Jangan gunakan:

* floating glow orb,
* neon halo,
* radial glow dekoratif,
* glowing borders,
* colored shadow besar.

---

### Excessive Gradients

Gradient tidak boleh menjadi bahasa visual utama.

Hindari gradient pada:

* headline,
* logo,
* button,
* stat number,
* card border,
* navigation,
* progress bar.

Gunakan warna solid.

---

### Decorative Background Grid

Jangan menggunakan:

* grid futuristik,
* glowing lines,
* particle background,
* animated gradient background.

---

### Floating Cards

Jangan membuat semua section menjadi card dengan shadow dan border radius besar.

Card hanya digunakan jika benar-benar merepresentasikan grouped content.

---

### Excessive Pills

Pill/chip tidak digunakan untuk semua metadata.

Gunakan hanya untuk:

* status,
* filter aktif,
* small categorical metadata.

---

### Emoji as Primary UI Icon

Jangan gunakan emoji seperti:

* 🧠
* 🤖
* 🔬
* 📊
* 🚨
* ⚠️
* 🔍

sebagai icon utama interface.

Gunakan SVG icon dengan visual style konsisten.

Emoji tetap boleh muncul pada konten editorial apabila memang diperlukan.

---

### Decorative Entrance Animation

Jangan menggunakan:

* fade-in setiap card,
* stagger animation,
* scale-on-load,
* intersection reveal,
* floating animation.

Page content harus tampil secara langsung.

---

### Marketing Buzzword UI

Hindari copy seperti:

* Powered by AI
* Advanced AI Technology
* Revolutionize
* Smart AI Detection
* Next Generation AI

jika tidak memberikan informasi penting kepada pengguna.

---

# 5. Visual Identity

## 5.1 Primary Theme

Doksli menggunakan **light theme sebagai default**.

Tema utama harus memberikan kesan seperti:

* research tool,
* fact-checking platform,
* modern editorial product,
* productivity utility.

---

# 6. Color System

Gunakan semantic color tokens.

## Background

```css
--color-background: #f7f7f5;
--color-surface: #ffffff;
--color-surface-subtle: #f4f4f5;
```

---

## Text

```css
--color-foreground: #18181b;
--color-text-secondary: #52525b;
--color-text-muted: #71717a;
--color-text-disabled: #a1a1aa;
```

---

## Border

```css
--color-border: #e4e4e7;
--color-border-strong: #d4d4d8;
```

---

## Brand

```css
--color-brand: #1d4ed8;
--color-brand-hover: #1e40af;
--color-brand-subtle: #eff6ff;
```

Brand blue digunakan secara terbatas untuk:

* primary button,
* active navigation,
* focus state,
* link,
* selected control.

---

# 7. Semantic Colors

## Valid

```css
--color-success: #15803d;
--color-success-bg: #f0fdf4;
--color-success-border: #bbf7d0;
```

---

## Meragukan

```css
--color-warning: #b45309;
--color-warning-bg: #fffbeb;
--color-warning-border: #fde68a;
```

---

## Hoax

```css
--color-danger: #b91c1c;
--color-danger-bg: #fef2f2;
--color-danger-border: #fecaca;
```

---

## Information

```css
--color-info: #1d4ed8;
--color-info-bg: #eff6ff;
--color-info-border: #bfdbfe;
```

---

# 8. Color Usage Rules

Semantic colors hanya digunakan berdasarkan makna.

Merah:

* error,
* destructive action,
* hoax result.

Hijau:

* valid result,
* success.

Amber:

* warning,
* uncertain,
* meragukan.

Biru:

* brand,
* interaction,
* informational content.

Jangan menggunakan semantic colors hanya untuk dekorasi.

---

# 9. Typography

## Primary Font

Gunakan:

```text
Inter
```

Fallback:

```css
font-family:
    Inter,
    ui-sans-serif,
    system-ui,
    -apple-system,
    BlinkMacSystemFont,
    "Segoe UI",
    sans-serif;
```

Tidak diperlukan display font terpisah.

---

# 10. Typography Scale

## Display

Digunakan sangat terbatas.

```text
48px / 56px
font-weight: 700
```

Gunakan hanya jika homepage benar-benar membutuhkannya.

---

## H1

```text
36px / 44px
font-weight: 700
letter-spacing: -0.02em
```

Mobile:

```text
30px / 38px
```

---

## H2

```text
28px / 36px
font-weight: 700
```

---

## H3

```text
20px / 28px
font-weight: 600
```

---

## Body Large

```text
18px / 28px
font-weight: 400
```

---

## Body

```text
16px / 24px
font-weight: 400
```

---

## Body Small

```text
14px / 20px
```

---

## Caption

```text
12px / 16px
```

---

# 11. Typography Rules

Jangan menggunakan uppercase panjang untuk section title.

Uppercase hanya boleh digunakan untuk:

* small metadata,
* table header,
* labels tertentu.

Hindari penggunaan:

```text
font-weight: 800
font-weight: 900
```

kecuali pada wordmark/logo jika benar-benar diperlukan.

Body text idealnya maksimal:

```text
65–75 characters per line
```

untuk content-heavy pages.

---

# 12. Spacing System

Gunakan basis **4px**.

```text
4px   = 1
8px   = 2
12px  = 3
16px  = 4
20px  = 5
24px  = 6
32px  = 8
40px  = 10
48px  = 12
64px  = 16
80px  = 20
96px  = 24
```

Gunakan spacing konsisten daripada angka arbitrary seperti:

```text
17px
23px
37px
```

kecuali benar-benar diperlukan.

---

# 13. Page Layout

## Main Container

Maximum width:

```text
1200px
```

Horizontal padding:

Desktop:

```text
24px
```

Tablet:

```text
20px
```

Mobile:

```text
16px
```

---

## Content Container

Untuk form dan halaman detail:

```text
720–840px
```

---

## Prose Container

Untuk:

* Tentang
* Cara Kerja
* Kebijakan Privasi

gunakan:

```text
680–760px
```

agar mudah dibaca.

---

# 14. Vertical Rhythm

Page section spacing:

Desktop:

```text
64–96px
```

Mobile:

```text
40–64px
```

Section yang masih satu konteks tidak harus memiliki jarak besar.

---

# 15. Border Radius

Gunakan radius konservatif.

```text
4px   small
6px   control
8px   default
12px  large surface
999px pill only
```

Default:

```text
8px
```

Jangan menggunakan radius `20px+` pada semua card.

---

# 16. Shadow

Gunakan shadow sangat terbatas.

Default surface tidak perlu shadow.

Jika membutuhkan elevation:

```css
box-shadow:
    0 1px 2px rgba(0,0,0,0.04),
    0 1px 3px rgba(0,0,0,0.06);
```

Modal:

```css
box-shadow:
    0 20px 40px rgba(0,0,0,0.12);
```

Tidak ada colored shadow.

---

# 17. Buttons

## Primary Button

Digunakan untuk action utama.

Contoh:

* Periksa Berita
* Simpan
* Masuk
* Daftar

Visual:

```text
background: brand blue
text: white
border: none
radius: 8px
font-weight: 600
```

---

## Secondary Button

Gunakan untuk action sekunder.

```text
background: white
border: standard
text: foreground
```

---

## Ghost Button

Untuk action low-priority.

Contoh:

* kembali,
* reset,
* close.

Tidak memiliki background default.

---

## Danger Button

Hanya untuk destructive action.

Contoh:

```text
Hapus akun
```

---

# 18. Button Rules

Jangan menggunakan gradient.

Jangan menggunakan:

```text
translateY()
large shadow
glow
```

saat hover.

Hover cukup:

* perubahan background,
* perubahan border,
* perubahan text color.

Animation:

```text
150ms
```

---

# 19. Forms

Semua form controls harus menggunakan visual language yang sama.

Default height:

```text
40–44px
```

Large:

```text
48px
```

Properties:

```text
background: white
border: #d4d4d8
border-radius: 8px
```

Focus:

```text
border: brand
focus ring: 2–3px
```

---

# 20. Form Labels

Label selalu tampil di atas field.

```text
font-size: 14px
font-weight: 500
```

Placeholder tidak boleh menggantikan label.

---

# 21. Validation

Error muncul langsung di bawah field.

Contoh:

```text
Alamat email
[email................]

Alamat email tidak valid.
```

Jangan hanya mengandalkan warna.

Gunakan:

* icon jika diperlukan,
* text error,
* `aria-invalid`.

---

# 22. Textarea

Untuk checker text input:

Desktop:

```text
minimum height: 220–260px
```

Mobile:

```text
180–220px
```

Character count berada di bawah kanan.

Minimum character information di bawah kiri.

---

# 23. Checker Input Navigation

Jenis input:

* Teks
* Gambar
* Video
* Tautan

gunakan tabs.

Default:

```text
border-bottom
```

atau segmented control sederhana.

Jangan gunakan gradient selected state.

Contoh:

```text
Teks        Gambar        Video        Tautan
────
```

---

# 24. File Upload

Dropzone menggunakan:

```text
white surface
dashed border
8–12px radius
center content
```

Contoh:

```text
Unggah gambar

Klik untuk memilih file
atau tarik file ke area ini.

PNG, JPG, WEBP · Maks. 10 MB
```

Hover hanya mengubah:

```text
border-color
background subtle
```

Tidak ada:

```text
scale
rotate
glow
```

---

# 25. CAPTCHA

CAPTCHA harus terlihat sebagai bagian dari form, bukan card teknologi.

Contoh:

```text
Verifikasi keamanan

7 + 5 = ?
[      ]
```

Jangan tampilkan rate limit normal seperti:

```text
30/IP
100/account
```

kepada pengguna kecuali memang relevan.

---

# 26. Cards

Card digunakan jika sebuah informasi memang membentuk satu unit yang terpisah.

Contoh yang valid:

* result summary,
* upload area,
* modal,
* grouped account setting.

Jangan membuat card hanya karena ada section baru.

---

# 27. Card Style

Default:

```text
background: white
border: 1px solid border
border-radius: 12px
```

Padding:

```text
20–24px
```

Shadow optional.

Tidak boleh menggunakan backdrop blur.

---

# 28. Status Badge

Status badge boleh menggunakan pill.

Contoh:

```text
✓ Valid
! Meragukan
× Hoax
```

Badge harus memiliki:

* icon,
* text,
* background semantic,
* foreground semantic.

Tidak boleh mengandalkan warna saja.

---

# 29. Result Page

Halaman hasil harus memiliki hierarchy:

## Level 1

Verdict.

Contoh:

```text
Indikasi: Hoax
```

---

## Level 2

Short explanation.

```text
Model menemukan pola yang lebih konsisten dengan konten hoax.
```

---

## Level 3

Confidence.

```text
Tingkat keyakinan model: 87%
```

Confidence tidak boleh menjadi elemen visual terbesar di halaman.

---

## Level 4

Explanation.

```text
Mengapa hasil ini muncul
```

---

## Level 5

Input yang dianalisis.

---

## Level 6

Verification disclaimer.

---

## Level 7

Detail teknis.

---

# 30. Confidence Visualization

Progress bar sederhana.

Tidak menggunakan:

```text
shimmer
glow
animated gradient
```

Gunakan solid semantic color berdasarkan hasil.

---

# 31. Technical Details

Informasi seperti:

* model version,
* prompt version,
* translation model,
* submission ID,
* processing pipeline,

harus masuk ke:

```text
Detail teknis
```

yang dapat menggunakan native:

```html
<details>
```

atau disclosure component.

Default state:

```text
collapsed
```

---

# 32. Tables

Riwayat menggunakan data table di desktop.

Headers:

```text
Input
Jenis
Hasil
Tanggal
Aksi
```

Jangan membuat table row menjadi floating card.

Gunakan:

```text
border-bottom
hover background subtle
```

---

# 33. Mobile History

Pada mobile, row dapat berubah menjadi stacked layout.

Contoh:

```text
Hoax

Presiden mengumumkan kebijakan...
Teks · 7 Sep 2026

Lihat detail →
```

Tetap gunakan border separator sederhana.

---

# 34. Navigation

## Desktop Navbar

Navbar:

* sticky,
* white,
* bottom border,
* tidak transparan.

Height sekitar:

```text
64px
```

---

## Navbar Structure

```text
Doksli

Cek Berita
Cara Kerja
Tentang

Masuk / Account
```

---

# 35. Active Navigation

Gunakan:

* foreground stronger,
* font weight,
* optional bottom border.

Tidak menggunakan animated gradient underline.

---

# 36. Mobile Navigation

Gunakan menu overlay atau drawer sederhana.

Tidak perlu full-screen futuristic overlay.

Navigation harus:

* keyboard accessible,
* memiliki close button,
* lock body scroll saat terbuka.

---

# 37. Footer

Footer harus sederhana.

Contoh structure:

```text
Doksli

Alat bantu pemeriksaan informasi berbasis
model klasifikasi bahasa Indonesia.

Cara Kerja
Tentang
Kebijakan Privasi
GitHub

© 2026
```

Jangan menampilkan social icon palsu jika URL belum tersedia.

---

# 38. Homepage

Homepage bukan landing page marketing tradisional.

Urutan ideal:

## 1. Intro singkat

```text
Periksa informasi sebelum membagikannya.
```

---

## 2. Checker

Checker harus terlihat tanpa banyak scroll pada desktop.

---

## 3. Disclaimer

Jelaskan bahwa hasil bersifat probabilistik.

---

## 4. Cara kerja singkat

Maksimal 3–4 langkah.

---

## 5. Trust / Methodology

Jelaskan IndoBERT dan proses secara sederhana.

---

## 6. CTA akhir

Sederhana.

---

# 39. Hero Rules

Homepage tidak menggunakan:

* giant heading 72px,
* gradient text,
* AI badge,
* glow,
* stats card,

di atas checker.

Heading maksimal sekitar:

```text
40–48px desktop
30–36px mobile
```

Checker tetap harus menjadi fokus visual utama.

---

# 40. Statistics

Jangan otomatis membuat metric menjadi stat cards.

Gunakan plain text summary jika cukup.

Contoh:

```text
38 pemeriksaan
18 valid · 13 hoax · 7 meragukan
```

Gunakan chart/card hanya jika visualisasi benar-benar memberikan insight.

---

# 41. Accuracy Claims

Angka seperti:

```text
95% akurasi
```

hanya boleh ditampilkan jika:

* berasal dari evaluasi model sebenarnya,
* memiliki dataset dan methodology yang jelas,
* dapat dipertanggungjawabkan.

Jangan menggunakan angka dummy atau marketing estimate.

---

# 42. Authentication

Login/Register:

* centered container,
* max-width ±400px,
* simple form,
* no decorative background.

Tidak menggunakan:

* glow orb,
* gradient card,
* giant logo.

---

# 43. Profile / Settings

Profile menggunakan pattern settings page.

Contoh:

```text
Pengaturan akun

Profil
----------------

Keamanan
----------------

Hapus akun
----------------
```

Tidak perlu satu card untuk setiap section apabila separator sudah cukup.

---

# 44. Cara Kerja

Gunakan pola documentation/timeline.

Contoh:

```text
01
Input

↓

02
Ekstraksi

↓

03
Klasifikasi IndoBERT

↓

04
Hasil dan penjelasan
```

Jangan menggunakan emoji besar dan glowing cards.

---

# 45. Content Pages

Untuk:

* Tentang
* Kebijakan Privasi,

gunakan editorial/prose layout.

Prioritaskan:

* readability,
* headings,
* paragraphs,
* lists,
* links.

Jangan gunakan dashboard card layout.

---

# 46. Icons

Gunakan icon SVG dengan konsistensi:

```text
stroke width: 1.75–2
size: 16 / 20 / 24
round joins
round caps
```

Semua icon harus berasal dari satu visual family.

Hindari mencampurkan banyak gaya icon.

---

# 47. Icon Sizes

Small control:

```text
16px
```

Default:

```text
20px
```

Section icon:

```text
24px
```

Hindari icon 48–80px kecuali ilustrasi memang diperlukan.

---

# 48. Motion

Motion digunakan hanya untuk menjelaskan state.

Allowed:

* dropdown,
* modal,
* accordion,
* button state,
* tab content,
* progress.

Duration:

```text
150–200ms
```

---

# 49. Reduced Motion

Respect:

```css
@media (prefers-reduced-motion: reduce)
```

Disable atau kurangi animation.

---

# 50. Loading States

Loading tidak perlu decorative.

Gunakan:

* spinner kecil,
* progress bar,
* textual processing state.

Contoh:

```text
Mengekstrak teks...

Menganalisis dengan model...

Menyiapkan hasil...
```

Jangan membuat fake progress jika proses backend tidak memberikan progress sebenarnya.

---

# 51. Empty States

Empty state harus:

* menjelaskan kondisi,
* memberikan next action.

Contoh:

```text
Belum ada riwayat pemeriksaan.

Pemeriksaan yang kamu lakukan akan muncul di sini.

[Periksa berita]
```

Tidak perlu emoji besar.

---

# 52. Error States

Error message harus menjelaskan:

1. apa yang gagal,
2. apa yang dapat dilakukan user.

Buruk:

```text
Something went wrong.
```

Baik:

```text
Tautan tidak dapat diproses.

Pastikan URL dapat diakses secara publik lalu coba kembali.
```

---

# 53. Flash Messages

Toast/banner menggunakan semantic style.

Maximum width:

```text
480–560px
```

Tidak menggunakan blur.

Harus memiliki:

* icon,
* message,
* optional close button.

---

# 54. Modals

Modal hanya digunakan jika user harus membuat keputusan penting.

Contoh:

* hapus akun,
* destructive action.

Jangan menggunakan modal untuk informasi biasa.

---

# 55. Responsive Breakpoints

Gunakan Tailwind defaults jika memungkinkan.

Key breakpoints:

```text
sm: 640px
md: 768px
lg: 1024px
xl: 1280px
```

Design dimulai dari mobile-friendly layout.

---

# 56. Mobile Rules

Pada mobile:

* navigation disederhanakan,
* multi-column menjadi single column,
* button penting boleh full width,
* table berubah menjadi stacked rows,
* horizontal tabs boleh scroll,
* spacing diperkecil,
* typography scale turun secara proporsional.

Tidak boleh ada horizontal overflow.

---

# 57. Accessibility

Target minimal:

```text
WCAG 2.1 AA
```

Wajib:

* semantic HTML,
* keyboard navigation,
* visible focus,
* label untuk form,
* `aria-live` untuk async status,
* alt text,
* sufficient contrast,
* no color-only meaning.

---

# 58. Focus State

Semua interactive elements harus memiliki visible focus ring.

Contoh:

```css
outline: 2px solid var(--color-brand);
outline-offset: 2px;
```

Jangan menghapus outline tanpa replacement.

---

# 59. Link Style

Links dalam body text:

```text
brand blue
underline on hover
```

Links harus terlihat berbeda dari regular text.

---

# 60. Content Language

Primary UI language:

```text
Bahasa Indonesia
```

Gunakan bahasa sederhana.

Hindari istilah teknis jika tidak perlu.

Contoh:

Daripada:

```text
Inference pipeline sedang berjalan.
```

Gunakan:

```text
Berita sedang dianalisis.
```

---

# 61. Technical Terminology

Istilah seperti:

* BERT,
* OCR,
* confidence score,
* inference,

boleh digunakan pada halaman metodologi atau detail teknis.

Pada main user flow, beri istilah yang lebih mudah dipahami.

---

# 62. Brand Naming

Gunakan:

```text
Doksli
```

sebagai nama produk pada UI.

Gunakan bentuk yang konsisten.

Hindari variasi capitalization seperti:

```text
DOKSLI
doksli
DokSli
```

kecuali konteks teknis seperti domain atau repository.

---

# 63. CSS Architecture

Gunakan Tailwind CSS sebagai styling utama.

`app.css` digunakan untuk:

1. Tailwind import.
2. Theme tokens.
3. Base styles.
4. Beberapa reusable custom utilities yang benar-benar diperlukan.
5. Accessibility/motion overrides.

Hindari file CSS menjadi kumpulan style custom untuk setiap komponen.

---

# 64. Inline Style Rule

Hindari:

```html
style="..."
```

pada Blade.

Inline style hanya diperbolehkan jika nilainya benar-benar dynamic dan tidak praktis direpresentasikan melalui class.

Contoh acceptable:

```blade
style="width: {{ $progress }}%"
```

Jika styling statis, gunakan class.

---

# 65. JavaScript Architecture

JavaScript hanya menangani behavior.

Jangan menggunakan JS untuk styling default.

Buruk:

```js
element.style.background = ...
element.style.color = ...
```

Lebih baik:

```js
element.classList.add('active')
```

dan styling berada di CSS/Tailwind.

---

# 66. JavaScript Modules

Jika behavior mulai kompleks, gunakan:

```text
resources/js/
├── app.js
└── modules/
    ├── navigation.js
    ├── checker.js
    ├── upload.js
    └── result.js
```

Jangan menambahkan duplicate event listener di Blade dan `app.js`.

---

# 67. Blade Components

Ekstrak komponen apabila benar-benar digunakan berulang.

Candidate:

```text
components/
├── ui/
│   ├── button.blade.php
│   ├── alert.blade.php
│   ├── badge.blade.php
│   ├── input.blade.php
│   ├── textarea.blade.php
│   └── empty-state.blade.php
│
└── layout/
    ├── navbar.blade.php
    └── footer.blade.php
```

Hindari over-engineering.

Jangan membuat Blade component untuk elemen yang hanya muncul sekali.

---

# 68. Application Shell

`layouts/app.blade.php` hanya bertanggung jawab atas:

* HTML head,
* global assets,
* navbar include,
* flash message,
* main slot/yield,
* footer include,
* Livewire assets.

Jangan menempatkan ratusan baris navbar/footer/JS inline langsung di layout.

---

# 69. Functional Safety During Refactor

UI refactoring tidak boleh mengubah contract backend tanpa alasan.

Pertahankan:

```text
route names
form method
form action
CSRF
input names
Livewire directives
submission IDs
captcha field
authentication flow
result properties
upload constraints
```

Refactor harus memisahkan:

```text
presentation change
```

dengan:

```text
functional change
```

---

# 70. Regression Rule

Setelah refactoring setiap major page, pastikan behavior sebelumnya tetap berjalan.

Critical flows:

* Text submission
* Image upload
* Video upload
* Video URL
* Article URL
* CAPTCHA
* Result progress
* Result rendering
* Authentication
* Email verification
* History
* Filtering
* Profile update
* Password update
* Account deletion
* Feedback
* PDF export
* Private media access

---

# 71. Page Priority

Urutan implementasi UI v2:

## Phase 1 — Foundation

* color tokens
* typography
* spacing
* forms
* buttons
* statuses
* layout primitives

---

## Phase 2 — Application Shell

* navbar
* mobile navigation
* footer
* global alerts
* layout

---

## Phase 3 — Core User Journey

* Homepage
* Checker
* Loading
* Result

---

## Phase 4 — User Account

* Login
* Register
* Forgot password
* Reset password
* Email verification
* Profile
* History

---

## Phase 5 — Informational Pages

* Cara Kerja
* Tentang
* Kebijakan Privasi
* Statistik

---

## Phase 6 — Cleanup

* remove old CSS
* remove unused JS
* remove inline style
* responsive pass
* accessibility pass
* regression test

---

# 72. Definition of Done — Component

Sebuah component dianggap selesai jika:

* menggunakan design tokens,
* responsive,
* keyboard accessible,
* memiliki focus state,
* tidak menggunakan inline static style,
* tidak memperkenalkan design pattern yang dilarang,
* memiliki state normal/hover/focus/disabled/error jika relevan.

---

# 73. Definition of Done — Page

Sebuah halaman dianggap selesai jika:

* mengikuti hierarchy design system,
* responsive desktop/mobile,
* tidak memiliki horizontal overflow,
* semua interactive element dapat digunakan keyboard,
* semantic colors digunakan sesuai makna,
* tidak ada glass/glow/neon,
* tidak ada unnecessary gradient,
* tidak ada decorative animation,
* existing functionality tetap bekerja.

---

# 74. Agent Guardrails

Untuk AI coding agent yang mengerjakan Doksli:

## DO

* reuse existing design tokens,
* reuse existing components,
* favor simple layouts,
* prioritize readability,
* minimize visual noise,
* preserve existing functionality,
* test responsive states,
* preserve accessibility.

## DO NOT

* redesign halaman dengan gradient,
* membuat glow,
* membuat glass card,
* menambahkan emoji sebagai UI icon,
* membuat animated background,
* menambahkan decorative stat cards,
* membuat setiap section menjadi card,
* menambah library UI baru tanpa kebutuhan,
* mengubah backend ketika hanya diminta mengubah UI,
* menghapus technical information yang diperlukan untuk Tugas Akhir.

---

# 75. Design Review Checklist

Sebelum menerima perubahan UI, tanyakan:

### Visual

* Apakah ada gradient yang sebenarnya tidak diperlukan?
* Apakah ada glow?
* Apakah terlalu banyak cards?
* Apakah terlalu banyak pills?
* Apakah hierarchy terlihat tanpa efek dekoratif?
* Apakah halaman terasa seperti utility, bukan AI landing page?

### UX

* Apakah primary action langsung terlihat?
* Apakah user memahami apa yang harus dilakukan?
* Apakah technical jargon terlalu dominan?
* Apakah result mudah dipahami pengguna awam?

### Responsive

* Apakah berjalan pada 320px?
* Apakah layout tablet masuk akal?
* Apakah desktop tidak terlalu stretched?

### Accessibility

* Apakah keyboard navigation berfungsi?
* Apakah focus terlihat?
* Apakah color contrast cukup?
* Apakah status tidak hanya dibedakan lewat warna?

### Engineering

* Apakah inline style baru ditambahkan?
* Apakah JS mengatur styling secara langsung?
* Apakah component yang sudah ada sebenarnya dapat digunakan?
* Apakah backend contract berubah tanpa kebutuhan?

---

# 76. Final Design Character

Jika suatu keputusan desain masih meragukan, pilih opsi yang lebih:

```text
simple
calm
clear
neutral
editorial
functional
```

daripada opsi yang:

```text
flashy
futuristic
decorative
animated
glowing
AI-looking
```

Doksli harus terlihat seperti **alat yang dapat dipercaya untuk membantu pengguna memeriksa informasi**, bukan seperti demo teknologi AI.
