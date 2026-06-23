# 🧪 QUICK TEST GUIDE - Bug Fixes June 23, 2026

## ⚡ Quick Test Commands

### 1. Clear All Cache
```bash
php artisan cache:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
```

### 2. Check for PHP Errors
```bash
php artisan check
```

---

## 🧪 MANUAL TESTING CHECKLIST

### ✅ Test 1: Pembayaran Auto-Status
**Steps:**
1. Go to `/admin/pembayaran/create`
2. Select a transaction
3. **Test Case A - LUNAS:**
   - Total tagihan: Rp 100,000
   - Input uang: Rp 100,000 (or more)
   - ✅ Expected: Status auto "Lunas" (green), show kembalian
   
4. **Test Case B - CICILAN:**
   - Total tagihan: Rp 100,000
   - Input uang: Rp 50,000
   - ✅ Expected: Status auto "Cicilan" (orange), show sisa

**Look for:**
- ❌ Manual dropdown should NOT exist
- ✅ Calculator shows correct status
- ✅ Success message mentions correct status

---

### ✅ Test 2: Export Modal
**Steps:**
1. Go to `/admin/laporan_keuangan`
2. Click "Export Data" button
3. Modal should open with:
   - ✅ Radio buttons: Excel / PDF
   - ✅ Dropdown: Bulanan / Tahunan / Custom
   - ✅ Date pickers (only show if Custom selected)
4. Select options and click "Export Sekarang"
5. ✅ File should download with correct filter

**Look for:**
- ✅ Modal opens smoothly
- ✅ Alpine.js working (custom date toggle)
- ✅ Export respects selected filters

---

### ✅ Test 3: WhatsApp Notification with Customer Name
**Steps:**
1. Login as staff (washing/setrika/packing)
2. Go to washing queue
3. Complete a task
4. ✅ Flash message should say: "Tugas Pencucian untuk pelanggan **[NAME]** berhasil diselesaikan!"
5. ✅ WhatsApp notification banner should appear
6. Click "Notifikasi via WA"
7. Check WhatsApp message format:
   ```
   Halo *[CUSTOMER NAME]*,
   
   Proses *Pencucian* untuk pesanan Anda telah selesai! ✅
   
   📌 No. Invoice: *#TRX-XXX*
   📅 Selesai: 23/06/2026 14:30
   ```

**Look for:**
- ✅ Customer name in message
- ✅ Correct stage name (Pencucian/Setrika/Packing)
- ✅ WhatsApp link working

---

### ✅ Test 4: Pengeluaran - Status Field Removed
**Steps:**
1. Go to `/admin/pengeluaran/create`
2. Fill form and submit
3. ✅ Check database: status field can be null/empty
4. ✅ No errors when saving

**Look for:**
- ✅ No validation error for status
- ✅ Record saves successfully

---

### ✅ Test 5: Enhanced Notifications
**Steps:**
1. Create transaction with status "belum_bayar"
2. Go to pembayaran and pay it full (lunas)
3. ✅ Success message should say:
   ```
   ✅ NOTIFIKASI: Pelanggan [NAME] yang sebelumnya belum bayar kini statusnya LUNAS!
   ```

**Look for:**
- ✅ Special notification for status change
- ✅ Different message for cicilan vs lunas

---

## 🐛 Common Issues & Solutions

### Issue 1: Modal Not Opening
**Solution:**
```bash
# Clear view cache
php artisan view:clear

# Check Alpine.js is loaded
# Open browser console, should see Alpine in window object
```

### Issue 2: WhatsApp Link Not Working
**Solution:**
- Check phone number format (should be 628xxx, not 08xxx)
- Check `session('notification_link')` is set
- View source: search for "notification_link"

### Issue 3: Auto-Status Not Detecting
**Solution:**
```bash
# Check PembayaranController validation
# Make sure 'status_pembayaran' is NOT in $validated array
```

---

## 📊 Expected Results Summary

| Feature | Before | After |
|---------|--------|-------|
| Payment Status | Manual dropdown | ✅ Auto-detect |
| Export | Direct links | ✅ Modal with filters |
| WA Notification | Generic | ✅ With customer name |
| Pengeluaran Status | In fillable | ✅ Removed |
| Payment Stats | Unused vars | ✅ Cleaned up |
| Calculator UI | Basic | ✅ Enhanced with status |

---

## 🚀 Deploy to Production

After all tests pass:

1. **Commit changes:**
```bash
git add .
git commit -m "fix: payment auto-status, export modal, customer notifications"
git push origin main
```

2. **On production server:**
```bash
git pull origin main
php artisan cache:clear
php artisan view:clear
php artisan config:clear
```

3. **Monitor logs:**
```bash
tail -f storage/logs/laravel.log
```

---

## ✅ Success Criteria

All features working:
- ✅ Payment auto-detects lunas/cicilan
- ✅ Export modal functional
- ✅ WhatsApp with customer names
- ✅ No PHP errors
- ✅ No JavaScript console errors
- ✅ Database saves correctly

---

## 📞 Support

If issues found:
1. Check `storage/logs/laravel.log`
2. Check browser console (F12)
3. Test on different browser
4. Clear all caches again

**Emergency Rollback:**
```bash
git log --oneline -3
git revert <commit-hash>
php artisan cache:clear
```
