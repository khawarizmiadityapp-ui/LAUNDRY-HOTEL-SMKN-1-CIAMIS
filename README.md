# 🧺 Bening Laundry — Sistem Manajemen Laundry Hotel SMKN 1 Ciamis

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)](https://alpinejs.dev)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)

**Bening Laundry** adalah sistem informasi manajemen operasional dan keuangan laundry hotel terintegrasi yang dirancang untuk mendukung praktik kerja industri (Prakerin) dan operasional teaching factory di **SMKN 1 Ciamis**.

---

## 📌 Fitur Utama

- **🛡️ Multi-Role & Hak Akses Berjenjang**:
  - **Super Admin**: Akses mutlak seluruh sistem, manajemen akun pengguna, ganti password akun lain, pengaturan sistem, stasiun operasional, dan laporan.
  - **Admin**: Akses manajemen transaksi, POS, layanan, jadwal piket, keuangan & BKU, pengeluaran & pengajuan belanja, serta stok inventaris.
  - **Petugas / Staff**: Dashboard stasiun kerja sesuai divisi (Washing, Setrika/Ironing, Packing, Customer Service/Kasir, Inventory).
  - **Customer**: Lacak status pengerjaan laundry secara mandiri (*Tracking Status*).
- **🔐 Keamanan 2FA (Two-Factor Authentication)**:
  - Proteksi login dan penggantian kata sandi menggunakan **Google Authenticator** (OTP 6-digit) untuk level Super Admin & Admin.
- **⚡ Point of Sale (POS) Multi-Service**:
  - Input pesanan fleksibel multi-layanan (kiloan, satuan, dry clean, express).
  - Cetak nota digital / thermal receipt dan kalkulasi kembalian/diskon otomatis.
- **🔄 Alur Pengerjaan Cucian Real-time**:
  - Tracking stasiun: *Diterima ➔ Washing ➔ Drying ➔ Ironing ➔ Packing ➔ Siap Diambil ➔ Selesai*.
- **📊 Manajemen Keuangan & Laporan BKU**:
  - Buku Kas Umum (BKU), Laporan BHP (Bahan Habis Pakai), Pengeluaran, dan Pengajuan Belanja dengan ekspor PDF & Excel.
- **📦 Inventory & Penyesuaian Stok**:
  - Manajemen bahan & sabun laundry, permohonan penyesuaian stok oleh staff, serta approval instan oleh Super Admin/Admin.
- **📱 Integrasi Notifikasi WhatsApp**:
  - Notifikasi nomor admin & customer care via WhatsApp.

---

## 💻 Prasyarat Sistem

Sebelum melakukan instalasi, pastikan perangkat Anda telah terpasang:
- **PHP** >= 8.2 (Ekstensi wajib: `pdo_mysql`, `mbstring`, `openssl`, `bcmath`, `curl`, `gd`)
- **Composer** >= 2.x
- **MySQL / MariaDB** (Dapat menggunakan Laragon / XAMPP)
- **Git** (Opsional untuk clone repository)
- **Aplikasi Google Authenticator** di Smartphone / Browser Extension (untuk verifikasi 2FA)

---

## 🚀 Panduan Instalasi & Setup

Ikuti langkah-langkah berikut secara berurutan untuk menjalankan project dari awal:

### 1. Masuk ke Direktori Project
Jika menggunakan Laragon, pastikan folder project berada di `C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS`. Buka terminal (PowerShell / Command Prompt / Git Bash) di folder tersebut:
```bash
cd c:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS
```

### 2. Install Dependensi PHP via Composer
Jalankan perintah berikut untuk mengunduh semua pustaka Laravel yang diperlukan:
```bash
composer install
```

### 3. Konfigurasi File Environment (`.env`)
Salin file `.env.example` menjadi `.env` jika belum ada:
```bash
# Windows PowerShell
copy .env.example .env

# Atau Linux/Git Bash
cp .env.example .env
```

Buka file `.env` lalu sesuaikan konfigurasi database Anda:
```env
APP_NAME="Bening Laundry"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE="Asia/Jakarta"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laundry_hotel_smkn1_ciamis
DB_USERNAME=root
DB_PASSWORD=
```

> **Catatan**: Buat database baru di MySQL dengan nama `laundry_hotel_smkn1_ciamis` via phpMyAdmin / HeidiSQL jika belum ada.

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Jalankan Migrasi Database & Seeder
Perintah ini akan membuat semua tabel database beserta data awal (akun Super Admin, Admin, Petugas, Layanan, Harga, dan Stok):
```bash
php artisan migrate:fresh --seed
```

### 6. Buat Symlink Storage Publik
Pastikan folder storage terhubung untuk upload gambar struk/bon dan logo:
```bash
php artisan storage:link
```

### 7. Jalankan Server Lokal
Jalankan development server Laravel:
```bash
php artisan serve
```
Aplikasi sekarang dapat diakses melalui browser di: **`http://127.0.0.1:8000`** atau **`http://localhost:8000`** *(atau `http://laundry-hotel-smkn-1-ciamis.test` jika menggunakan Laragon virtual host)*.

---

## 🔑 Akun Default Sistem

Setelah database di-seed, akun-akun berikut siap langsung digunakan untuk login:

| Peran (Role) | Divisi | Email | Kata Sandi | Keterangan Akses |
| :--- | :--- | :--- | :--- | :--- |
| **⭐ Super Admin** | *-* | `superadmin@laundry.com` | `password` | **Full Access** (Manajemen Pengguna, Ganti Sandi, 2FA, Pengaturan, Stasiun Operasional, Keuangan) |
| **🛡️ Admin** | *-* | `admin@laundry.com` | `password` | **Admin Portal** (POS, Transaksi, Jadwal, Layanan, Keuangan, 2FA) |
| **👤 Petugas CS / Kasir** | `customer_service` | `kasir@laundry.com` | `123456` | Dashboard CS, Point of Sale, Registrasi Pelanggan |
| **👤 Petugas Washing** | `washing` | `washing@laundry.com` | `123456` | Dashboard Stasiun Cuci (Washing Station) |
| **👤 Petugas Setrika** | `setrika` | `setrika@laundry.com` | `123456` | Dashboard Stasiun Setrika / Ironing |
| **👤 Petugas Packing** | `packing` | `packing@laundry.com` | `123456` | Dashboard Stasiun Pengemasan & Cek Siap Ambil |
| **👤 Petugas Inventory** | `inventory` | `inventory@laundry.com` | `123456` | Dashboard Gudang & Pengajuan Stok |
| **👤 Petugas All-In-One** | `all_roles` | `allroles@laundry.com` | `123456` | Akses ke seluruh stasiun operasional petugas |

---

## 📱 Panduan Verifikasi 2FA (Google Authenticator)

Akun **Super Admin** dan **Admin** dilindungi oleh verifikasi 2FA saat login:
1. Masukkan **Email** dan **Password**.
2. Anda akan diarahkan ke halaman **Verifikasi 2FA**:
   - Jika login pertama kali, pindai (**Scan**) QR Code yang tampil menggunakan aplikasi **Google Authenticator** di HP Anda.
   - Atau salin **Kunci Manual (Setup Key)** ke dalam aplikasi Authenticator.
3. Masukkan 6 digit kode OTP yang dihasilkan oleh aplikasi Authenticator.
4. Klik **Verifikasi & Masuk**.

---

## 🧪 Menjalankan Pengujian Otomatis (Testing)

Project ini dilengkapi dengan test suite lengkap (Unit & Feature Tests):
```bash
# Menjalankan seluruh pengujian
php ./vendor/bin/phpunit

# Atau menggunakan perintah artisan
php artisan test
```

---

## 📂 Struktur Direktori Utama

```text
├── app/
│   ├── Exports/            # Export Excel & PDF transaksi/laporan
│   ├── Http/
│   │   ├── Controllers/    # Controller modul Admin, Petugas, POS, Keuangan, Auth
│   │   └── Middleware/     # Middleware otorisasi role & filter
│   ├── Models/             # Eloquent Model (User, Transaksi, Layanan, dll.)
│   └── Services/           # Layanan Menu, Google2FA, dan logika bisnis
├── config/
│   └── sidebar.php         # Konfigurasi menu sidebar & label divisi
├── database/
│   ├── migrations/         # Skema database terstruktur
│   └── seeders/            # Data inisial default sistem
├── resources/
│   └── views/
│       ├── admin/          # Tampilan modul Administrator & Pengaturan
│       ├── auth/           # Tampilan Login & Verifikasi 2FA
│       ├── layouts/        # Template layout master (Admin & Petugas)
│       ├── petugas_piket/  # Tampilan modul operasional stasiun kerja
│       └── pos/            # Tampilan Kasir / Point of Sale
└── routes/
    └── web.php             # Rute web aplikasi berorientasi role
```

---

## 🤝 Kontribusi & Dukungan

Dikembangkan untuk **SMKN 1 Ciamis** — Jurusan Perhotelan & Manajemen Bisnis Laundry.  
Jika menemukan kendala atau membutuhkan pengembangan fitur lebih lanjut, hubungi tim pengembang atau buat *issue* pada repository.

✨ *Semoga bermanfaat untuk menunjang kegiatan operasional dan pembelajaran siswa!*
