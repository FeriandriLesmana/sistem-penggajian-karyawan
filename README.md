# 💰 Sistem Informasi Penggajian Karyawan

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Filament](https://img.shields.io/badge/Filament-F0A500?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)

## 📖 Deskripsi
Aplikasi berbasis web untuk mengelola sistem administrasi dan penggajian karyawan secara efisien. Proyek ini dikembangkan menggunakan framework **Laravel** dan **Filament PHP** yang menyajikan antarmuka panel admin modern dan responsif. Aplikasi ini dirancang untuk mempermudah proses perhitungan gaji yang transparan dan terstruktur.

## ✨ Fitur Utama
- **Manajemen Karyawan:** Pendataan identitas, jabatan, dan status karyawan.
- **Manajemen Kehadiran:** Perekaman data absensi bulanan.
- **Komponen Gaji:** Konfigurasi gaji pokok, tunjangan, dan potongan.
- **Perhitungan Gaji Otomatis:** Kalkulasi total gaji bersih berdasarkan komponen yang ada.
- **Cetak Slip Gaji:** Pembuatan dan pencetakan slip gaji dalam format PDF untuk setiap karyawan.
- **Dashboard Admin:** Ringkasan statistik operasional dan laporan keuangan.

## 🛠️ Persyaratan Sistem
- PHP >= 8.1
- Composer
- MySQL / MariaDB
- Node.js & NPM (untuk build aset frontend)

## 🚀 Cara Instalasi (Pengembangan Lokal)

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal:

1. **Clone repository ini:**
        git clone [https://github.com/FeriandriLesmana/sistem-penggajian-karyawan.git](https://github.com/FeriandriLesmana/sistem-penggajian-karyawan.git)

2. **Masuk ke direktori proyek:**
        cd sistem-penggajian-karyawan

3. **Install dependensi PHP:**
        composer install

4. **Siapkan environment:**
        cp .env.example .env

5. **Generate application key:**
        php artisan key:generate

6. **Migrasi struktur database:**
        php artisan migrate

7. **Jalankan server lokal:**
        php artisan serve

## 🔐 Akses Login Default

Setelah aplikasi berjalan, Anda dapat masuk ke panel admin menggunakan kredensial berikut:

### Akun Admin
- **Email:** admin@dhiarfa.com
- **Password:** admin

### Akun Owner
- **Email:** owner@dhiarfa.com
- **Password:** owner

### Akun Karyawan
- **Email:** slamet@dhiarfa.com
- **Password:** slamet

*(Catatan: Pastikan Anda sudah menjalankan seeder jika akun ini dibuat melalui database seeder).*

## 👨‍💻 Penulis
**Feriandri Lesmana** (221011700222)  
Program Studi Sistem Informasi - Universitas Pamulang (UNPAM)