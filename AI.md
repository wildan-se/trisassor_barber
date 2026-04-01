# TRISASSOR BARBER - AI DEVELOPMENT GUIDELINES

Dokumen ini berfungsi sebagai "Buku Panduan Utama" (Project Memory & System Prompt) untuk setiap agen Artificial Intelligence (AI) yang bekerja pada repositori ini. AI wajib membaca dan mematuhi instruksi di bawah ini sebelum memodifikasi kode, guna mencegah halusinasi, menjaga standar *Software Engineering* profesional, dan memastikan integritas arsitektur.

---

## 1. PROJECT CONTEXT & ARCHITECTURE
- **Sistem:** Platform reservasi (Booking) online untuk Barbershop Premium.
- **Fitur Inti:** Pemesanan jadwal berlapis anti *Double-Booking*, Autentikasi Hybrid (Manual & Google OAuth), Profil Barbershop, dan Manajemen Admin.
- **Arsitektur Utama:** Monolith MVC standar Laravel dengan Server-Side Rendering (Blade), diperkaya mikro-interaksi via Alpine.js.

---

## 2. TECHNOLOGY STACK
Setiap baris kode yang direkomendasikan AI harus spesifik dan kompatibel dengan tumpukan teknologi berikut:
- **Backend framework:** Laravel 12 (Pastikan syntax terbaru Laravel 12, jangan berhalusinasi dengan sintaks usang Laravel 9/10).
- **PHP Version:** PHP 8.2+
- **Database:** MySQL relational DB (Strict mode).
- **Frontend / UI:** Tailwind CSS v4, Blade Templating, Alpine.js (Untuk state management ringan seperti *multi-step form* & *modal*). **Dilarang** menggunakan jQuery.
- **Authentication:** Custom Laravel Breeze & Laravel Socialite (Google).

---

## 3. STRICT AI RULES (ANTI-HALLUCINATION & ENGINEERING STANDARDS)

Setiap implementasi kode baru **WAJIB** mengikuti Golden Rules berikut:

### RULE 1: Language Separation (Pemisahan Bahasa)
- **KODE (Back-end & Front-end):** Nama variabel, *method*, *class*, *table*, struktur *database*, dan logika murni HARUS berbahasa **Inggris** (cth: `barber_id`, `hasConflict()`, `BookingController`).
- **USER INTERFACE (UI & UX):** Teks yang terlihat oleh *user* (Label, Notifikasi, Validasi *Error*, Flash Messages, Tombol) HARUS menggunakan bahasa **Indonesia** yang baku, elegan, dan sopan (cth: "Slot jadwal ini baru saja diambil orang lain.").

### RULE 2: Data Integrity & Concurrency (Anti Race-Condition)
- Transaksi kritis (seperti pembuatan *Booking*, pemotongan saldo, atau penambahan *Point Membership*) **HARUS** diamankan di dalam `DB::transaction()`.
- Wajib menggunakan teknik pendukung konkurensi seperti **Pessimistic Locking** (`lockForUpdate()`) setiap kali ada risiko dua request *user* menargetkan sumber daya (*barber/slot*) yang sama di milidetik yang sama. AI dilarang abai pada potensi *Race Condition*.

### RULE 3: Security & Authorization (Keamanan Skala Produksi)
- Pastikan semua _Form_ mutasi data (POST, PUT, DELETE) memiliki proteksi `@csrf`.
- Filter otorisasi: Blokir akses halaman Admin dari *Customer* menggunakan *Middleware* atau *Gate/Route Guard*.
- Jangan pernah mengekspos *stack trace* sistem ke UI untuk layar pelanggan. Gunakan blok `try...catch`, rekam di `Log::error($e->getMessage())`, lalu lemparkan *error* yang manusiawi melalui fungsi `withErrors()`.
- Lindungi tabel *database* dengan properti `$fillable` di model *Eloquent* untuk mencegah serangan *Mass-Assignment*.

### RULE 4: Clean Code & DRY Principle
- **Keep Controllers Thin:** Ekstrak logika *query* yang rumit ke dalam *Scope* Model (cth: `scopeActive($query)` atau `Booking::hasConflict()`) atau kelas *Service*. *Controller* hanya bertugas mengatur pelacakan aliran *Request* dan *Response*.
- Minimalisasi HTML *hardcoding*. Gunakan Blade Components (`<x-button>`, `<x-input>`) untuk elemen repetitif guna memudahkan *scaling* tema UI di masa depan.

### RULE 5: Zero Assumption
- Jika AI diminta untuk mengubah kerangka infrastruktur (*Library* baru, skema relasional tabel DB), **AI WAJIB bertanya dan mengajukan "Implementation Plan"** (*Draft* Perencanaan) terlebih dahulu untuk mendapat konfirmasi *developer*/klien sebelum memulai mengetik baris ke-*file* lokal. Dilarang mengubah DB otomatis secara buta.

---

*Disusun khusus untuk pedoman asisten intelijen buatan The Trisassor Project.*
