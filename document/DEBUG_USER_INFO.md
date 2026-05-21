# 🔍 DEBUG - User Info Check

## Masalah
Pas klik menu washing/cs/dll di sidebar petugas, malah redirect ke dashboard admin.

## Kemungkinan Penyebab

### 1. User Division Tidak Sesuai
User login sebagai staff tapi division nya NULL atau salah.

**Check:**
```bash
php artisan tinker
```

```php
// Check user yang login
$user = User::where('email', 'EMAIL_PETUGAS')->first();
dd([
    'id' => $user->id,
    'name' => $user->name,
    'email' => $user->email,
    'role' => $user->role,
    'division' => $user->division,
]);
```

**Expected:**
- role: `staff`
- division: `washing` / `setrika` / `packing` / `customer_service` / `inventory`

**If NULL or wrong, fix it:**
```php
$user = User::where('email', 'EMAIL_PETUGAS')->first();
$user->update(['division' => 'washing']); // atau division lain
```

---

### 2. Route Middleware Redirect
Ada middleware yang redirect staff ke admin dashboard.

**Check routes/web.php:**
- Pastikan route `petugas_piket.*` tidak ada middleware yang redirect
- Pastikan route `petugas.pos.index` accessible untuk staff

---

### 3. MenuService Filter Terlalu Strict
MenuService filter menu berdasarkan division, tapi logic nya salah.

**Already Fixed:**
- ✅ Admin sekarang bisa akses semua menu
- ✅ Staff dengan division NULL sekarang di-deny (bukan allow)

---

## Testing Steps

### Step 1: Check User Info
```bash
php artisan tinker
```

```php
auth()->user();
// Check: role, division
```

### Step 2: Check Menu Generation
```bash
php artisan tinker
```

```php
$menuService = app(\App\Services\MenuService::class);
$menus = $menuService->getMenusForUser('petugas');
dd($menus);
```

**Expected Output:**
```php
[
    [
        'label' => 'Dashboard',
        'route' => 'petugas_piket.dashboard',
        'url' => 'http://localhost/petugas',
        'is_active' => true/false,
        'divisions' => ['washing', 'setrika', ...],
    ],
    [
        'label' => 'Washing',
        'route' => 'petugas_piket.washing.index',
        'url' => 'http://localhost/petugas/washing',
        'is_active' => false,
        'divisions' => ['washing'],
    ],
    // ... more menus
]
```

### Step 3: Check Route URLs
```bash
php artisan route:list | grep petugas
```

**Expected:**
```
GET|HEAD  petugas ........................ petugas_piket.dashboard
GET|HEAD  petugas/washing ................ petugas_piket.washing.index
GET|HEAD  petugas/setrika ................ petugas_piket.setrika.index
GET|HEAD  petugas/packing ................ petugas_piket.packing.index
GET|HEAD  petugas/customer-service ....... petugas.pos.index
GET|HEAD  petugas/inventory .............. petugas_piket.inventory.index
GET|HEAD  petugas/history ................ petugas_piket.history.index
```

### Step 4: Test in Browser
1. Login sebagai staff dengan division `washing`
2. Check sidebar - harus muncul: Dashboard, Washing, History (3 menu)
3. Click "Washing" - harus redirect ke `/petugas/washing`
4. Check URL di browser - harus `/petugas/washing` bukan `/admin`

---

## Quick Fix Commands

### Fix User Division
```bash
php artisan tinker
```

```php
// Update user division
User::where('email', 'washing@test.com')->update(['division' => 'washing']);
User::where('email', 'setrika@test.com')->update(['division' => 'setrika']);
User::where('email', 'packing@test.com')->update(['division' => 'packing']);
User::where('email', 'cs@test.com')->update(['division' => 'customer_service']);
User::where('email', 'inventory@test.com')->update(['division' => 'inventory']);
```

### Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

## Expected Behavior

### For Staff with division = 'washing':
- ✅ Can see: Dashboard, Washing, History
- ❌ Cannot see: Setrika, Packing, Customer Service, Inventory

### For Staff with division = 'customer_service':
- ✅ Can see: Dashboard, Customer Service, History
- ❌ Cannot see: Washing, Setrika, Packing, Inventory

### For Admin:
- ✅ Can see: ALL menus (Dashboard, CS, Washing, Setrika, Packing, Inventory, History)

---

## If Still Not Working

Check browser console for JavaScript errors:
1. Open browser DevTools (F12)
2. Go to Console tab
3. Check for errors when clicking menu

Check network tab:
1. Open browser DevTools (F12)
2. Go to Network tab
3. Click menu item
4. Check the request URL - should be `/petugas/washing` not `/admin`

---

**Next**: Jalankan testing steps di atas dan report hasilnya bro!
