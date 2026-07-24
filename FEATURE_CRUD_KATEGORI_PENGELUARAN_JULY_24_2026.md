# Feature: CRUD Kategori Pengeluaran

**Date:** July 24, 2026  
**Status:** ✅ COMPLETED  
**Type:** NEW FEATURE

---

## 📋 Overview

Menambahkan fitur CRUD (Create, Read, Update, Delete) untuk kategori pengeluaran yang memungkinkan admin untuk mengelola kategori pengeluaran secara dinamis melalui UI, menggantikan hardcoded array yang sebelumnya digunakan.

---

## ✨ Features

### 1. **Manage Kategori Pengeluaran**
- ✅ Tambah kategori baru dengan nama dan deskripsi
- ✅ Edit kategori yang sudah ada
- ✅ Aktifkan/nonaktifkan kategori
- ✅ Hapus kategori (jika tidak digunakan)
- ✅ Search kategori
- ✅ Lihat jumlah pengeluaran per kategori

### 2. **Integration dengan Pengeluaran**
- ✅ Dropdown kategori di form pengeluaran mengambil dari database
- ✅ Hanya menampilkan kategori yang aktif di dropdown
- ✅ Link "Kelola Kategori" di form pengeluaran
- ✅ Filter pengeluaran berdasarkan kategori ID

### 3. **Data Protection**
- ✅ Kategori yang masih digunakan tidak bisa dihapus
- ✅ Notifikasi jumlah pengeluaran saat edit kategori
- ✅ Backward compatibility dengan field `kategori` string

---

## 📁 Files Created

### Models
1. **`app/Models/KategoriPengeluaran.php`**
   - Model untuk kategori pengeluaran
   - Relasi ke Pengeluaran
   - Scope untuk kategori aktif
   - Method untuk cek penggunaan

### Controllers
2. **`app/Http/Controllers/KategoriPengeluaranController.php`**
   - CRUD operations
   - Toggle status aktif/nonaktif
   - Search functionality
   - Stats untuk dashboard

### Migrations
3. **`database/migrations/2026_07_24_132540_create_kategori_pengeluaran_table.php`**
   - Tabel kategori_pengeluaran
   - Seeding 3 kategori default

4. **`database/migrations/2026_07_24_133859_add_kategori_id_to_pengeluarans_table.php`**
   - Tambah kolom kategori_id ke tabel pengeluarans
   - Migrasi data dari kategori string ke kategori_id
   - Foreign key constraint

### Views
5. **`resources/views/admin/kategori_pengeluaran/index.blade.php`**
   - List kategori dengan stats
   - Search dan filter
   - Actions: Edit, Toggle Status, Delete

6. **`resources/views/admin/kategori_pengeluaran/create.blade.php`**
   - Form tambah kategori baru

7. **`resources/views/admin/kategori_pengeluaran/edit.blade.php`**
   - Form edit kategori
   - Info jumlah pengeluaran yang menggunakan kategori

---

## 📝 Files Modified

### Models
1. **`app/Models/Pengeluaran.php`**
   - Hapus const `KATEGORI_DIIZINKAN`
   - Tambah relasi `kategoriPengeluaran()`
   - Tambah accessor `getKategoriNamaAttribute()`
   - Update scope `scopeKategori()` untuk menggunakan kategori_id

### Controllers
2. **`app/Http/Controllers/PengeluaranController.php`**
   - Update `index()` untuk load kategori dari database
   - Update `create()` untuk ambil kategori aktif
   - Update `store()` untuk simpan kategori_id dan kategori (legacy)
   - Update `edit()` untuk ambil kategori aktif
   - Update `update()` untuk simpan kategori_id dan kategori (legacy)
   - Add eager loading `kategoriPengeluaran`

### Routes
3. **`routes/web.php`**
   - Tambah resource routes untuk kategori-pengeluaran
   - Route untuk toggle status kategori

### Views - Pengeluaran
4. **`resources/views/admin/pengeluaran/index.blade.php`**
   - Update filter kategori menggunakan ID
   - Update display kategori menggunakan accessor

5. **`resources/views/admin/pengeluaran/create.blade.php`**
   - Update dropdown kategori menggunakan database
   - Tambah link "Kelola Kategori"
   - Change field name dari `kategori` ke `kategori_id`

6. **`resources/views/admin/pengeluaran/edit.blade.php`**
   - Update dropdown kategori menggunakan database
   - Tambah link "Kelola Kategori"
   - Change field name dari `kategori` ke `kategori_id`

---

## 🗄️ Database Schema

### Tabel: `kategori_pengeluaran`
```sql
id              BIGINT UNSIGNED PRIMARY KEY
nama            VARCHAR(255) UNIQUE
deskripsi       TEXT NULLABLE
is_active       BOOLEAN DEFAULT TRUE
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

### Tabel: `pengeluarans` (Modified)
```sql
...existing columns...
kategori        VARCHAR(255)  -- Legacy field (kept for backward compatibility)
kategori_id     BIGINT UNSIGNED NOT NULL
...existing columns...

FOREIGN KEY (kategori_id) REFERENCES kategori_pengeluaran(id) ON DELETE RESTRICT
```

---

## 🔄 Migration Steps

### Development/Local:
```bash
# 1. Jalankan migrations
php artisan migrate

# 2. Verify tabel dan data
# - Cek tabel kategori_pengeluaran terisi 3 kategori default
# - Cek tabel pengeluarans punya kolom kategori_id
# - Cek data pengeluaran existing sudah termapping ke kategori_id
```

### Production:
```bash
# 1. Backup database
mysqldump -u root -p laundry_hotel_smkn1_ciamis > backup_before_kategori_$(date +%Y%m%d).sql

# 2. Jalankan migrations
php artisan migrate --force

# 3. Verify data
# - Cek semua pengeluaran punya kategori_id yang valid
# - Cek kategori default ter-load

# 4. Test CRUD kategori di UI
```

---

## 🎯 Routes

### Admin Routes (Protected)
```php
/admin/kategori-pengeluaran              GET     index
/admin/kategori-pengeluaran/create       GET     create
/admin/kategori-pengeluaran              POST    store
/admin/kategori-pengeluaran/{id}/edit    GET     edit
/admin/kategori-pengeluaran/{id}         PUT     update
/admin/kategori-pengeluaran/{id}/toggle-status  PATCH  toggleStatus
/admin/kategori-pengeluaran/{id}         DELETE  destroy
```

---

## 🔐 Access Control

- **Admin Only**: Semua operasi CRUD kategori
- **Staff**: Tidak punya akses ke manage kategori (hanya bisa pilih dari dropdown)

---

## ✅ Testing Checklist

### CRUD Kategori
- [ ] Bisa tambah kategori baru
- [ ] Bisa edit kategori
- [ ] Bisa toggle status aktif/nonaktif
- [ ] Kategori nonaktif tidak muncul di dropdown pengeluaran
- [ ] Bisa hapus kategori yang tidak digunakan
- [ ] Tidak bisa hapus kategori yang masih digunakan
- [ ] Search kategori berfungsi

### Integration dengan Pengeluaran
- [ ] Dropdown kategori di form create pengeluaran menampilkan kategori dari database
- [ ] Dropdown kategori di form edit pengeluaran menampilkan kategori dari database
- [ ] Filter kategori di index pengeluaran berfungsi
- [ ] Kategori ditampilkan dengan benar di list pengeluaran
- [ ] Link "Kelola Kategori" berfungsi

### Data Integrity
- [ ] Data pengeluaran lama tetap punya kategori yang benar setelah migration
- [ ] Foreign key constraint berfungsi (tidak bisa delete kategori yang digunakan)
- [ ] Backward compatibility dengan field `kategori` string

---

## 📊 Default Categories

Tiga kategori default yang ter-seed:

1. **Operasional**
   - Deskripsi: Pengeluaran operasional harian laundry
   - Status: Aktif

2. **Bahan Kimia & Sabun**
   - Deskripsi: Pembelian deterjen, pewangi, pelembut, dan bahan kimia laundry
   - Status: Aktif

3. **Listrik & Air**
   - Deskripsi: Tagihan listrik dan air bulanan
   - Status: Aktif

---

## 🎨 UI/UX Features

### Index Page
- Stats cards: Total Kategori, Kategori Aktif, Tidak Aktif
- Search bar
- Table dengan kolom: Nama, Deskripsi, Jumlah Digunakan, Status, Aksi
- Badge untuk status aktif/nonaktif
- Badge untuk jumlah pengeluaran per kategori

### Create/Edit Form
- Field nama kategori (required, unique)
- Field deskripsi (optional)
- Checkbox status aktif
- Validation error messages
- Cancel & Save buttons

### Integration di Form Pengeluaran
- Dropdown kategori yang clean
- Link "Kelola kategori" untuk quick access
- Helper text untuk guide user

---

## 🚀 Future Enhancements

1. **Analytics per Kategori**
   - Chart pengeluaran per kategori
   - Trend kategori bulanan
   - Perbandingan antar kategori

2. **Budget per Kategori**
   - Set target budget per kategori
   - Alert jika melebihi budget
   - Progress bar budget

3. **Color Coding**
   - Assign warna ke setiap kategori
   - Visual distinction di reports

4. **Export per Kategori**
   - Export pengeluaran filtered by kategori
   - Laporan per kategori

---

## 📌 Notes

- **Backward Compatibility**: Field `kategori` string masih dipertahankan untuk compatibility dengan code lama, tapi semua query baru menggunakan `kategori_id`
- **Data Protection**: Foreign key dengan `ON DELETE RESTRICT` memastikan kategori yang masih digunakan tidak bisa dihapus
- **Performance**: Eager loading `kategoriPengeluaran` di index untuk avoid N+1 query
- **UX**: Link "Kelola Kategori" di form pengeluaran untuk kemudahan akses admin

---

**Developed by:** Kiro AI Assistant  
**Verified by:** [Pending]
