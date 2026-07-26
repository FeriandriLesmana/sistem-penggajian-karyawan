💰 Sistem Informasi Penggajian Karyawan

📖 Deskripsi

Aplikasi berbasis web untuk mengelola sistem administrasi dan penggajian karyawan secara efisien. Proyek ini dikembangkan menggunakan framework Laravel dan Filament PHP yang menyajikan antarmuka panel admin modern dan responsif. Aplikasi ini dirancang untuk mempermudah proses perhitungan gaji yang transparan dan terstruktur.

✨ Fitur Utama

Manajemen Karyawan: Pendataan identitas, jabatan, dan status karyawan.

Manajemen Kehadiran: Perekaman data absensi bulanan.

Komponen Gaji: Konfigurasi gaji pokok, tunjangan, dan potongan.

Perhitungan Gaji Otomatis: Kalkulasi total gaji bersih berdasarkan komponen yang ada.

Cetak Slip Gaji: Pembuatan dan pencetakan slip gaji dalam format PDF untuk setiap karyawan.

Dashboard Admin: Ringkasan statistik operasional dan laporan keuangan.

🛠️ Persyaratan Sistem

PHP >= 8.1

Composer

MySQL / MariaDB

Node.js & NPM (untuk build aset frontend)

🚀 Cara Instalasi (Pengembangan Lokal)

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal:

Clone repository ini:

git clone https://github.com/FeriandriLesmana/sistem-penggajian-karyawan.git


Masuk ke direktori proyek:

cd sistem-penggajian-karyawan


Install dependensi PHP:

composer install


Siapkan environment:

cp .env.example .env


Buka file .env dan sesuaikan kredensial database (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

Generate application key:

php artisan key:generate


Migrasi struktur database:

php artisan migrate


Jalankan server lokal:

php artisan serve


Aplikasi dapat diakses melalui browser pada http://localhost:8000.

🔐 Akses Login Default

Setelah aplikasi berjalan, Anda dapat masuk ke panel admin menggunakan kredensial berikut:

Email: admin@admin.com

Password: password

(Catatan: Pastikan Anda sudah menjalankan seeder jika akun ini dibuat melalui database seeder).

👨‍💻 Penulis

Feriandri Lesmana (221011700222)

Program Studi Sistem Informasi - Universitas Pamulang (UNPAM)