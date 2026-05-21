# 🔄 SWITCH PHP VERSION DI LARAGON

## 📍 **Current Issue**
- ✅ PHP 8.5.5 installed di: `C:\laragon\bin\php\php-8.5.5-Win32-vs17-x64\`
- ❌ Laragon masih pake PHP 8.3.30 sebagai default
- ❌ Terminal detect PHP 8.3.30 (bukan 8.5.5)

---

## 🚀 **SOLUTION: Switch PHP Version di Laragon**

### **Method 1: Via Laragon Menu** (RECOMMENDED) ⭐

#### **Step 1: Open Laragon**
1. Buka aplikasi **Laragon**
2. Pastikan Laragon sudah running

#### **Step 2: Switch PHP Version**
1. **Right-click** pada Laragon window (atau icon di system tray)
2. Pilih **Menu** → **PHP** → **Version**
3. Pilih **PHP 8.5.5**
4. Tunggu beberapa detik (Laragon akan restart services)

#### **Step 3: Restart All Services**
1. Klik **"Stop All"** (bottom right)
2. Tunggu sampai semua services stop
3. Klik **"Start All"**
4. Tunggu sampai semua services start (Apache, MySQL, etc.)

#### **Step 4: Verify**
1. Buka **NEW terminal/PowerShell** (close yang lama)
2. Run:
   ```bash
   php -v
   ```
3. **Expected Output**:
   ```
   PHP 8.5.5 (cli) (built: Apr  7 2026 19:23:32)
   ```

---

### **Method 2: Manual PATH Update** (If Method 1 doesn't work)

#### **Step 1: Check Current PATH**
```bash
echo $env:PATH
```

Look for: `C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64`

#### **Step 2: Update Laragon Settings**
1. Open Laragon
2. Go to **Menu** → **Preferences** → **Services & Ports**
3. Check PHP version setting
4. Make sure it points to PHP 8.5.5

#### **Step 3: Restart Computer** (Nuclear Option)
If nothing works:
1. Close all applications
2. Restart computer
3. Open Laragon
4. Start All services
5. Open NEW terminal
6. Test: `php -v`

---

## ✅ **After Successful Switch**

### **1. Verify PHP Version**
```bash
php -v
```
**Expected**: PHP 8.5.5 ✅

### **2. Check Composer Platform**
```bash
composer check-platform-reqs
```
**Expected**: All requirements satisfied ✅

### **3. Update Composer Dependencies**
```bash
composer update
```
**Expected**: No errors, all packages updated ✅

### **4. Clear Laravel Caches**
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### **5. Test Application**
```bash
php artisan serve
```
**Expected**: Server starts at http://localhost:8000 ✅

### **6. Visit Admin Dashboard**
Open browser: http://localhost:8000/admin/dashboard

**Expected**: Dashboard loads without errors ✅

---

## 🔍 **Troubleshooting**

### **Issue 1: Laragon Menu doesn't show PHP 8.5.5**

**Solution**:
1. Close Laragon completely
2. Check folder exists: `C:\laragon\bin\php\php-8.5.5-Win32-vs17-x64\`
3. Make sure `php.exe` exists inside that folder
4. Open Laragon again
5. Try Method 1 again

---

### **Issue 2: Terminal still shows PHP 8.3.30**

**Solution A**: Restart Terminal
1. Close ALL terminal/PowerShell windows
2. Open NEW terminal
3. Test: `php -v`

**Solution B**: Restart Computer
1. Restart your computer
2. Open Laragon → Start All
3. Open NEW terminal
4. Test: `php -v`

---

### **Issue 3: Composer update fails**

**Error**: `symfony/clock requires php (>= 8.4.0.0-dev)`

**Solution**: This should NOT happen with PHP 8.5.5
- Make sure `php -v` shows 8.5.5 FIRST
- Then run `composer update`

---

## 📋 **Verification Checklist**

Before continuing, make sure:
- [ ] Laragon switched to PHP 8.5.5 (via Menu → PHP → Version)
- [ ] Laragon restarted (Stop All → Start All)
- [ ] Terminal restarted (close old, open new)
- [ ] `php -v` shows **PHP 8.5.5** ✅
- [ ] `composer check-platform-reqs` passes ✅
- [ ] `composer update` completes successfully ✅
- [ ] `php artisan serve` works ✅
- [ ] Admin dashboard loads ✅

---

## 🎯 **Why This Matters**

**Current State**:
- Terminal outside project: PHP 8.5.5 ✅
- Laragon/Project: PHP 8.3.30 ❌

**Problem**:
- Composer uses Laragon's PHP (8.3.30)
- Laravel 13 requires PHP 8.4+ (via symfony/clock)
- Composer update will FAIL ❌

**Solution**:
- Switch Laragon to PHP 8.5.5
- Everything will work ✅

---

## 📞 **Next Steps After Success**

Once `php -v` shows 8.5.5:

1. ✅ Run `composer update`
2. ✅ Test application
3. ✅ Git commit PHP version update
4. ✅ Continue with optimizations:
   - Form Request Validation
   - Activity Logging
   - Caching Strategy
   - Manual Testing

---

**Date**: May 20, 2026  
**Status**: ⏳ **WAITING FOR PHP SWITCH**  
**Priority**: 🔴 **CRITICAL** (must fix before continuing)

**Bro, switch PHP version di Laragon dulu via Menu → PHP → Version → PHP 8.5.5, terus restart Laragon nya!** 🚀

**Setelah itu, buka NEW terminal dan run `php -v` untuk verify!** ✅
