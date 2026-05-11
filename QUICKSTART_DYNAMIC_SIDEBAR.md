# ⚡ Quick Start - Dynamic Sidebar

## 🚀 5 Menit Setup

### Step 1: Refresh Autoload (WAJIB!)
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### Step 2: Test Helpers
```bash
php artisan tinker
```

```php
// Quick test
get_user_menus('petugas');
get_division_label('washing');
get_user_initials('John Doe');

// Should work without errors
exit
```

### Step 3: Test in Browser
1. Login dengan user yang punya division
2. Sidebar harus muncul dengan menu sesuai division
3. Done! ✅

---

## 📝 Cara Pakai

### Di Blade View
```blade
{{-- Get menus --}}
@php
    $menus = get_user_menus('petugas');
@endphp

{{-- Loop menus --}}
@foreach ($menus as $menu)
    <a href="{{ $menu['url'] }}">
        {{ $menu['label'] }}
    </a>
@endforeach
```

### Tambah Menu Baru
Edit `config/sidebar.php`:

```php
'petugas_menus' => [
    // ... existing menus
    
    // Add new menu
    [
        'label' => 'Menu Baru',
        'route' => 'petugas_piket.new.index',
        'active' => ['petugas_piket.new.*'],
        'icon' => 'M12 4v16m8-8H4',  // SVG path dari heroicons.com
        'divisions' => ['washing', 'packing'],  // Siapa yang bisa akses
        'roles' => ['admin', 'staff'],
    ],
],
```

Save, refresh browser. Done!

---

## 🔧 Troubleshooting

### Helper not found?
```bash
composer dump-autoload
```

### Menus kosong?
```php
// Check di tinker
auth()->user()->division;  // Harus ada value
```

### Semua user lihat semua menu?
```php
// Check role
auth()->user()->role;  // Harus 'staff' bukan 'admin'
```

---

## 📚 Dokumentasi Lengkap

- **DYNAMIC_SIDEBAR_GUIDE.md** - Complete guide
- **SUMMARY_DYNAMIC_SIDEBAR.md** - Overview
- **TESTING_DYNAMIC_SIDEBAR.md** - Testing guide
- **CHANGELOG_DYNAMIC_SIDEBAR.md** - What's new

---

## ✅ Checklist

- [ ] Run `composer dump-autoload`
- [ ] Clear cache
- [ ] Test helpers in tinker
- [ ] Test in browser
- [ ] Verify menu access control

---

**Ready to use!** 🎉
