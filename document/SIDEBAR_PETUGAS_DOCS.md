# Dokumentasi Sidebar Dashboard Petugas

## Daftar Isi
1. [Gambaran Umum](#gambaran-umum)
2. [Struktur Menu CRUD](#struktur-menu-crud)
3. [Akses Berdasarkan Division](#akses-berdasarkan-division)
4. [Konfigurasi Route](#konfigurasi-route)
5. [Database Setup](#database-setup)
6. [Testing & Debugging](#testing--debugging)
7. [Customization](#customization)

---

## Gambaran Umum

Sidebar dashboard petugas menampilkan menu navigasi yang dinamis berdasarkan **role** dan **division** user:

- **Admin**: Melihat semua menu
- **Staff**: Hanya melihat menu sesuai dengan division mereka
- **Active State**: Menu yang sedang aktif di-highlight dengan warna biru

Fitur:
- ✅ Dinamis dan rapi dengan Tailwind CSS
- ✅ Icons menggunakan Heroicons
- ✅ Active state dengan highlight
- ✅ Role-based access control
- ✅ Clean dan modern design

---

## Struktur Menu CRUD

### Menu yang Tersedia

| Menu | Route Name | Icon | Division | Aksi |
|------|-----------|------|----------|------|
| **Dashboard** | `petugas_piket.dashboard` | Grid | Semua | View |
| **Customer Service** | `petugas.pos.index` | Plus Circle | `customer_service` | CRUD |
| **Washing** | `petugas_piket.washing.index` | Water Drop | `washing` | CRUD |
| **Setrika** | `petugas_piket.setrika.index` | Appliance | `setrika`, `ironing` | CRUD |
| **Packing** | `petugas_piket.packing.index` | Box | `packing` | CRUD |
| **Inventory** | `petugas_piket.inventory.index` | Cube | `inventory` | CRUD |
| **History** | `petugas_piket.history.index` | Clock | Semua | View |

### Tabel Division

```
Division Values di User Table:
- 'washing'       -> Petugas Washing
- 'setrika'       -> Petugas Setrika
- 'ironing'       -> Alias untuk Setrika
- 'packing'       -> Petugas Packing
- 'customer_service' -> Staff Customer Service
- 'inventory'     -> Staff Inventory
```

---

## Akses Berdasarkan Division

### User Role: Staff

Setiap staff hanya melihat 3 menu:
1. Dashboard
2. Menu khusus division mereka (salah satu dari Washing/Setrika/Packing/Customer Service/Inventory)
3. History

Contoh:

**Staff Washing:**
```
- Dashboard
- Washing
- History
```

**Staff Packing:**
```
- Dashboard
- Packing
- History
```

**Staff Customer Service:**
```
- Dashboard
- Customer Service
- History
```

### User Role: Admin

Melihat semua menu:
```
- Dashboard
- Customer Service
- Washing
- Setrika
- Packing
- Inventory
- History
```

---

## Konfigurasi Route

### Route Group untuk Petugas

File: `routes/web.php`

```php
Route::prefix('petugas')->name('petugas_piket.')->middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/', [PetugasController::class, 'dashboard'])->name('dashboard');
    
    // Washing CRUD
    Route::get('/washing', [PetugasController::class, 'washing'])->name('washing.index');
    // Tambahkan routes untuk create, store, edit, update, delete
    
    // Setrika CRUD
    Route::get('/setrika', [PetugasController::class, 'setrika'])->name('setrika.index');
    // Tambahkan routes untuk create, store, edit, update, delete
    
    // Packing CRUD
    Route::get('/packing', [PetugasController::class, 'packing'])->name('packing.index');
    // Tambahkan routes untuk create, store, edit, update, delete
    
    // Inventory
    Route::get('/inventory', [PetugasController::class, 'inventory'])->name('inventory.index');
    Route::post('/inventory/{id}/adjust', [PetugasController::class, 'adjustInventory'])->name('inventory.adjust');
    
    // History
    Route::get('/history', [PetugasController::class, 'history'])->name('history.index');
    
    // Tasks (jika ada)
    Route::post('/tasks/{id}/status', [PetugasController::class, 'updateTaskStatus'])->name('tasks.updateStatus');
    Route::post('/tasks/{id}/complete', [PetugasController::class, 'completeTask'])->name('tasks.complete');
});
```

### Route untuk Customer Service (POS)

```php
Route::get('/petugas/customer-service', [PosController::class, 'index'])->name('petugas.pos.index');
```

---

## Database Setup

### User Table Structure

Pastikan table `users` memiliki kolom:

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('password');
    $table->enum('role', ['admin', 'staff'])->default('staff');
    $table->enum('division', [
        'washing',
        'setrika',
        'ironing',
        'packing',
        'customer_service',
        'inventory'
    ])->nullable();
    $table->string('google_id')->nullable();
    $table->timestamps();
});
```

### Seed Data untuk Testing

File: `database/seeders/UserSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User (melihat semua menu)
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'division' => null,
        ]);

        // Staff Washing
        User::create([
            'name' => 'Petugas Washing',
            'email' => 'washing@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'washing',
        ]);

        // Staff Setrika
        User::create([
            'name' => 'Petugas Setrika',
            'email' => 'setrika@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'setrika',
        ]);

        // Staff Packing
        User::create([
            'name' => 'Petugas Packing',
            'email' => 'packing@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'packing',
        ]);

        // Staff Customer Service
        User::create([
            'name' => 'Customer Service Staff',
            'email' => 'cs@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'customer_service',
        ]);

        // Staff Inventory
        User::create([
            'name' => 'Staff Inventory',
            'email' => 'inventory@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'division' => 'inventory',
        ]);
    }
}
```

Jalankan seeder:
```bash
php artisan db:seed --class=UserSeeder
```

---

## Testing & Debugging

### Cara Testing Sidebar

1. **Login sebagai Admin:**
   ```
   Email: admin@test.com
   Password: password
   ```
   Harusnya melihat semua menu.

2. **Login sebagai Staff Washing:**
   ```
   Email: washing@test.com
   Password: password
   ```
   Harusnya melihat: Dashboard, Washing, History

3. **Login sebagai Staff Packing:**
   ```
   Email: packing@test.com
   Password: password
   ```
   Harusnya melihat: Dashboard, Packing, History

### Debug: Sidebar hanya tampil Dashboard dan History

Jika sidebar hanya menampilkan Dashboard dan History, berarti `division` user kosong atau tidak sesuai. Cek:

1. **Cek user di database:**
   ```sql
   SELECT id, name, role, division FROM users WHERE email = 'user@test.com';
   ```

2. **Cek di Laravel Tinker:**
   ```bash
   php artisan tinker
   
   # Di dalam tinker:
   auth()->user()->division  # Harus ada value
   auth()->user()->role      # Harus 'admin' atau 'staff'
   ```

3. **Jika division NULL:**
   ```bash
   php artisan tinker
   
   # Update division user:
   $user = User::find(1);
   $user->update(['division' => 'washing']);
   ```

---

## Customization

### Menambah Menu Baru

1. Buka `resources/views/petugas_piket/sidebar.blade.php`
2. Tambahkan item ke array `$allMenus`:

```php
[
    'label' => 'Menu Baru',
    'route' => route('petugas_piket.menu-baru.index'),
    'active' => request()->routeIs('petugas_piket.menu-baru.index'),
    'icon' => 'M5 13l4 4L19 7',  // Icon path SVG
    'allowDivisions' => ['washing', 'packing'],  // Division mana saja yang bisa akses
],
```

### Mengubah Icon

Gunakan Heroicons (https://heroicons.com/):

1. Cari icon di website
2. Copy SVG path (d attribute)
3. Ganti di sidebar

Contoh:
```php
'icon' => 'M5 13l4 4L19 7',  // Icon Check
```

### Styling Customization

Sidebar menggunakan Tailwind CSS. Edit class di:

- `bg-white` -> Background
- `border-slate-100` -> Border color
- `bg-blue-600` -> Active menu color
- `text-slate-900` -> Text color

---

## Troubleshooting

| Masalah | Solusi |
|---------|---------|
| Menu tidak muncul | Cek `division` di user table |
| Route error | Pastikan route name match di sidebar |
| Icon tidak jelas | Gunakan SVG path yang benar dari Heroicons |
| Sidebar tertutup di mobile | Sidebar harus di-trigger dengan button toggle |
| Menu tidak highlight | Cek `routeIs()` match dengan route actual |

---

## Best Practices

1. **Selalu gunakan `route()` helper** untuk link generation
2. **Normalisasi division** dengan `strtolower()` saat compare
3. **Tambah CRUD middleware** untuk setiap aksi di controller
4. **Test di semua division** saat development
5. **Update seeder** saat menambah division/menu baru

---

## File Penting

- `resources/views/petugas_piket/sidebar.blade.php` - Sidebar component
- `resources/views/layouts/petugas_piket.blade.php` - Layout utama
- `routes/web.php` - Routing configuration
- `app/Http/Controllers/PetugasController.php` - Controller logic
- `app/Models/User.php` - User model

---

**Last Updated:** April 2026
**Version:** 2.0 (Diperbaiki dengan menu CRUD lengkap)
