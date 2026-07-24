-- Fix script untuk menghapus duplicate layanan
-- Masalah: Ada layanan yang sama muncul 2x di database

USE laundry_hotel_smkn1_ciamis;

-- 1. Cek layanan duplicate (same nama + kategori)
SELECT nama, kategori, COUNT(*) as jumlah, GROUP_CONCAT(id) as ids
FROM layanans
GROUP BY nama, kategori
HAVING COUNT(*) > 1;

-- 2. List semua layanan untuk review
SELECT id, nama, kategori, harga, status, created_at
FROM layanans
ORDER BY nama, kategori, id;

-- 3. Backup data sebelum delete (opsional)
-- CREATE TABLE layanans_backup AS SELECT * FROM layanans;

-- 4. Delete duplicate - Keep yang ID terkecil (created first), delete yang lain
-- NOTES: Jangan jalankan query ini dulu! Review hasil query #1 dan #2 terlebih dahulu
-- DELETE l1 FROM layanans l1
-- INNER JOIN layanans l2 
-- WHERE l1.id > l2.id 
--   AND l1.nama = l2.nama 
--   AND l1.kategori = l2.kategori;

-- 5. Verify tidak ada duplicate lagi
-- SELECT nama, kategori, COUNT(*) as jumlah
-- FROM layanans
-- GROUP BY nama, kategori
-- HAVING COUNT(*) > 1;
