# 🧪 MANUAL TESTING GUIDE

## 📋 **Quick Testing Checklist** (15 minutes)

**Date**: May 19, 2026  
**Purpose**: Verify all implementations work correctly in browser  
**Estimated Time**: 15-20 minutes

---

## 🔐 **TEST 1: Admin Access Control** (5 minutes)

### **Step 1: Test Admin Access**
1. Open browser: `http://localhost/LAUNDRY-HOTEL-SMKN-1-CIAMIS/public`
2. Login as admin:
   - Email: `admin@laundry.com`
   - Password: (your admin password)
3. ✅ **Expected**: Successfully logged in
4. Navigate to: `http://localhost/LAUNDRY-HOTEL-SMKN-1-CIAMIS/public/admin`
5. ✅ **Expected**: Dashboard loads successfully (no 403 error)
6. Click around admin menu items (Transaksi, Customer, Layanan, etc.)
7. ✅ **Expected**: All pages load without errors

**Result**: ✅ PASS / ❌ FAIL

---

### **Step 2: Test Staff Access (Should be Blocked)**
1. Logout from admin account
2. Login as staff:
   - Email: `kasir@laundry.com` (or any staff email)
   - Password: (staff password)
3. ✅ **Expected**: Successfully logged in to petugas dashboard
4. Try to access: `http://localhost/LAUNDRY-HOTEL-SMKN-1-CIAMIS/public/admin`
5. ✅ **Expected**: Redirected to `/petugas` with error message
6. Error message should say: "Anda tidak memiliki akses ke halaman admin"

**Result**: ✅ PASS / ❌ FAIL

---

### **Step 3: Test Guest Access (Should Redirect to Login)**
1. Logout completely
2. Try to access: `http://localhost/LAUNDRY-HOTEL-SMKN-1-CIAMIS/public/admin`
3. ✅ **Expected**: Redirected to `/login` page
4. Error message should say: "Silakan login terlebih dahulu"

**Result**: ✅ PASS / ❌ FAIL

---

## ⚡ **TEST 2: Performance Check** (3 minutes)

### **Step 1: Dashboard Load Time**
1. Login as admin
2. Open browser DevTools (F12)
3. Go to Network tab
4. Navigate to admin dashboard
5. Check "DOMContentLoaded" time
6. ✅ **Expected**: <200ms (should be fast)

**Result**: _____ ms (✅ PASS if <200ms)

---

### **Step 2: Transaction List Load Time**
1. Navigate to Transaksi page
2. Check Network tab for load time
3. ✅ **Expected**: <300ms

**Result**: _____ ms (✅ PASS if <300ms)

---

### **Step 3: Check for N+1 Queries (Optional)**
1. Install Laravel Debugbar (if not installed):
   ```bash
   composer require barryvdh/laravel-debugbar --dev
   ```
2. Refresh any page
3. Check Debugbar at bottom of page
4. Click "Queries" tab
5. ✅ **Expected**: Low query count (5-10 queries for most pages)

**Result**: _____ queries (✅ PASS if <20 queries)

---

## 🔒 **TEST 3: Rate Limiting** (5 minutes)

### **Step 1: Test Normal Usage (Should Work)**
1. Login as admin
2. Go to Customer page
3. Create a new customer
4. ✅ **Expected**: Customer created successfully
5. Create another customer
6. ✅ **Expected**: Works fine (within rate limit)

**Result**: ✅ PASS / ❌ FAIL

---

### **Step 2: Test Rate Limit (Advanced - Optional)**
**Note**: This requires a tool like Postman or curl

1. Get CSRF token from login page
2. Make 21 POST requests to `/admin/customers` in 1 minute
3. ✅ **Expected**: 
   - First 20 requests: 200 OK
   - 21st request: 429 Too Many Requests
4. Wait 1 minute
5. Try again
6. ✅ **Expected**: Works again (counter reset)

**Result**: ✅ PASS / ❌ FAIL / ⏭️ SKIP

---

## 💾 **TEST 4: Backup System** (2 minutes)

### **Step 1: Check Backup File**
1. Open File Explorer
2. Navigate to: `C:\laragon\www\LAUNDRY-HOTEL-SMKN-1-CIAMIS\storage\app\Laravel`
3. ✅ **Expected**: See backup zip file (e.g., `2026-05-19-14-24-01.zip`)
4. Check file size
5. ✅ **Expected**: File size > 0 KB (should be ~4 KB)

**Result**: ✅ PASS / ❌ FAIL

---

### **Step 2: Test Backup Command (Optional)**
1. Open terminal in project directory
2. Run: `php artisan backup:run --only-db`
3. ✅ **Expected**: "Backup completed!" message
4. Check storage folder again
5. ✅ **Expected**: New backup file created

**Result**: ✅ PASS / ❌ FAIL / ⏭️ SKIP

---

## 🎨 **TEST 5: UI/UX Check** (3 minutes)

### **Step 1: Sidebar Navigation**
1. Login as admin
2. Click each menu item in sidebar
3. ✅ **Expected**: All pages load correctly
4. Check if active menu item is highlighted
5. ✅ **Expected**: Current page highlighted in sidebar

**Result**: ✅ PASS / ❌ FAIL

---

### **Step 2: Error Messages**
1. Try to create customer with empty name
2. ✅ **Expected**: Validation error shown
3. Error message should be clear and in Indonesian
4. Form data should be preserved (not lost)

**Result**: ✅ PASS / ❌ FAIL

---

### **Step 3: Success Messages**
1. Create a new customer successfully
2. ✅ **Expected**: Success message shown
3. Message should be clear and in Indonesian
4. Redirected to appropriate page

**Result**: ✅ PASS / ❌ FAIL

---

## 📊 **TEST SUMMARY**

### **Results**
| Test | Status | Notes |
|------|--------|-------|
| Admin Access | ✅ / ❌ | |
| Staff Blocked | ✅ / ❌ | |
| Guest Redirect | ✅ / ❌ | |
| Dashboard Speed | ✅ / ❌ | ___ms |
| Transaction Speed | ✅ / ❌ | ___ms |
| Query Count | ✅ / ❌ | ___queries |
| Rate Limiting | ✅ / ❌ / ⏭️ | |
| Backup File | ✅ / ❌ | |
| Sidebar Nav | ✅ / ❌ | |
| Error Messages | ✅ / ❌ | |
| Success Messages | ✅ / ❌ | |

### **Overall**: ___/11 PASSED

---

## 🐛 **Issues Found**

### **Issue 1: [Description]**
- **Severity**: 🔴 Critical / 🟡 Medium / 🟢 Low
- **Steps to Reproduce**: 
- **Expected**: 
- **Actual**: 
- **Fix**: 

### **Issue 2: [Description]**
- **Severity**: 🔴 Critical / 🟡 Medium / 🟢 Low
- **Steps to Reproduce**: 
- **Expected**: 
- **Actual**: 
- **Fix**: 

---

## ✅ **Sign Off**

**Tester**: _________________  
**Date**: May 19, 2026  
**Time**: _________________  
**Overall Status**: ✅ PASS / ❌ FAIL  

**Notes**:
_________________________________________________________________
_________________________________________________________________
_________________________________________________________________

---

## 🚀 **Next Steps**

### **If All Tests Pass** ✅
1. ✅ Mark project as production-ready
2. ✅ Prepare deployment checklist
3. ✅ Setup production environment
4. ✅ Deploy to production

### **If Tests Fail** ❌
1. ❌ Document all issues found
2. ❌ Prioritize fixes (Critical → High → Medium → Low)
3. ❌ Fix issues one by one
4. ❌ Re-test after fixes
5. ❌ Repeat until all tests pass

---

## 💡 **Tips for Testing**

### **Browser DevTools**
- **F12**: Open DevTools
- **Ctrl+Shift+R**: Hard refresh (clear cache)
- **Network Tab**: Check load times
- **Console Tab**: Check for JavaScript errors

### **Common Issues**
- **403 Forbidden**: Check user role and middleware
- **Slow Load**: Check for N+1 queries (use Debugbar)
- **Validation Errors**: Check FormRequest or controller validation
- **Rate Limit**: Wait 1 minute and try again

### **Testing Best Practices**
- ✅ Test in different browsers (Chrome, Firefox, Edge)
- ✅ Test with different user roles (admin, staff)
- ✅ Test with different screen sizes (desktop, mobile)
- ✅ Clear cache between tests (Ctrl+Shift+R)
- ✅ Check browser console for errors

---

**Bro, ini checklist untuk manual testing. Tinggal ikutin step by step aja, total waktu ~15 menit!** 🚀

