-- Fix script untuk error "Table transaksis doesn't exist"
-- Masalah: Foreign key constraint yang merujuk ke tabel dengan nama salah

USE laundry_hotel_smkn1_ciamis;

-- 1. Drop foreign key constraint yang salah di tabel laundry_tasks
ALTER TABLE `laundry_tasks` 
DROP FOREIGN KEY IF EXISTS `laundry_tasks_transaksi_id_foreign`;

-- 2. Buat ulang foreign key dengan referensi tabel yang benar
ALTER TABLE `laundry_tasks` 
ADD CONSTRAINT `laundry_tasks_transaksi_id_foreign` 
FOREIGN KEY (`transaksi_id`) 
REFERENCES `transaksi` (`id`) 
ON DELETE CASCADE;

-- 3. Verifikasi constraint sudah benar
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM
    INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE
    TABLE_SCHEMA = 'laundry_hotel_smkn1_ciamis'
    AND TABLE_NAME = 'laundry_tasks'
    AND REFERENCED_TABLE_NAME IS NOT NULL;

-- Expected result:
-- TABLE_NAME: laundry_tasks
-- COLUMN_NAME: transaksi_id  
-- REFERENCED_TABLE_NAME: transaksi (bukan transaksis!)
