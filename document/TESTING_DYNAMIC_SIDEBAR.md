# 🧪 Testing Dynamic Sidebar System

## Quick Test Commands

### 1. Test Autoload
```bash
composer dump-autoload
php artisan config:clear
php artisan cache:clear
```

### 2. Test Helpers in Tinker
```bash
php artisan tinker
```

```php
// Test get_user_menus()
$user = User::first();
auth()->login($user);
$menus = get_user_menus('petugas');
dd($menus);

// Test get_division_label()
get_division_label('washing');        // Should return: "Washing"
get_division_label('customer_service'); // Should return: "Customer Service"
get_division_label('kasir');          // Should return: "Customer Service" (alias)

// Test get_user_initials()
get_user_initials('John Doe');        // Should return: "JD"
get_user_initials('Admin User');      // Should return: "AU"

// Test format_rupiah()
format_rupiah(50000);                 // Should return: "Rp 50.000"
format_rupiah(1500000);               // Should return: "Rp 1.500.000"

// Test status helpers
status_badge_class('completed');      // Should return: "bg-green-100 text-green-700"
status_label('in_progress');          // Should return: "Dalam Proses"
```

### 3. Test MenuService
```php
$service = app(\App\Services\MenuService::class);

// Test normalizeDivision()
$service->normalizeDivision('kasir');     // Should return: 'customer_service'
$service->normalizeDivision('cs');        // Should return: 'customer_service'
$service->normalizeDivision('ironing');   // Should return: 'setrika'
$service->normalizeDivision('washing');   // Should return: 'washing'

// Test getDivisionLabel()
$service->getDivisionLabel('washing');    // Should return: 'Washing'
$service->getDivisionLabel('setrika');    // Should return: 'Setrika'

// Test getUserInitials()
$service->getUserInitials('Budi Santoso'); // Should return: 'BS'

// Test getBrandInfo()
$service->getBrandInfo();                 // Should return array with name & tagline
```

### 4. Test Access Control

```php
// Test Admin Access (should see ALL menus)
$admin = User::where('role', 'admin')->first();
auth()->login($admin);
$menus = get_user_menus('petugas');
count($menus);  // Should be 7 (all menus)

// Test Washing Staff (should see: Dashboard, Washing, History)
$washing = User::where('division', 'washing')->where('role', 'staff')->first();
auth()->login($washing);
$menus = get_user_menus('petugas');
count($menus);  // Should be 3

// Test Packing Staff (should see: Dashboard, Packing, History)
$packing = User::where('division', 'packing')->where('role', 'staff')->first();
auth()->login($packing);
$menus = get_user_menus('petugas');
count($menus);  // Should be 3

// Test CS Staff (should see: Dashboard, Customer Service, History)
$cs = User::where('division', 'customer_service')->where('role', 'staff')->first();
auth()->login($cs);
$menus = get_user_menus('petugas');
count($menus);  // Should be 3
```

---

## Manual Browser Testing

### Setup Test Users

```sql
-- Create test users if not exist
INSERT INTO users (name, email, password, role, division, created_at, updated_at) VALUES
('Admin User', 'admin@test.com', '$2y$12$...', 'admin', NULL, NOW(), NOW()),
('Washing Staff', 'washing@test.com', '$2y$12$...', 'staff', 'washing', NOW(), NOW()),
('Setrika Staff', 'setrika@test.com', '$2y$12$...', 'staff', 'setrika', NOW(), NOW()),
('Packing Staff', 'packing@test.com', '$2y$12$...', 'staff', 'packing', NOW(), NOW()),
('CS Staff', 'cs@test.com', '$2y$12$...', 'staff', 'customer_service', NOW(), NOW()),
('Inventory Staff', 'inventory@test.com', '$2y$12$...', 'staff', 'inventory', NOW(), NOW());

-- Password for all: 'password'
```

### Test Cases

| User | Expected Menus | Count |
|------|---------------|-------|
| admin@test.com | Dashboard, CS, Washing, Setrika, Packing, Inventory, History | 7 |
| washing@test.com | Dashboard, Washing, History | 3 |
| setrika@test.com | Dashboard, Setrika, History | 3 |
| packing@test.com | Dashboard, Packing, History | 3 |
| cs@test.com | Dashboard, Customer Service, History | 3 |
| inventory@test.com | Dashboard, Inventory, History | 3 |

### Browser Test Steps

1. **Login as washing@test.com**
   - ✅ Should see: Dashboard, Washing, History
   - ✅ Should NOT see: Customer Service, Setrika, Packing, Inventory
   - ✅ Division label should show: "Washing"
   - ✅ Active menu should be highlighted

2. **Login as admin@test.com**
   - ✅ Should see ALL 7 menus
   - ✅ Division label should show: "Staff" or "Admin"
   - ✅ All menus should be clickable

3. **Test Active State**
   - ✅ Click "Washing" menu
   - ✅ Menu should be highlighted with blue background
   - ✅ Icon should be white
   - ✅ Other menus should be gray

4. **Test Responsive**
   - ✅ Resize browser to mobile size
   - ✅ Sidebar should collapse (if mobile toggle implemented)
   - ✅ All menus should still be accessible

---

## Automated Testing (Optional)

### Feature Test Example

```php
// tests/Feature/MenuServiceTest.php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Services\MenuService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MenuServiceTest extends TestCase
{
    use RefreshDatabase;

    protected MenuService $menuService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->menuService = app(MenuService::class);
    }

    /** @test */
    public function admin_can_see_all_menus()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $menus = $this->menuService->getMenusForUser('petugas');

        $this->assertCount(7, $menus);
    }

    /** @test */
    public function washing_staff_sees_only_washing_menus()
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'division' => 'washing'
        ]);
        $this->actingAs($staff);

        $menus = $this->menuService->getMenusForUser('petugas');

        $this->assertCount(3, $menus);
        $labels = collect($menus)->pluck('label')->toArray();
        $this->assertContains('Dashboard', $labels);
        $this->assertContains('Washing', $labels);
        $this->assertContains('History', $labels);
    }

    /** @test */
    public function division_normalization_works()
    {
        $this->assertEquals('customer_service', $this->menuService->normalizeDivision('kasir'));
        $this->assertEquals('customer_service', $this->menuService->normalizeDivision('cs'));
        $this->assertEquals('setrika', $this->menuService->normalizeDivision('ironing'));
    }

    /** @test */
    public function division_label_returns_correct_label()
    {
        $this->assertEquals('Washing', $this->menuService->getDivisionLabel('washing'));
        $this->assertEquals('Customer Service', $this->menuService->getDivisionLabel('customer_service'));
        $this->assertEquals('Customer Service', $this->menuService->getDivisionLabel('kasir')); // alias
    }

    /** @test */
    public function user_initials_generated_correctly()
    {
        $this->assertEquals('JD', $this->menuService->getUserInitials('John Doe'));
        $this->assertEquals('BS', $this->menuService->getUserInitials('Budi Santoso'));
        $this->assertEquals('A', $this->menuService->getUserInitials('Admin'));
    }
}
```

### Run Tests

```bash
php artisan test --filter=MenuServiceTest
```

---

## Troubleshooting Tests

### Issue: Helper function not found

**Error**: `Call to undefined function get_user_menus()`

**Solution**:
```bash
composer dump-autoload
php artisan config:clear
```

### Issue: Menus empty

**Error**: `$menus` returns empty array

**Check**:
```php
// In tinker
auth()->check();  // Should be true
auth()->user()->division;  // Should have value
config('sidebar.petugas_menus');  // Should return array
```

### Issue: All users see all menus

**Cause**: Role check not working

**Check**:
```php
auth()->user()->role;  // Should be 'staff' not 'admin'
```

### Issue: Active state not working

**Cause**: Route name mismatch

**Check**:
```php
request()->route()->getName();  // Check current route name
// Should match pattern in config 'active' array
```

---

## Performance Testing

### Test Menu Loading Time

```php
// In tinker
$start = microtime(true);
$menus = get_user_menus('petugas');
$end = microtime(true);
echo "Time: " . ($end - $start) . " seconds\n";
// Should be < 0.01 seconds
```

### Test with Many Menus

```php
// Add 50 menus to config
// Test loading time
// Should still be < 0.05 seconds
```

---

## Checklist

### Before Deployment

- [ ] All helpers work in tinker
- [ ] Admin sees all menus
- [ ] Staff sees only their division menus
- [ ] Active state highlights correctly
- [ ] Division labels display correctly
- [ ] User initials generate correctly
- [ ] No PHP errors in logs
- [ ] Config cache cleared
- [ ] Autoload refreshed
- [ ] Browser testing passed
- [ ] Mobile responsive works

### After Deployment

- [ ] Monitor error logs
- [ ] Check user feedback
- [ ] Verify menu access control
- [ ] Test with real users
- [ ] Performance monitoring

---

## Test Results Template

```
Date: ___________
Tester: ___________

| Test Case | Expected | Actual | Status |
|-----------|----------|--------|--------|
| Admin sees all menus | 7 menus | ___ | ☐ Pass ☐ Fail |
| Washing staff sees 3 menus | 3 menus | ___ | ☐ Pass ☐ Fail |
| Division label correct | "Washing" | ___ | ☐ Pass ☐ Fail |
| Active state works | Blue highlight | ___ | ☐ Pass ☐ Fail |
| Helpers work | No errors | ___ | ☐ Pass ☐ Fail |
| Mobile responsive | Works | ___ | ☐ Pass ☐ Fail |

Notes:
_______________________________________________________
_______________________________________________________
```

---

**Last Updated**: May 2026  
**Status**: Ready for Testing  
**Priority**: High
