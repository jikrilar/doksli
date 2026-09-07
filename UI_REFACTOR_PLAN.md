# Doksli — UI Refactor Plan

## 1. Tujuan

Dokumen ini menjadi panduan implementasi refactor antarmuka Doksli berdasarkan `DESIGN_SYSTEM.md`.

Refactor ini bertujuan mengubah UI lama yang masih memiliki karakter:

* dark AI-style interface,
* glassmorphism,
* glow,
* gradient berlebihan,
* card-heavy layout,
* decorative animation,
* emoji sebagai icon utama,
* styling inline yang tersebar,

menjadi antarmuka yang:

* sederhana,
* kredibel,
* information-first,
* editorial,
* konsisten,
* mudah dipahami masyarakat umum,
* responsif,
* accessible,
* dan lebih mudah dipelihara secara teknis.

Refactor difokuskan pada **presentation layer**.

Fungsi bisnis, flow backend, BERT service, database, queue, autentikasi, dan endpoint existing harus tetap dipertahankan kecuali terdapat alasan teknis yang jelas untuk mengubahnya.

---

# 2. Prinsip Refactor

Seluruh perubahan harus mengikuti prinsip berikut.

## 2.1 Visual Refactor, Bukan Functional Rewrite

Default assumption:

> Jika suatu fitur sudah bekerja, jangan mengubah behavior-nya hanya karena UI sedang direfactor.

Pertahankan:

* route,
* controller contract,
* request field,
* validation,
* authentication flow,
* Livewire behavior,
* queue,
* submission lifecycle,
* result structure,
* database interaction.

---

## 2.2 Refactor Bertahap

Jangan merombak seluruh UI dalam satu perubahan besar.

Setiap fase harus menghasilkan kondisi aplikasi yang:

* tetap dapat dijalankan,
* tetap dapat diuji,
* tidak meninggalkan flow utama dalam kondisi setengah rusak.

---

## 2.3 Design System sebagai Source of Truth

Semua keputusan visual harus merujuk pada:

```text
DESIGN_SYSTEM.md
```

Jika implementasi bertentangan dengan design system, design system menjadi acuan utama kecuali dokumennya secara eksplisit diperbarui.

---

## 2.4 Core Flow Didahulukan

Prioritas utama:

```text
Homepage
→ Checker
→ Processing
→ Result
```

Halaman sekunder baru direfactor setelah core journey stabil.

---

# 3. Scope

## In Scope

Refactor mencakup:

* global layout,
* navbar,
* mobile navigation,
* footer,
* design tokens,
* typography,
* button,
* form control,
* alerts,
* badges,
* modal,
* tabs,
* upload area,
* homepage,
* checker,
* processing state,
* result page,
* history,
* profile,
* authentication pages,
* Cara Kerja,
* Tentang,
* Kebijakan Privasi,
* Statistik,
* responsive behavior,
* accessibility,
* cleanup CSS,
* cleanup JavaScript,
* Blade component extraction.

---

## Out of Scope

Tidak termasuk secara default:

* redesign Filament Admin Panel,
* perubahan database,
* perubahan model BERT,
* retraining model,
* perubahan queue architecture,
* penggantian Laravel/Blade/Livewire,
* migrasi frontend ke React/Vue/Next.js,
* perubahan API eksternal,
* perubahan authentication architecture,
* penambahan fitur produk baru.

Perubahan tersebut harus menjadi task terpisah.

---

# 4. Kondisi Awal

UI existing memiliki beberapa technical debt utama.

## Visual

* dark theme dominan,
* gradient digunakan pada banyak elemen,
* glass card digunakan hampir di seluruh halaman,
* glowing background decoration,
* card-heavy composition,
* excessive badge/pill,
* animation dekoratif,
* emoji sebagai interface icon,
* marketing-heavy homepage.

---

## CSS

Styling berada pada kombinasi:

```text
resources/css/app.css
+
Tailwind utilities
+
inline style pada Blade
```

Akibatnya:

* styling sulit dicari,
* perubahan global sulit dilakukan,
* banyak nilai visual tidak konsisten,
* reuse rendah.

---

## JavaScript

Behavior tersebar antara:

```text
resources/js/app.js
```

dan script inline pada Blade.

Beberapa behavior juga mengubah style langsung dengan JavaScript.

---

## Blade

Beberapa view memiliki:

* ukuran file sangat besar,
* layout dan styling tercampur,
* inline JavaScript,
* inline CSS,
* repeated markup.

---

# 5. Target Architecture

Struktur ideal setelah refactor:

```text
resources/
├── css/
│   └── app.css
│
├── js/
│   ├── app.js
│   └── modules/
│       ├── navigation.js
│       ├── checker.js
│       ├── upload.js
│       ├── result.js
│       └── profile.js
│
└── views/
    ├── components/
    │   ├── layout/
    │   │   ├── navbar.blade.php
    │   │   └── footer.blade.php
    │   │
    │   └── ui/
    │       ├── alert.blade.php
    │       ├── badge.blade.php
    │       ├── button.blade.php
    │       ├── empty-state.blade.php
    │       ├── input.blade.php
    │       ├── textarea.blade.php
    │       └── page-header.blade.php
    │
    ├── layouts/
    │   └── app.blade.php
    │
    ├── auth/
    ├── livewire/
    ├── welcome.blade.php
    ├── hasil.blade.php
    ├── riwayat.blade.php
    ├── profile.blade.php
    ├── cara-kerja.blade.php
    ├── tentang.blade.php
    └── kebijakan-privasi.blade.php
```

Struktur ini bersifat target, bukan kewajiban untuk membuat semua component sejak awal.

Hanya extract component jika benar-benar digunakan berulang.

---

# 6. Refactor Strategy

Refactor dibagi menjadi 8 fase.

---

# Phase 0 — Baseline & Safety

## Tujuan

Merekam behavior existing sebelum perubahan UI dilakukan.

## Task

* [x] Pastikan project dapat dijalankan secara lokal.
* [x] Pastikan asset Vite dapat di-build.
* [x] Catat halaman existing.
* [x] Catat flow utama.
* [x] Catat field form existing.
* [x] Catat route penting.
* [x] Catat Livewire behavior.
* [x] Catat mobile behavior utama.
* [x] Catat error state existing.

## Critical Flow Baseline

Pastikan flow berikut bekerja sebelum refactor:

* [x] Submit teks.
* [x] Submit gambar.
* [x] Submit video.
* [x] Submit video URL jika tersedia.
* [x] Submit artikel URL.
* [x] CAPTCHA.
* [x] Processing state.
* [x] Result selesai.
* [x] Result gagal.
* [x] Login.
* [x] Register.
* [x] Logout.
* [x] Forgot password.
* [x] Email verification.
* [x] History.
* [x] History filtering.
* [x] History detail.
* [x] Update profile.
* [x] Update password.
* [x] Delete account.
* [x] Feedback.
* [x] PDF export.
* [x] Private media access.

## Exit Criteria

Tidak ada perubahan visual besar sebelum baseline flow diketahui.

---

# Phase 1 — Design Foundation

## Tujuan

Mengganti fondasi visual lama sebelum halaman individual direfactor.

## Files

Primary:

```text
resources/css/app.css
```

Optional:

```text
resources/views/components/ui/*
```

## Task

### Theme Tokens

* [x] Ganti dark surface tokens menjadi light theme.
* [x] Tambahkan background token.
* [x] Tambahkan surface token.
* [x] Tambahkan foreground token.
* [x] Tambahkan secondary text token.
* [x] Tambahkan muted text token.
* [x] Tambahkan border token.
* [x] Tambahkan brand token.
* [x] Tambahkan semantic success/warning/danger/info tokens.

### Typography

* [x] Gunakan Inter sebagai primary font.
* [x] Hapus dependency Outfit jika sudah tidak digunakan.
* [x] Tentukan scale H1/H2/H3/body/caption.
* [x] Kurangi font weight 800/900 yang tidak perlu.

### Core Primitives

Bangun style untuk:

* [x] button primary,
* [x] button secondary,
* [x] ghost button,
* [x] danger button,
* [x] input,
* [x] textarea,
* [x] select,
* [x] checkbox,
* [x] label,
* [x] validation message,
* [x] alert,
* [x] badge,
* [x] card,
* [x] divider,
* [x] modal.

### Remove Legacy Visual Language

Mulai deprecated:

* [x] `.glass-card`
* [x] `.glass-card-solid`
* [x] `.gradient-text`
* [x] `.gradient-text-danger`
* [x] `.text-gradient-primary`
* [x] `.glow-orb`
* [x] `.glow-orb-1`
* [x] `.glow-orb-2`
* [x] decorative shimmer
* [x] floating animation
* [x] global reveal animation

Jangan langsung menghapus class jika halaman existing masih membutuhkannya.

Deprecated class dapat dihapus setelah seluruh consumer selesai direfactor.

## Exit Criteria

* Light theme aktif.
* Design tokens stabil.
* Core control memiliki visual consistent.
* Focus states tersedia.
* Tidak ada gradient/glow pada komponen baru.

---

# Phase 2 — Application Shell

## Tujuan

Menyederhanakan shell global sebelum halaman direfactor.

## Files

```text
resources/views/layouts/app.blade.php
resources/views/components/layout/navbar.blade.php
resources/views/components/layout/footer.blade.php
resources/js/app.js
resources/js/modules/navigation.js
```

## Navbar

### Task

* [x] Ubah navbar menjadi solid light surface.
* [x] Tambahkan subtle bottom border.
* [x] Pertahankan sticky behavior.
* [x] Hapus gradient logo.
* [x] Hapus animated gradient underline.
* [x] Sederhanakan auth state.
* [x] Sederhanakan account dropdown.
* [x] Gunakan consistent SVG icons.
* [x] Perbaiki keyboard accessibility.
* [x] Pastikan active navigation terlihat jelas.

---

## Mobile Navigation

* [x] Refactor menu mobile.
* [x] Hindari futuristic full-screen styling.
* [x] Tambahkan close button yang jelas.
* [x] Pertahankan body scroll locking.
* [x] Pastikan keyboard navigation bekerja.

---

## Footer

* [x] Sederhanakan footer.
* [x] Hapus fake social button.
* [x] Hanya tampilkan link yang benar-benar tersedia.
* [x] Kurangi jumlah kolom jika tidak diperlukan.
* [x] Gunakan hierarchy berbasis text dan spacing.

---

## Global Flash Message

* [x] Gunakan semantic alert styling.
* [x] Hapus blur.
* [x] Pertahankan auto dismiss jika relevan.
* [x] Pastikan message tetap accessible.

---

## Layout Cleanup

* [x] Kurangi inline style dalam layout.
* [x] Pindahkan behavior global dari inline script.
* [x] Hapus duplicate navbar listener.
* [x] Hapus duplicate flash behavior.

## Exit Criteria

Semua halaman existing sudah memakai shell global baru tanpa merusak navigation/authentication.

---

# Phase 3 — Homepage & Checker

## Tujuan

Membangun ulang core entry point Doksli.

## File

```text
resources/views/welcome.blade.php
```

Optional JS:

```text
resources/js/modules/checker.js
resources/js/modules/upload.js
```

---

## Homepage Structure

Target hierarchy:

```text
Intro
↓
Checker
↓
Disclaimer
↓
Cara kerja singkat
↓
Metodologi / trust
↓
CTA
```

---

## Hero / Intro

### Remove

* [x] glow orb,
* [x] animated grid,
* [x] AI badge,
* [x] gradient heading,
* [x] oversized heading,
* [x] decorative statistics.

### Implement

* [x] heading sederhana,
* [x] supporting copy pendek,
* [x] checker langsung terlihat,
* [x] responsive hierarchy.

---

## Checker

### Input Tabs

* [x] Refactor text/image/video/URL tabs.
* [x] Gunakan active state sederhana.
* [x] Pertahankan ARIA tab semantics.
* [x] Pastikan keyboard behavior tetap masuk akal.

---

## Text Input

* [x] Refactor textarea.
* [x] Pertahankan minlength.
* [x] Pertahankan character counter.
* [x] Gunakan helper text yang sederhana.

---

## Image Input

* [x] Refactor dropzone.
* [x] Hapus rotating/scaling icon.
* [x] Pertahankan preview.
* [x] Pertahankan drag-and-drop.
* [x] Pertahankan file constraints.

---

## Video Input

* [x] Sederhanakan file/URL switching.
* [x] Pertahankan existing request contract.
* [x] Perjelas bahwa processing video lebih lama.

---

## URL Input

* [x] Sederhanakan input.
* [x] Pertahankan URL preview jika berguna.
* [x] Kurangi visual decoration.

---

## CAPTCHA

* [x] Integrasikan sebagai bagian normal form.
* [x] Hapus card ungu.
* [x] Jangan tampilkan rate-limit metadata normal.
* [x] Pertahankan honeypot.
* [x] Pertahankan request field existing.

---

## Submit Button

* [x] Gunakan primary button solid.
* [x] Pertahankan text sesuai jenis input.
* [x] Pertahankan disabled state.

---

## Disclaimer

Tampilkan concise disclaimer dekat checker.

Contoh arah isi:

```text
Hasil merupakan estimasi model dan tidak menggantikan pemeriksaan fakta dari sumber independen.
```

---

## Homepage Secondary Content

### Cara Kerja Singkat

* [x] Maksimal 4 langkah.
* [x] Hindari card untuk tiap langkah jika tidak diperlukan.
* [x] Gunakan number + title + description.
* [x] Gunakan SVG icon hanya jika membantu.

### BERT Explanation

* [x] Kurangi technical marketing.
* [x] Jelaskan fungsi model secara sederhana.
* [x] Link ke Cara Kerja untuk detail lebih lanjut.

### Statistics

* [x] Hapus 95%+ jika belum didukung evaluasi aktual.
* [x] Hapus 15s jika bukan metric aktual.
* [x] Hanya tampilkan angka yang dapat dipertanggungjawabkan.

## Exit Criteria

* Checker menjadi visual focus utama.
* Tidak ada glass/glow/gradient lama.
* Semua empat jenis input tetap bekerja.
* Homepage responsive pada mobile dan desktop.

---

# Phase 4 — Processing & Result

## Tujuan

Mengubah output menjadi fact-check report yang lebih mudah dipahami.

## Files

```text
resources/views/hasil.blade.php
resources/views/livewire/submission-progress.blade.php
resources/js/modules/result.js
```

---

# Processing State

## Task

* [x] Sederhanakan progress interface.
* [x] Pertahankan Livewire polling.
* [x] Pertahankan fallback JS jika masih diperlukan.
* [x] Pertahankan no-JS fallback.
* [x] Hindari fake progress bila tidak berasal dari backend.
* [x] Gunakan textual progress stage.
* [x] Gunakan progress bar sederhana.

---

# Result Hierarchy

Target:

```text
Hasil pemeriksaan
↓
Verdict
↓
Short interpretation
↓
Confidence
↓
Explanation
↓
Analyzed content
↓
Verification guidance
↓
Actions
↓
Technical details
```

---

## Verdict

* [x] Jadikan verdict elemen utama.
* [x] Gunakan semantic status.
* [x] Gunakan SVG icon.
* [x] Jangan gunakan emoji.

Possible labels:

```text
Valid
Meragukan
Hoax
```

---

## Confidence

* [x] Jadikan informasi sekunder.
* [x] Hapus gradient percentage.
* [x] Hapus shimmer.
* [x] Gunakan simple progress bar.

---

## Explanation

* [x] Ganti heading “Penjelasan AI” jika terlalu teknis.
* [x] Gunakan heading seperti `Penjelasan hasil`.
* [x] Hapus robot emoji.
* [x] Pertahankan generated explanation existing.

---

## Input Content

* [x] Tampilkan submitted text secara readable.
* [x] Tampilkan extracted text jika tersedia.
* [x] Pertahankan media preview.
* [x] Pertahankan private media route.
* [x] Pertahankan translation information jika relevan.

---

## Technical Metadata

Pindahkan:

* model version,
* prompt version,
* translation model,
* pipeline information,

ke dalam:

```text
Detail teknis
```

Default collapsed.

---

## Verification Guidance

Tambahkan section yang mengingatkan user untuk cross-check.

Jangan membuat klaim bahwa sistem memberikan kebenaran absolut.

---

## Action Area

Pertahankan jika tersedia:

* [x] download PDF,
* [x] check another item,
* [x] history,
* [x] feedback.

## Exit Criteria

* Result dapat dipahami tanpa pengetahuan AI.
* Technical metadata tetap tersedia.
* Live progress tetap bekerja.
* Existing result data tidak berubah.

---

# Phase 5 — History & Account

## Tujuan

Mengubah authenticated UI dari card-heavy dashboard menjadi utility interface.

---

# History

## File

```text
resources/views/riwayat.blade.php
```

## Task

### Header

* [x] Hapus gradient heading.
* [x] Gunakan standard page header.

### Search

* [x] Pertahankan search.
* [x] Gunakan standard input.

### Filter

* [x] Sederhanakan filter input.
* [x] Pertahankan query parameter existing.
* [x] Kurangi excessive pill.
* [x] Gunakan badge hanya untuk result status.

### Table

* [x] Gunakan clean data table.
* [x] Hapus glass wrapper.
* [x] Gunakan row divider.
* [x] Gunakan subtle hover.
* [x] Pertahankan link detail.

### Mobile

* [x] Ubah row menjadi stacked layout.
* [x] Hindari card-heavy mobile layout.
* [x] Pastikan tidak horizontal overflow.

### Summary

* [x] Ganti tiga stat cards dengan compact summary.
* [x] Pertahankan count existing.

### Empty State

* [x] Hapus emoji besar.
* [x] Tambahkan next action.

---

# Profile

## File

```text
resources/views/profile.blade.php
```

## Target Structure

```text
Pengaturan akun

Profil
----------------

Keamanan
----------------

Hapus akun
----------------
```

## Task

* [x] Hapus gradient avatar.
* [x] Sederhanakan identity header.
* [x] Refactor profile form.
* [x] Refactor password form.
* [x] Pertahankan password strength jika berguna.
* [x] Refactor danger zone.
* [x] Refactor delete confirmation modal.
* [x] Pertahankan delete contract.

## Exit Criteria

History dan profile menggunakan primitives yang sama dengan homepage/auth.

---

# Phase 6 — Authentication

## Files

```text
resources/views/auth/login.blade.php
resources/views/auth/register.blade.php
resources/views/auth/forgot-password.blade.php
resources/views/auth/reset-password.blade.php
resources/views/auth/verify-email.blade.php
```

## Target

Simple centered form.

Max width:

```text
~400px
```

## Remove

* [x] glow orb,
* [x] glass card,
* [x] scale entrance animation,
* [x] decorative trust copy,
* [x] gradient branding.

## Implement

* [x] shared auth layout/pattern,
* [x] consistent form components,
* [x] clear validation,
* [x] password visibility control,
* [x] loading state,
* [x] responsive behavior.

## Preserve

* [x] route,
* [x] CSRF,
* [x] remember me,
* [x] password reset,
* [x] verification,
* [x] validation errors.

## Exit Criteria

Seluruh authentication page memiliki tampilan dan behavior konsisten.

---

# Phase 7 — Informational Pages

## Pages

```text
cara-kerja.blade.php
tentang.blade.php
kebijakan-privasi.blade.php
statistik.blade.php
```

---

# Cara Kerja

## Task

* [x] Hapus glow hero.
* [x] Hapus gradient text.
* [x] Hapus emoji besar.
* [x] Hapus nested glowing cards.
* [x] Ubah pipeline menjadi timeline/documentation flow.
* [x] Pertahankan metodologi penting.
* [x] Pertahankan detail BERT.
* [x] Jelaskan OCR/transkripsi secara sederhana.
* [x] Pisahkan detail teknis dari explanation umum.

---

# Tentang

## Task

* [x] Gunakan prose container.
* [x] Perbaiki typography hierarchy.
* [x] Hapus card dekoratif.
* [x] Fokus pada tujuan proyek dan keterbatasan sistem.

---

# Kebijakan Privasi

## Task

* [x] Gunakan prose layout.
* [x] Pertahankan seluruh informasi legal/privacy existing.
* [x] Perjelas heading hierarchy.
* [x] Pastikan readability mobile.

---

# Statistik

## Task

* [x] Audit apakah setiap metric membutuhkan card/chart.
* [x] Gunakan visualisasi hanya jika memberikan insight.
* [x] Pertahankan semantic status.
* [x] Pastikan chart responsive jika tersedia.
* [x] Hindari dashboard-template appearance.

## Exit Criteria

Semua halaman sekunder sudah mengikuti visual language baru.

---

# Phase 8 — Technical Cleanup

## Tujuan

Menghapus technical debt UI setelah semua page migration selesai.

---

# CSS Cleanup

## Task

* [x] Hapus `.glass-card`.
* [x] Hapus `.glass-card-solid`.
* [x] Hapus gradient utilities yang tidak digunakan.
* [x] Hapus glow classes.
* [x] Hapus old animation classes.
* [x] Hapus unused stat styles.
* [x] Hapus unused feature-card styles.
* [x] Hapus unused auth styles.
* [x] Hapus duplicated media queries.
* [x] Audit arbitrary custom CSS.

---

# Inline Style Cleanup

Search:

```text
style="
```

## Task

* [x] Migrasikan static style ke class/Tailwind.
* [x] Pertahankan inline style hanya untuk truly dynamic values.

Contoh acceptable:

```blade
style="width: {{ $progress }}%"
```

---

# JavaScript Cleanup

## Task

* [x] Hapus duplicate navbar scroll listener.
* [x] Hapus duplicate IntersectionObserver.
* [x] Hapus decorative animation logic.
* [x] Pindahkan checker behavior.
* [x] Pindahkan upload behavior.
* [x] Pindahkan result behavior.
* [x] Hindari `.style.*` untuk UI state jika dapat menggunakan class.
* [x] Pertahankan minimal global JS.

---

# Blade Cleanup

## Task

* [x] Extract repeated navbar/footer.
* [x] Extract repeated alert.
* [x] Extract repeated button jika bermanfaat.
* [x] Extract repeated form component jika bermanfaat.
* [x] Jangan over-componentize.
* [x] Hapus markup/comment lama yang tidak digunakan.

---

# Asset Cleanup

* [x] Audit old font.
* [x] Audit unused SVG.
* [x] Audit unused CSS.
* [x] Audit unused JS.
* [x] Audit unused images.

## Exit Criteria

Tidak ada legacy UI dependency yang masih diperlukan oleh public interface.

---

# 7. Responsive Requirements

Setiap phase harus dites pada minimal:

```text
320px
375px
768px
1024px
1440px
```

Periksa:

* [x] navigation,
* [x] checker,
* [x] tabs,
* [x] textarea,
* [x] upload zone,
* [x] result,
* [x] table,
* [x] auth form,
* [x] modal,
* [x] footer.

Tidak boleh ada horizontal overflow.

---

# 8. Accessibility Requirements

Target minimal:

```text
WCAG 2.1 AA
```

Setiap halaman harus memeriksa:

* [x] semantic heading order,
* [x] keyboard navigation,
* [x] visible focus,
* [x] label form,
* [x] ARIA hanya jika diperlukan,
* [x] async status via aria-live,
* [x] modal focus behavior,
* [x] sufficient contrast,
* [x] status tidak hanya berdasarkan warna,
* [x] alt text,
* [x] reduced motion.

---

# 9. Regression Requirements

Setiap fase yang menyentuh core interaction harus diuji terhadap fungsi existing.

## Checker

* [x] text,
* [x] image,
* [x] video,
* [x] video URL,
* [x] article URL,
* [x] CAPTCHA,
* [x] file preview,
* [x] drag-and-drop.

## Result

* [x] pending,
* [x] processing,
* [x] completed,
* [x] failed,
* [x] media,
* [x] PDF,
* [x] feedback.

## Account

* [x] login,
* [x] logout,
* [x] register,
* [x] verification,
* [x] reset password,
* [x] update profile,
* [x] update password,
* [x] delete account.

## History

* [x] list,
* [x] search,
* [x] filter,
* [x] sort,
* [x] pagination,
* [x] detail.

---

# 10. Implementation Order

Urutan pengerjaan wajib:

```text
Phase 0
Baseline

↓

Phase 1
Foundation

↓

Phase 2
Application Shell

↓

Phase 3
Homepage + Checker

↓

Phase 4
Processing + Result

↓

Phase 5
History + Profile

↓

Phase 6
Authentication

↓

Phase 7
Informational Pages

↓

Phase 8
Cleanup + Regression
```

Jangan memulai cleanup legacy class terlalu awal.

Legacy style baru boleh dihapus ketika tidak ada consumer tersisa.

---

# 11. Git Strategy

Disarankan menggunakan branch:

```text
refactor/ui-v2
```

Commit dibuat berdasarkan logical unit.

Contoh:

```text
refactor(ui): rebuild design tokens
refactor(layout): simplify navbar and footer
refactor(checker): redesign homepage input flow
refactor(result): restructure detection report
refactor(history): simplify history interface
refactor(auth): unify authentication pages
refactor(ui): remove legacy glass and glow styles
```

Hindari satu commit besar untuk seluruh redesign.

---

# 12. Change Safety Rules

Agent tidak boleh:

* mengubah backend hanya untuk mempermudah styling,
* mengganti route,
* mengganti field request,
* mengganti database schema,
* menghapus Livewire polling tanpa replacement yang valid,
* menghapus fallback behavior tanpa audit,
* mengubah result logic,
* mengubah model labels,
* mengubah CAPTCHA behavior,
* mengubah auth logic,

kecuali task secara eksplisit meminta perubahan functional.

---

# 13. Acceptance Criteria Global

Refactor dianggap selesai jika:

## Visual

* [x] tidak ada glassmorphism pada public UI,
* [x] tidak ada glow decoration,
* [x] tidak ada gradient visual utama,
* [x] tidak ada emoji sebagai icon utama,
* [x] tidak ada decorative card explosion,
* [x] tidak ada excessive pill,
* [x] interface menggunakan light theme konsisten.

## UX

* [x] checker menjadi fokus homepage,
* [x] hasil mudah dipahami pengguna awam,
* [x] confidence tidak mendominasi verdict,
* [x] technical metadata tidak mengganggu main flow,
* [x] navigation konsisten.

## Engineering

* [x] inline style statis berkurang signifikan,
* [x] JavaScript behavior tidak duplikatif,
* [x] components reusable tersedia bila memang diperlukan,
* [x] legacy CSS sudah dibersihkan,
* [x] project tetap build tanpa error.

## Responsive

* [x] tidak ada horizontal overflow,
* [x] core flow usable di mobile,
* [x] history mobile usable,
* [x] auth mobile usable.

## Accessibility

* [x] keyboard flow bekerja,
* [x] focus state visible,
* [x] contrast memadai,
* [x] async state accessible.

## Regression

* [x] seluruh critical flow existing tetap bekerja.

---

# 14. Definition of Done per Phase

Sebuah fase hanya dianggap selesai jika:

1. Seluruh task utama fase selesai.
2. Existing functionality masih berjalan.
3. Responsive pass dilakukan.
4. Accessibility dasar diperiksa.
5. Tidak memperkenalkan pola yang dilarang `DESIGN_SYSTEM.md`.
6. Tidak meninggalkan unused legacy implementation yang seharusnya sudah dapat dihapus pada fase tersebut.
7. Perubahan cukup terisolasi untuk dilanjutkan ke fase berikutnya.

---

# 15. Refactor Priority

Jika harus memilih antara:

```text
visual polish
```

dan:

```text
clarity + maintainability
```

prioritaskan:

```text
clarity + maintainability
```

Jika harus memilih antara:

```text
fancy interaction
```

dan:

```text
predictable interaction
```

prioritaskan:

```text
predictable interaction
```

Jika harus memilih antara:

```text
showing the technology
```

dan:

```text
helping the user understand the result
```

prioritaskan:

```text
helping the user understand the result
```

---

# 16. Final Target

Refactor UI Doksli dinyatakan berhasil ketika aplikasi tidak lagi terasa seperti:

> demo AI yang dibangun dari kumpulan gradient, glow, glass card, dan animation.

Tetapi terasa seperti:

> alat pemeriksaan informasi yang sederhana, tenang, transparan, dan dapat dipercaya.

Teknologi tetap menjadi fondasi produk.

Namun antarmuka harus memprioritaskan **pengguna dan informasi**, bukan teknologi itu sendiri.
