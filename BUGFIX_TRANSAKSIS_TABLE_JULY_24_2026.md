# Bug Fix: Table 'transaksis' doesn't exist

**Date:** July 24, 2026  
**Status:** ✅ FIXED  
**Severity:** HIGH - Database Error

---

## 🐛 Problem

Error terjadi saat query ke tabel transaksi:

```
SQLSTATE[42S02]: Base table or view not found: 1146 
Table 'laundry_hotel_smkn1_ciamis.transaksis' doesn't exist

SQL: select count(*) as `aggregate` from `transaksis` where `id` = 1
```

Laravel mencari tabel `transaksis` (plural dengan 's') padahal nama tabel yang benar adalah `transaksi`.

---

## 🔍 Root Cause

1. **Foreign Key Constraint Salah**
   - Tabel `laundry_tasks` memiliki foreign key yang merujuk ke tabel `transaksis` (salah)
   - Seharusnya merujuk ke `transaksi` (benar)

2. **Validation Rule Salah**
   - File `PetugasController.php` menggunakan validasi `exists:transaksis,id`
   - Seharusnya `exists:transaksi,id`

3. **File Dokumentasi Salah**
   - File `DATABASE_MIGRATIONS_SEEDERS.php` menggunakan constraint ke `transaksis`

---

## ✅ Solution

### 1. Fix Code Files

**File: `app/Http/Controllers/PetugasController.php`**
```php
// ❌ BEFORE
'transaction_ids.*' => 'exists:transaksis,id',

// ✅ AFTER
'transaction_ids.*' => 'exists:transaksi,id',
```

**File: `DATABASE_MIGRATIONS_SEEDERS.php`**
```php
// ❌ BEFORE
$table->foreignId('transaksi_id')->constrained('transaksis')->onDelete('cascade');

// ✅ AFTER
$table->foreignId('transaksi_id')->constrained('transaksi')->onDelete('cascade');
```

### 2. Fix Database Foreign Key

**Jalankan SQL Script:**

```bash
# Masuk ke MySQL
mysql -u root -p

# Atau jika pakai Laragon, buka HeidiSQL dan jalankan:
```

```sql
USE laundry_hotel_smkn1_ciamis;

-- Drop constraint yang salah
ALTER TABLE `laundry_tasks` 
DROP FOREIGN KEY `laundry_tasks_transaksi_id_foreign`;

-- Buat ulang dengan referensi yang benar
ALTER TABLE `laundry_tasks` 
ADD CONSTRAINT `laundry_tasks_transaksi_id_foreign` 
FOREIGN KEY (`transaksi_id`) 
REFERENCES `transaksi` (`id`) 
ON DELETE CASCADE;
```

**Atau jalankan file SQL yang sudah dibuat:**
```bash
mysql -u root -p laundry_hotel_smkn1_ciamis < FIX_TRANSAKSIS_TABLE.sql
```

### 3. Verify Fix

```sql
-- Cek foreign key sudah benar
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'laundry_hotel_smkn1_ciamis'
    AND TABLE_NAME = 'laundry_tasks'
    AND REFERENCED_TABLE_NAME IS NOT NULL;

-- Expected result: REFERENCED_TABLE_NAME harus 'transaksi' bukan 'transaksis'
```

---

## 📝 Files Modified

1. ✅ `app/Http/Controllers/PetugasController.php` - Fix validation rule
2. ✅ `DATABASE_MIGRATIONS_SEEDERS.php` - Fix documentation
3. ✅ `FIX_TRANSAKSIS_TABLE.sql` - SQL script untuk fix database

---

## ⚠️ Notes

- File migrasi asli `database/migrations/2026_04_13_103336_create_laundry_tasks_table.php` sudah benar
- File `app/Models/Transaksi.php` sudah benar dengan `protected $table = 'transaksi'`
- Bug ini kemungkinan terjadi karena:
  - Database dibuat manual atau import dari backup yang salah
  - Foreign key constraint dibuat manual dengan nama tabel yang salah
  - Migrasi dijalankan dengan file yang sudah diperbaiki tapi constraint lama tidak di-drop

---

## 🚀 Deployment Steps

### Development:
1. Update code files (sudah done)
2. Jalankan SQL script di database lokal
3. Test CRUD transaksi dan laundry tasks

### Production:
1. Backup database terlebih dahulu
2. Jalankan SQL script di production database
3. Deploy code updates
4. Verify dengan monitoring error logs

---

## ✅ Testing Checklist

- [ ] Bisa create transaksi baru
- [ ] Bisa update status transaksi
- [ ] Bisa assign laundry tasks ke petugas
- [ ] Bulk complete tasks berfungsi (PetugasController)
- [ ] Tidak ada error "Table transaksis doesn't exist" di logs

---

**Fixed by:** Kiro AI Assistant  
**Verified by:** [Pending]
