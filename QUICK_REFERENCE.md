# Quick Reference & Troubleshooting Guide

## 📋 File yang Telah Diupdate/Dibuat

### 1. **Sidebar Component (Main File)**
- **Path**: `resources/views/petugas_piket/sidebar.blade.php`
- **Status**: ✅ Diperbaiki
- **Perubahan**:
  - Struktur lebih clean dan maintainable
  - Better comments dan documentation
  - Improved menu filtering logic
  - Better icon handling
  - Added `allowDivisions` array untuk setiap menu

### 2. **Documentation**
- **Path**: `SIDEBAR_PETUGAS_DOCS.md`
- **Isi**: Dokumentasi lengkap tentang sidebar, routes, database setup

### 3. **Contoh CRUD Template**
- **Path**: `resources/views/petugas_piket/CRUD_TEMPLATE.blade.php`
- **Isi**: Template halaman list dengan filter, table, dan pagination

### 4. **Contoh Controller Methods**
- **Path**: `PETUGAS_CONTROLLER_EXAMPLE.php`
- **Isi**: Implementasi methods untuk semua action (dashboard, washing, setrika, packing, inventory, history)

### 5. **Reusable Blade Components**
- **Path**: `REUSABLE_COMPONENTS.blade.php`
- **Isi**: Components yang bisa di-reuse (nav-item, status-badge, buttons, modals, alerts)

### 6. **Database Setup**
- **Path**: `DATABASE_MIGRATIONS_SEEDERS.php`
- **Isi**: Migrations dan seeders untuk setup data testing

---

## 🚀 Quickstart: Menampilkan Menu CRUD Lengkap

### Step 1: Verifikasi User Division
```sql
-- Check di database
SELECT id, name, role, division FROM users WHERE email = 'your@email.com';
```

Jika `division` kosong, update:
```bash
php artisan tinker
$user = User::find(1);
$user->update(['division' => 'washing']);
```

### Step 2: Sidebar Sudah Benar
File `resources/views/petugas_piket/sidebar.blade.php` sudah diperbaiki dengan:
- ✅ Dashboard
- ✅ Customer Service (untuk cs division)
- ✅ Washing (untuk washing division)
- ✅ Setrika (untuk setrika/ironing division)
- ✅ Packing (untuk packing division)
- ✅ Inventory (untuk inventory division)
- ✅ History (untuk semua division)

### Step 3: Cek Routes
Pastikan route sudah terdaftar di `routes/web.php`:
```php
Route::prefix('petugas')->name('petugas_piket.')->middleware(['auth'])->group(function () {
    Route::get('/', [PetugasController::class, 'dashboard'])->name('dashboard');
    Route::get('/washing', [PetugasController::class, 'washing'])->name('washing.index');
    Route::get('/setrika', [PetugasController::class, 'setrika'])->name('setrika.index');
    Route::get('/packing', [PetugasController::class, 'packing'])->name('packing.index');
    Route::get('/inventory', [PetugasController::class, 'inventory'])->name('inventory.index');
    Route::get('/history', [PetugasController::class, 'history'])->name('history.index');
});

// Untuk Customer Service (POS)
Route::get('/petugas/customer-service', [PosController::class, 'index'])->name('petugas.pos.index');
```

### Step 4: Test Login
```
Email: washing@test.com
Password: password
```

Harusnya melihat:
- Dashboard
- Washing
- History

---

## 🔍 Troubleshooting

### ❌ Sidebar hanya tampil Dashboard dan History

**Penyebab**: Division user tidak sesuai atau NULL

**Solusi**:
```bash
php artisan tinker

# Check user
auth()->user()->division  # Harus ada value: 'washing', 'packing', dll
auth()->user()->role      # Harus 'admin' atau 'staff'

# Update jika perlu
$user = User::find(1);
$user->update(['division' => 'washing', 'role' => 'staff']);
```

### ❌ Route not found

**Penyebab**: Route belum terdaftar di `routes/web.php`

**Solusi**:
```bash
# Check registered routes
php artisan route:list | grep petugas

# Pastikan ada:
# petugas_piket.dashboard -> /petugas
# petugas_piket.washing.index -> /petugas/washing
# petugas_piket.setrika.index -> /petugas/setrika
# petugas_piket.packing.index -> /petugas/packing
# petugas_piket.inventory.index -> /petugas/inventory
# petugas_piket.history.index -> /petugas/history
# petugas.pos.index -> /petugas/customer-service
```

### ❌ Icon tidak tampil

**Penyebab**: SVG path salah

**Solusi**:
```blade
{{-- Gunakan format yang benar --}}
'icon' => 'M12 4v16m8-8H4'

{{-- Bukan --}}
'icon' => 'M12 4v16m8-8H4 M10 2h4'  {{-- ❌ Terlalu banyak path --}}
```

### ❌ Active state tidak highlight

**Penyebab**: `routeIs()` tidak cocok

**Cek**:
```blade
{{-- Di sidebar --}}
'active' => request()->routeIs('petugas_piket.washing.index'),

{{-- Route harus match --}}
// routes/web.php
Route::get('/washing', ...)->name('washing.index');  // ✅ Full name: petugas_piket.washing.index
```

---

## 📝 Customization Tips

### Menambah Menu Baru
1. Edit `resources/views/petugas_piket/sidebar.blade.php`
2. Tambah item ke `$allMenus` array:
```php
[
    'label' => 'Menu Baru',
    'route' => route('petugas_piket.menu-baru.index'),
    'active' => request()->routeIs('petugas_piket.menu-baru.index'),
    'icon' => 'SVG_PATH_HERE',
    'allowDivisions' => ['washing', 'packing'],
],
```

### Mengubah Icon
1. Cari di https://heroicons.com/
2. Copy SVG path (dari attribute `d`)
3. Ganti di sidebar

Contoh:
```
Heroicons "check" icon:
M5 13l4 4L19 7
```

### Mengubah Styling
Edit class Tailwind:
```blade
{{-- Active menu color --}}
'bg-blue-600 text-white'  {{-- Bisa diubah ke red, green, purple, dll --}}

{{-- Hover color --}}
'hover:bg-slate-50'  {{-- Bisa diubah --}}

{{-- Border color --}}
'border-slate-100'  {{-- Bisa diubah --}}
```

---

## 🎯 Testing Checklist

- [ ] Admin user bisa melihat semua menu
- [ ] Staff washing hanya lihat: Dashboard, Washing, History
- [ ] Staff packing hanya lihat: Dashboard, Packing, History
- [ ] Staff setrika hanya lihat: Dashboard, Setrika, History
- [ ] Staff CS hanya lihat: Dashboard, Customer Service, History
- [ ] Staff inventory hanya lihat: Dashboard, Inventory, History
- [ ] Menu active state highlight dengan benar
- [ ] Semua route berfungsi (tidak 404)
- [ ] Icons terbaca dengan baik
- [ ] Sidebar responsive di mobile/tablet

---

## 📚 Menggunakan Reusable Components

File `REUSABLE_COMPONENTS.blade.php` berisi contoh component Blade yang bisa di-reuse:

### Cara menggunakan:
1. Buat folder `resources/views/components/`
2. Buat file sesuai contoh (nav-item.blade.php, status-badge.blade.php, dll)
3. Gunakan di view:

```blade
{{-- Status Badge --}}
<x-status-badge status="completed" />

{{-- CRUD Buttons --}}
<x-crud-button type="create" route="{{ route('petugas_piket.washing.create') }}" />

{{-- Alert --}}
<x-alert type="success" title="Success" message="Task completed" />

{{-- Modal --}}
<x-modal-form modalId="editModal" title="Edit Task" action="{{ route('...') }}" method="PATCH">
    <input type="text" name="name" required>
</x-modal-form>
```

---

## 🔐 Security Notes

1. **Middleware Check**: Semua route sudah protected dengan `['auth']` middleware
2. **Division Check**: Controller melakukan `ensureStaffDivisionAccess()` check
3. **Role Check**: Admin bisa akses semua, staff hanya akses sesuai division
4. **CSRF Protection**: Semua form harus include `@csrf`

---

## 📞 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| Sidebar tidak muncul | Check layout include `@include('petugas_piket.sidebar')` |
| Menu tidak responsive | Add mobile toggle button & media queries |
| Icons blur/small | Use `w-[18px] h-[18px]` class size |
| Active state tidak jelas | Increase contrast atau ubah color |
| Route mismatch | Use `route()` helper, jangan hardcode path |
| Permission denied | Check user role & division di database |

---

## 📖 Useful Laravel Commands

```bash
# Check all routes
php artisan route:list | grep petugas

# Check routes with names
php artisan route:list --name=petugas

# Clear cache if routes not loading
php artisan route:clear

# Debug user in tinker
php artisan tinker
auth()->user()->load('division')  # Check division value
```

---

## 🎨 Tailwind CSS Customization

Default colors digunakan:
- Primary: `blue-600` / `blue-700`
- Success: `green-600`
- Warning: `yellow-600`
- Error: `red-600`
- Neutral: `slate-*`

Untuk mengubah warna global, edit `tailwind.config.js` atau gunakan custom class di CSS.

---

**Last Updated**: April 2026  
**Version**: 2.0 - Sidebar CRUD Lengkap  
**Status**: ✅ Ready for Production
