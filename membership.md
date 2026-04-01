# Goal Description

Membangun fitur **Membership & Loyalty Program** terintegrasi untuk pelanggan Trisassor Barber. Fitur ini dirancang untuk meningkatkan retensi pelanggan (membuat mereka datang kembali) dengan memberikan _reward_ (poin) atau diskon berdasarkan kesetiaan mereka.

## Proposed Changes

Terdapat **2 Jenis Konsep Membership** yang sangat populer dan terbukti ampuh di industri Barbershop modern. Anda dapat memilih salah satu atau menggabungkan keduanya:

### Opsi 1: Sistem "Points & Tiers" (Sistem Pangkat) - 🏆 Paling Direkomendasikan

Pelanggan mendapatkan poin dari setiap rupiah yang dihabiskan untuk mencukur, dan poin tersebut menentukan "Pangkat" diskon mereka selamanya.

- **Skema Dasar**: Setiap Rp 10.000 = 1 Poin.
- **Bronze (0 - 100 Poin)**: Harga normal.
- **Silver (101 - 300 Poin)**: Otomatis Diskon 5% setiap kali _Booking_.
- **Gold (301 - 500 Poin)**: Otomatis Diskon 10%.
- **VIP (> 500 Poin)**: Otomatis Diskon 15% + Potong Rambut Bebas Antre.

### Opsi 2: Sistem "Digital Stamp Card" (Potong 5x Gratis 1x) - 🎟️ Sangat Cocok untuk Pemula

Sistem klasik yang paling disukai. Setiap kali pelanggan selesai cukur (Status _Completed_), mereka mendapat 1 "_Stamp_" digital di _Dashboard_ _website_ mereka.

- **Skema Dasar**: Mengumpulkan 5 _Stamp_.
- **Reward**: Jika sudah 5 _Stamp_, _booking_ ke-6 mereka akan memotong harga menjadi 0 (Gratis Penuh) atau mendapat diskon 50%, lalu jumlah stamp di-reset kembali ke 0.

---

### Rencana Implementasi Teknis (Jika memilih Opsi 1)

#### [NEW] Database Migrations

- Menambahkan kolom `points` (integer, default 0) dan `membership_tier` (string, default 'bronze') pada tabel `users`.

#### [MODIFY] app/Models/Booking.php & app/Http/Controllers/Admin/BookingController.php

- Menambahkan logika _Event_: Ketika Admin mengubah status _Booking_ pelanggan dari `confirmed` menjadi `completed` (selesai cukur), Sistem **otomatis** menghitung total harga transaksi dan menambahkan _Points_ ke profil si pelanggan. Sistem juga otomatis menaikkan Pangkat pelanggan jika syarat poin tercapai.

#### [MODIFY] app/Http/Controllers/BookingController.php (Customer Side)

- Menerapkan injeksi Diskon otomatis sesuai `membership_tier` saat perhitungan `total_price` di _Checkout_ / _Booking_.

#### [MODIFY] resources/views/booking/index.blade.php (Customer Dashboard)

- Membuat **Tampilan Kartu Member Digital (Virtual Member Card)** yang mewah. Kartu ini akan menampilkan Nama, Pangkat (Silver/Gold/VIP), Jumlah Poin saat ini, dan fitur _Progress Bar_ (misal: "Yuk, kumpulkan 30 poin lagi untuk naik ke Gold!").

## Open Questions

> [!IMPORTANT]
> **Keputusan Bisnis:** Opsi mana yang paling sesuai dengan target pasar Trisassor saat ini?
> Apakah Anda lebih suka **Opsi 1 (Pangkat / Tier Diskon)**, **Opsi 2 (Kartu Stempel Beli 5 Gratis 1)**, atau Anda memiliki konsep hitungan Membership sendiri?

Mohon balas dengan persetujuan atau ide tambahan Anda, dan saya akan langsung mengeksekusi sistem yang Anda pilih!
