# 🚀 Dynamic Sidebar System - Complete Guide

## 📋 Overview

Sistem sidebar yang **sepenuhnya dinamis** menggunakan:
- ✅ **Config-driven** - Semua menu disimpan di `config/sidebar.php`
- ✅ **Service Layer** - Logic terpusat di `MenuService`
- ✅ **Helper Functions** - Easy-to-use helpers
- ✅ **Role & Division Based** - Automatic filtering
- ✅ **Reusable Components** - Blade components untuk consistency

---

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────┐
│           config/sidebar.php                    │
│  (Single source of truth untuk semua menu)      │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│        app/Services/MenuService.php             │
│  - getMenusForUser()                            │
│  - canAccessMenu()                              │
│  - normalizeDivision()                          │
│  - getDivisionLabel()                           │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│           app/helpers.php                       │
│  - get_user_menus()                             │
│  - get_division_label()                         │
│  - get_user_initials()                          │
└─────────────────┬───────────────────────────────┘
                  │
                  ▼
┌─────────────────────────────────────────────────┐
│    resources/views/petugas_piket/sidebar.blade  │
│    resources/views/admin/sidebar.blade          │
│  (Clean view dengan minimal logic)              │
└─────────────────────────────────────────────────┘
```

---

## 📁 File Structure

```
app/
├── Services/
│   └── MenuService.php          # Core menu logic
├── helpers.php                  # Helper functions
config/
└── sidebar.php                  # Menu configuration
resources/views/
├── petugas_piket/
│   └── sidebar.blade.php        # Petugas sidebar
├── admin/
│   └── sidebar.blade.php        # Admin sidebar (optional)
└── components/
    └── sidebar-menu-item.blade.php  # Reusable menu item
```

---

## ⚙️ Configuration

### `config/sidebar.php`

```php
return [
    // Brand info
    'brand' => [
        'name' => 'Bening Laundry',
        'tagline' => 'Management Portal',
    ],

    // Division aliases untuk normalisasi
    'division_aliases' => [
        'kasir' => 'customer_service',
        'cs' => 'customer_service',
        'ironing' => 'setrika',
    ],

    // Division labels untuk display
    'division_labels' => [
        'washing' => 'Washing',
        'setrika' => 'Setrika',
        'packing' => 'Packing',
        'customer_service' => 'Customer Service',
        'inventory' => 'Inventory',
    ],

    // Petugas menus
    'petugas_menus' => [
        [
            'label' => 'Dashboard',
            'route' => 'petugas_piket.dashboard',
            'active' => ['petugas_piket.dashboard'],
            'icon' => 'M3.75 6A2.25...',  // SVG path
            'divisions' => ['washing', 'setrika', 'packing', 'customer_service', 'inventory'],
            'roles' => ['admin', 'staff'],
        ],
        // ... more menus
    ],

    // Admin menus
    'admin_menus' => [
        // ... admin specific menus
    ],
];
```

---

## 🎯 Usage

### 1. **In Blade Views**

```blade
{{-- Get menus for current user --}}
@php
    $menus = get_user_menus('petugas');  // or 'admin'
@endphp

{{-- Loop through menus --}}
@foreach ($menus as $menu)
    <a href="{{ $menu['url'] }}" 
       class="{{ $menu['is_active'] ? 'active' : '' }}">
        <svg><path d="{{ $menu['icon'] }}" /></svg>
        {{ $menu['label'] }}
    </a>
@endforeach

{{-- Get division label --}}
<span>{{ get_division_label(auth()->user()->division) }}</span>

{{-- Get user initials --}}
<div>{{ get_user_initials(auth()->user()->name) }}</div>
```

### 2. **Using Blade Component**

```blade
<x-sidebar-menu-item 
    :label="$menu['label']"
    :url="$menu['url']"
    :icon="$menu['icon']"
    :active="$menu['is_active']"
    :badge="$menu['badge'] ?? null"
/>
```

### 3. **In Controllers**

```php
use App\Services\MenuService;

class DashboardController extends Controller
{
    public function __construct(
        protected MenuService $menuService
    ) {}

    public function index()
    {
        $menus = $this->menuService->getMenusForUser('petugas');
        $divisionLabel = $this->menuService->getDivisionLabel(auth()->user()->division);
        
        return view('dashboard', compact('menus', 'divisionLabel'));
    }
}
```

### 4. **Using Helper Functions**

```php
// Get menus
$menus = get_user_menus('petugas');

// Get division label
$label = get_division_label('washing');  // Returns: "Washing"

// Get user initials
$initials = get_user_initials('John Doe');  // Returns: "JD"

// Format currency
$formatted = format_rupiah(50000);  // Returns: "Rp 50.000"

// Status badge class
$class = status_badge_class('completed');  // Returns: "bg-green-100 text-green-700"

// Status label
$label = status_label('in_progress');  // Returns: "Dalam Proses"
```

---

## 🔐 Access Control

### How It Works

1. **Admin Role**: Sees ALL menus (no filtering)
2. **Staff Role**: Sees menus based on their division

### Menu Structure

```php
[
    'label' => 'Washing',
    'route' => 'petugas_piket.washing.index',
    'divisions' => ['washing'],           // Only washing division
    'roles' => ['admin', 'staff'],        // Both admin & staff
]
```

### Access Logic

```php
// In MenuService::canAccessMenu()

// 1. Admin bypasses all checks
if ($role === 'admin') {
    return true;
}

// 2. Check role permission
if (!in_array($role, $menu['roles'])) {
    return false;
}

// 3. Check division permission
if (!in_array($division, $menu['divisions'])) {
    return false;
}

return true;
```

---

## 🎨 Customization

### Adding New Menu

Edit `config/sidebar.php`:

```php
'petugas_menus' => [
    // ... existing menus
    
    [
        'label' => 'Quality Control',
        'route' => 'petugas_piket.qc.index',
        'active' => ['petugas_piket.qc.*'],
        'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'divisions' => ['qc', 'admin'],
        'roles' => ['admin', 'staff'],
    ],
],
```

### Adding New Division

1. **Add to aliases** (if needed):
```php
'division_aliases' => [
    'quality' => 'qc',
    'quality control' => 'qc',
],
```

2. **Add to labels**:
```php
'division_labels' => [
    'qc' => 'Quality Control',
],
```

3. **Update menus** to include new division:
```php
'divisions' => ['washing', 'qc'],
```

### Changing Icons

Find icons at [Heroicons](https://heroicons.com/), copy the SVG path:

```php
'icon' => 'M12 4v16m8-8H4',  // Plus icon
```

### Custom Badge

```php
[
    'label' => 'Notifications',
    'route' => 'notifications.index',
    'badge' => 'unreadCount',  // Variable name
]
```

In your view:
```blade
@php
    $unreadCount = auth()->user()->unreadNotifications->count();
@endphp
```

---

## 🧪 Testing

### Test User Access

```php
// In tinker
php artisan tinker

// Test washing staff
$user = User::where('division', 'washing')->first();
auth()->login($user);
$menus = get_user_menus('petugas');
// Should see: Dashboard, Washing, History

// Test admin
$admin = User::where('role', 'admin')->first();
auth()->login($admin);
$menus = get_user_menus('petugas');
// Should see: ALL menus
```

### Test Division Normalization

```php
$service = app(\App\Services\MenuService::class);

$service->normalizeDivision('kasir');  // Returns: 'customer_service'
$service->normalizeDivision('cs');     // Returns: 'customer_service'
$service->normalizeDivision('ironing'); // Returns: 'setrika'
```

### Test Helpers

```php
get_division_label('washing');        // "Washing"
get_division_label('customer_service'); // "Customer Service"
get_user_initials('John Doe');        // "JD"
format_rupiah(50000);                 // "Rp 50.000"
status_badge_class('completed');      // "bg-green-100 text-green-700"
```

---

## 🐛 Troubleshooting

### Menu tidak muncul

**Penyebab**: Division user tidak match dengan config

**Solusi**:
```bash
php artisan tinker
$user = auth()->user();
$user->division;  // Check value

// Update jika perlu
$user->update(['division' => 'washing']);
```

### Menu muncul untuk semua user

**Penyebab**: `divisions` array kosong atau tidak ada

**Solusi**: Pastikan setiap menu punya `divisions` array:
```php
'divisions' => ['washing', 'packing'],  // ✅ Correct
'divisions' => [],                       // ❌ Wrong - allows all
```

### Active state tidak work

**Penyebab**: Route pattern tidak match

**Solusi**: Check `active` array:
```php
'active' => ['petugas_piket.washing.*'],  // Match all washing routes
'active' => ['petugas_piket.washing.index'],  // Match only index
```

### Helper function not found

**Penyebab**: Autoload belum di-refresh

**Solusi**:
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

---

## 🚀 Migration Guide

### From Old Sidebar to Dynamic Sidebar

**Before** (Hardcoded in Blade):
```blade
@php
    $allMenus = [
        ['label' => 'Dashboard', ...],
        ['label' => 'Washing', ...],
    ];
@endphp
```

**After** (Dynamic from Config):
```blade
@php
    $menus = get_user_menus('petugas');
@endphp
```

### Steps:

1. ✅ Update `config/sidebar.php` dengan menu baru
2. ✅ Replace sidebar blade dengan versi dinamis
3. ✅ Test dengan different users
4. ✅ Remove old hardcoded logic

---

## 📊 Benefits

| Feature | Before | After |
|---------|--------|-------|
| Menu Location | Hardcoded in Blade | Config file |
| Logic | Mixed in view | Service layer |
| Reusability | Copy-paste | Single source |
| Testing | Hard | Easy |
| Maintenance | Difficult | Simple |
| Extensibility | Limited | Flexible |

---

## 🎓 Best Practices

1. **Always use helpers** in views instead of direct service calls
2. **Keep config clean** - one menu per array item
3. **Use route names** instead of hardcoded URLs
4. **Test with different roles** before deploying
5. **Document custom menus** in comments
6. **Use consistent naming** for divisions
7. **Cache menus** for production (optional)

---

## 🔮 Future Enhancements

### Database-Driven Menus (Optional)

```php
// Create migration
Schema::create('menus', function (Blueprint $table) {
    $table->id();
    $table->string('label');
    $table->string('route');
    $table->text('icon');
    $table->json('divisions');
    $table->json('roles');
    $table->integer('order')->default(0);
    $table->boolean('active')->default(true);
    $table->timestamps();
});

// Update MenuService to read from DB
public function getMenusForUser(string $type = 'petugas'): array
{
    // Try database first
    $dbMenus = Menu::where('type', $type)
        ->where('active', true)
        ->orderBy('order')
        ->get()
        ->toArray();
    
    // Fallback to config
    if (empty($dbMenus)) {
        $dbMenus = config("sidebar.{$type}_menus", []);
    }
    
    // ... rest of logic
}
```

### Permission-Based Access

```php
// Add to menu config
'permissions' => ['view-washing', 'edit-washing'],

// Check in service
if (!$user->hasAnyPermission($menu['permissions'])) {
    return false;
}
```

### Menu Caching

```php
public function getMenusForUser(string $type = 'petugas'): array
{
    $cacheKey = "menus.{$type}." . auth()->id();
    
    return Cache::remember($cacheKey, 3600, function () use ($type) {
        // ... menu logic
    });
}
```

---

## 📞 Support

Jika ada pertanyaan atau issue:
1. Check troubleshooting section
2. Review config file
3. Test dengan `php artisan tinker`
4. Check logs di `storage/logs/laravel.log`

---

**Version**: 3.0 - Dynamic Sidebar System  
**Last Updated**: May 2026  
**Status**: ✅ Production Ready  
**Maintainability**: ⭐⭐⭐⭐⭐
