# 💈 Trisassor Barber House

![Project Banner](https://via.placeholder.com/1200x400/1e293b/facc15?text=Trisassor+Barber+House)

**Trisassor Barber House** adalah platform reservasi cukur rambut (barbershop) modern yang dirancang untuk memberikan pengalaman pemesanan premium dan antarmuka *glassmorphism* yang elegan. Dibangun dengan *stack* teknologi terkini untuk memastikan kecepatan, keamanan, dan *User Experience* (UX) terbaik.

---

## 🚀 Fitur Utama
*   **Sistem Reservasi (Booking) Real-Time**: Alur *booking* dengan Alpine.js (reaktif tanpa pingsan halaman) yang ditenagai oleh UI modern, memilih kapster (Barber), jenis layanan (Service), tanggal, waktu, hingga mendapatkan *Queue Number* (Nomor Antrean) secara otomatis.
*   **Google OAuth Authentication**: Login super cepat dengan sistem *socialite* yang memaksa pengguna melengkapi nomor ponsel sebelum diizinkan memesan (Profile Completion Flow).
*   **Antarmuka Glassmorphism (Premium Design)**: Efek `backdrop-blur-lg` dengan animasi navigasi responsif dan *smooth scrolling* di Hero Section.
*   **Role Management (Breeze)**: Pemisahan tegas halaman dasbor admin & manajer (*backend*) dengan halaman pemesanan bagi *customer*.
*   **Production Simulation Ready**: Telah dikonfigurasi penuh dengan `Dockerfile` (Multi-stage Build & Apache-PHP 8.2) dan `.env` yang terisolasi siap-*deploy* khusus Railway / Vercel.

---

## 🛠 Instalasi untuk Development Lokal
Jika Anda meng-*clone* repositori ini, pastikan Anda telah memasang **PHP 8.2+**, **Composer**, **Node.js (v20+)**, dan **MySQL/MariaDB (via XAMPP)** di dalam komputer Anda.

Jalankan perintah berikut di Terminal secara berurutan:

```bash
# 1. Unduh repositori ini
git clone https://github.com/mwildans/trisassor_barber.git
cd trisassor_barber

# 2. Pasang semua pustaka backend PHP
composer install

# 3. Pasang semua pustaka frontend (Tailwind v4, Alpine, Vite)
npm install

# 4. Salin konfigurasi environment default
cp .env.example .env

# 5. Bangkitkan Kunci Keamanan internal (Application Key) Laravel Anda
php artisan key:generate
```

### ⚙️ Penyiapan Database
1. Buka *database management tool* (seperti phpMyAdmin atau HeidiSQL) untuk MySQL Anda.
2. Buat satu *database* kosong dengan nama `trisassor_barber`.
3. Buka konfigurasi `.env` Anda, pastikan data *database* (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) terhubung ke komputer Anda.

Setelah nama database disetel, jalankan perintah struktur migrasi data dan gambar:
```bash
# 1. Bangun tabel-tabel terhubung (Users, Bookings, Barbers, Services, Schedules)
php artisan migrate

# 2. Buka lorong direktori publik agar semua logo foto dan gambar kapster (Storage) bisa terbaca web
php artisan storage:link
```

### 🏃‍♂️ Menjalankan Proyek
Buka **Dua (2) Jendela Terminal**, dan jalankan ini di masing-masing:
```bash
# Terminal 1 - Menyalakan Server Backend (PHP)
php artisan serve
```
```bash
# Terminal 2 - Menyalakan Server Frontend Hot-Reloading (Vite)
npm run dev
```
Buka browser Anda dan kunjungi 👉 **[http://localhost:8000](http://localhost:8000)**. Selamat Datang!

---

## 🐋 Simulasi Internet / Podman Server Test (Level Production)
Jika Anda sudah tak ingin mengusik desain koding, dan ingin mengetes 100% simulasi utuh seperti apa hasilnya saat diunggah server aslinya (*Railway Cloud*):

```bash
# Bangun replika gambar Docker dan jalankan layanannya
podman compose up -d --build

# Untuk Docker tulen: docker-compose up -d --build
```
Kunjungi 👉 **[http://localhost:8080](http://localhost:8080)**. Semua *cache*, file internal web, dan *Apache service* akan tertulis mentah *(static-compiled)* seperti versi akhir produk di internet.

---

## 💻 Stack & Library yang Digunakan
*   **Core**: Laravel 12.x Core
*   **Database**: MySQL/MariaDB
*   **Frontend**: Alpine.js v3 | Tailwind CSS v4.0 via Vite
*   **Auth**: Laravel Breeze | Laravel Socialite (Google OAuth)
*   **Container**: Dockerfile & `docker-compose` Native Support

*(Dokumen ini akan terus disempurnakan seiring berjalannya fase pengujian produk).*
