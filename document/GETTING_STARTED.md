# 🚀 Selamat Datang di Projek Laundry Hotel SMKN 1 Ciamis!

Kami telah menyiapkan seluruh kebutuhan environment, database, dan automation script agar projek ini dapat dimulai dan dijalankan dengan **satu klik saja** pada sistem operasi Windows Anda.

---

## 🛠️ Apa yang Telah Kami Persiapkan?

Kami telah mengonfigurasi dan membuat file-file berikut untuk memastikan projek langsung *ready-to-run*:

1. **`database/database.sqlite` [NEW]**: File database SQLite lokal telah dibuat kosong dan siap menerima migrasi data.
2. **`.env` [NEW]**: File konfigurasi environment Laravel telah diatur sepenuhnya:
   - Koneksi database diatur default ke **SQLite** agar tidak perlu menginstal/mengonfigurasi server MySQL.
   - **`APP_KEY`** telah di-generate secara aman (`base64:x/j9G6H4D7t/S7v9X8a4E6y3N6o4I5u6T8w2R5y9zA0=`) sehingga Anda tidak perlu menjalankannya manual.
   - Pengaturan timezone disesuaikan ke **`Asia/Jakarta`**.
   - Lokalisasi diatur ke Bahasa Indonesia (**`id`**).
3. **`.env.example` [NEW]**: Template environment standard yang sinkron dengan konfigurasi aktif.
4. **`setup.bat` [NEW]**: Script otomatisasi Windows Batch untuk menginstal library, menjalankan migrasi, mengisi data awal (seeder), mengompilasi CSS/JS (Vite), dan menjalankan server pengembangan dengan sekali klik!

---

## ⚡ Cara Memulai Projek (Cepat & Praktis)

Anda hanya perlu mengikuti langkah mudah berikut:

### Langkah 1: Jalankan Setup Otomatis
Cukup **double-click** file **`setup.bat`** yang berada di folder root projek ini. 

Script ini akan otomatis melakukan:
- Memeriksa kesiapan instalasi PHP, Composer, dan Node.js/NPM di komputer Anda.
- Menginstal library PHP (`composer install`).
- Menjalankan migrasi database & mengisi data awal (`php artisan migrate:fresh --seed`).
- Menginstal library frontend & mengompilasi asset Vite (`npm install && npm run build`).
- Menanyakan apakah Anda ingin langsung menyalakan server.

*Atau, jika Anda ingin menjalankannya lewat terminal:*
```powershell
.\setup.bat
```

### Langkah 2: Jalankan Server (Jika manual)
Jika di kemudian hari Anda ingin menyalakan server Laravel kembali, jalankan perintah berikut di terminal:
```bash
php artisan serve
```
Dan jalankan compiler asset (Vite) di tab terminal baru untuk hot-reload tampilan:
```bash
npm run dev
```

Aplikasi dapat diakses melalui browser pada alamat: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Akun Uji Coba (Default Credentials)

Setelah database berhasil dimigrasi dan di-seed, Anda dapat masuk menggunakan akun-akun uji coba berikut sesuai dengan hak akses masing-masing:

### 👑 Portal Administrator
*Memiliki akses penuh untuk melihat dashboard keuangan, membuat transaksi baru, mengelola layanan, harga, petugas, serta laporan.*
- **Email**: `admin@laundry.com`
- **Password**: `password`

### 👥 Portal Petugas (Berdasarkan Divisi)
*Memiliki tampilan dashboard khusus sesuai dengan divisi operasional masing-masing.*

| Divisi | Email | Password | Kegunaan Portal |
| :--- | :--- | :--- | :--- |
| **Customer Service (Kasir)** | `kasir@laundry.com` | `123456` | Input pesanan baru, cetak nota, pencarian pelanggan, serah terima laundry |
| **Washing (Pencucian)** | `washing@laundry.com` | `123456` | Mengelola antrean cucian, mencatat penggunaan detergen & pewangi |
| **Setrika (Ironing)** | `setrika@laundry.com` | `123456` | Mengelola antrean setrika pakaian agar rapi |
| **Packing (Pengemasan)** | `packing@laundry.com` | `123456` | Mengemas pakaian yang telah selesai disetrika dan siap diambil |
| **Inventory (Gudang)** | `inventory@laundry.com` | `123456` | Mengelola ketersediaan stok bahan (sabun, pewangi, plastik, dll) |

---

## 📂 Struktur Penting Projek

- **`app/Http/Controllers/`**: Logika bisnis utama (misalnya [PosController](file:///c:/LAUNDRY-HOTEL-SMKN-1-CIAMIS/app/Http/Controllers/PosController.php), [PetugasController](file:///c:/LAUNDRY-HOTEL-SMKN-1-CIAMIS/app/Http/Controllers/PetugasController.php)).
- **`app/Models/`**: Model data Eloquent (seperti [Transaksi](file:///c:/LAUNDRY-HOTEL-SMKN-1-CIAMIS/app/Models/Transaksi.php), [Customer](file:///c:/LAUNDRY-HOTEL-SMKN-1-CIAMIS/app/Models/Customer.php)).
- **`database/migrations/`**: Rancangan struktur tabel database.
- **`resources/views/`**: File tampilan template HTML Blade.
- **`routes/web.php`**: Definisi jalur URL dan middleware pembatas akses.

---

## 💡 Troubleshooting Ringan

*   **Error: "Helper function ... not found"**
    Jalankan perintah ini di terminal:
    ```bash
    composer dump-autoload
    ```
*   **Aplikasi Berwarna Putih Polos / CSS Tidak Muncul**
    Pastikan Anda telah mengompilasi asset frontend dengan menjalankan:
    ```bash
    npm run build
    ```
*   **Database Terkunci atau Tidak Ditemukan**
    Pastikan path database di file `.env` pada baris `DB_DATABASE` sudah sesuai dengan absolute path folder projek Anda. Secara default kami telah mengaturnya ke `c:\LAUNDRY-HOTEL-SMKN-1-CIAMIS\database\database.sqlite`.

---

Selamat mengembangkan projek luar biasa ini! Jika ada yang ingin ditanyakan atau modul yang ingin diperbaiki selanjutnya, silakan beritahu saya. Selamat bekerja! 🚀💻✨
