# Trisassor Barber House

Trisassor Barber House adalah aplikasi web reservasi/booking barbershop yang dibangun menggunakan Laravel 12. Aplikasi ini dirancang agar customer bisa dengan mudah memesan jadwal cukur dengan kapster (barber) pilihan mereka.

## Fitur Utama
- **Sistem Booking**: Fitur booking yang interaktif menggunakan Alpine.js untuk mengatur alur memilih layanan, meja/kapster, hingga jam operasional.
- **Login Cepat Google**: Terintegrasi penuh dengan Laravel Socialite, jadi user bisa langsung login pakai akun Google (dilengkapi validasi agar pelanggan otomatis diminta melengkapi nomor HP/WhatsApp).
- **Desain Modern (Glassmorphism)**: Tampilan UI dibuat rapi dan ringan menggunakan Tailwind CSS v4.
- **Akses Admin & Pelanggan**: Pembagian halaman (*routing* dan dasbor) khusus untuk akses pengelola toko maupun riwayat pemesanan milik customer.
- **Support Docker**: Sudah termasuk `Dockerfile` dan konfigurasi `compose` standar siap pakai, memudahkan ketika aplikasi akan di-*deploy* rilis ke server.

## Tech Stack
- **Backend Framework**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL / MariaDB
- **Frontend**: DOM Alpine.js, styling Tailwind CSS v4, kompilator Vite
- **Deployment Layer**: Nginx/Apache Docker, Podman
- **Otentikasi**: Laravel Breeze

---

## Panduan Instalasi (Local Development)

Untuk teman-teman atau anggota tim yang mau *clone* dan ikut mengembangkan (*development*) proyek ini dari awal, syaratnya cukup instal: **PHP (Min 8.2)**, **Composer**, **Node.js**, dan bawaan **MySQL (seperti XAMPP)** di komputernya.

### 1. Clone Project & Install Library
Buka terminal dan ketik perintah berikut:
```bash
git clone https://github.com/mwildans/trisassor_barber.git
cd trisassor_barber

# Install seluruh kebutuhan library backend (Laravel)
composer install

# Install kebutuhan engine javascript & CSS
npm install
```

### 2. Atur Konfigurasi Lingkungan (.env)
Buat salinan *(copy)* dari file bernamakan `.env.example`, lalu beri nama `.env` saja.
Buka file `.env` tersebut dan pastikan rincian server database XAMPP kalian telah sesuai (ganti namanya sama seperti isi di phpmyadmin), contohnya seperti ini:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trisassor_barber
DB_USERNAME=root   # Default XAMPP
DB_PASSWORD=       # Kosongkan jika XAMPP
```
*(Ingat ya, pergi ke **phpMyAdmin** lalu buat dulu database baru yang kosong memakai nama `trisassor_barber` tersebut!)*

### 3. Generate Kunci Autentikasi dan Tabel Database
Jika database kosong sudah siap, eksekusi pembuatan struktur tabel dan kunci app di terminal:
```bash
php artisan key:generate
php artisan migrate

# Step ini wajib dijalankan supaya folder direktori foto/gambar kapster bisa dirender di web
php artisan storage:link
```

### 4. Mulai Ber-koding! 🏃‍♂️
Nyalakan **dua (2) buah terminal**, dan pastikan keduanya berjalan beriringan selama pengerjaan:

Terminal 1 (Untuk menjalankan program PHP):
```bash
php artisan serve
```

Terminal 2 (Untuk men-compile perombakan teks dan desain CSS secara riil):
```bash
npm run dev
```

Buka tab baru di browser kalian dan klik link ini: 👉 **[http://localhost:8000](http://localhost:8000)**.

---

## Mengetes Server ala Production (Docker / Podman)

**Catatan:** Trik tes *Container* simulasi ini cuma dilakukan kalau *website* sudah jadi mentah dan siap lempar ke peladen publik (seperti Railway). Untuk koding sehari-harinya, tetap andalkan langkah `php artisan serve` di atas!

Jika kalian ingin tahu *environment* aslinya sebelum dilepas:
```bash
# Untuk pengguna Podman Desktop
podman compose up -d --build

# Bagi pengguna Docker standar:
# docker-compose up -d --build
```
Web bisa diintip di port independen: 👉 **[http://localhost:8080](http://localhost:8080)**.
(Untuk membongkarnya, cukup lempar perintah `podman compose down`).
