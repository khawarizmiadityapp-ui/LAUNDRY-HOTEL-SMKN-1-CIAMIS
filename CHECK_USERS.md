# 🔍 CHECK USERS - Debugging Guide

## Problem
1. Admin jadi forbidden pas staff login
2. Login multiple staff di browser berbeda malah jadi satu role aja

---

## Solution 1: Check Current User

### Step 1: Check who is logged in
```bash
php artisan tinker
```

```php
// Check current authenticated user
auth()->user();

// If null, no one is logged in
// If not null, check the role and email
```

### Step 2: List all users
```php
User::all(['id', 'name', 'email', 'role', 'division']);
```

**Expected users**:
- `admin@laundry.com` - role: `admin`
- `kasir@laundry.com` - role: `staff`, division: `customer_service`
- `washing@laundry.com` - role: `staff`, division: `washing`
- `setrika@laundry.com` - role: `staff`, division: `setrika`
- `packing@laundry.com` - role: `staff`, division: `packing`
- `inventory@laundry.com` - role: `staff`, division: `inventory`

---

## Solution 2: Clear All Sessions

### Step 1: Clear cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan session:clear
```

### Step 2: Clear browser cookies
1. Open browser DevTools (F12)
2. Go to Application tab
3. Clear all cookies for localhost
4. Close all browser tabs

### Step 3: Logout all users
```bash
php artisan tinker
```

```php
// Clear online staff cache
Cache::forget('online_staff_users');

// Or clear all cache
Cache::flush();
```

---

## Solution 3: Test Multiple Users

### Method 1: Different Browsers
1. **Chrome**: Login as `admin@laundry.com`
2. **Firefox**: Login as `washing@laundry.com`
3. **Edge**: Login as `setrika@laundry.com`
4. **Opera**: Login as `packing@laundry.com`

### Method 2: Incognito/Private Mode
1. **Chrome Normal**: Login as `admin@laundry.com`
2. **Chrome Incognito 1**: Login as `washing@laundry.com`
3. **Chrome Incognito 2**: Login as `setrika@laundry.com`
4. **Chrome Incognito 3**: Login as `packing@laundry.com`

**Note**: Each incognito window is a separate session!

### Method 3: Browser Profiles (Best)
1. Create Chrome profiles:
   - Profile 1: Admin
   - Profile 2: Washing
   - Profile 3: Setrika
   - Profile 4: Packing
   - Profile 5: Inventory

2. Login each profile with different user

---

## Solution 4: Check User Roles

### If admin is forbidden:
```bash
php artisan tinker
```

```php
// Check admin user
$admin = User::where('email', 'admin@laundry.com')->first();
dd([
    'email' => $admin->email,
    'role' => $admin->role,  // Should be 'admin'
    'division' => $admin->division,  // Should be null
]);

// If role is not 'admin', fix it:
$admin->update(['role' => 'admin', 'division' => null]);
```

### If staff can't login:
```php
// Check staff user
$staff = User::where('email', 'washing@laundry.com')->first();
dd([
    'email' => $staff->email,
    'role' => $staff->role,  // Should be 'staff'
    'division' => $staff->division,  // Should be 'washing'
]);

// If role or division is wrong, fix it:
$staff->update(['role' => 'staff', 'division' => 'washing']);
```

---

## Solution 5: Create Test Users

If users don't exist, create them:

```bash
php artisan tinker
```

```php
// Create admin
User::create([
    'name' => 'Administrator',
    'email' => 'admin@laundry.com',
    'password' => bcrypt('password'),
    'role' => 'admin',
    'division' => null,
]);

// Create staff users
$staff = [
    ['name' => 'Petugas Kasir', 'email' => 'kasir@laundry.com', 'division' => 'customer_service'],
    ['name' => 'Petugas Washing', 'email' => 'washing@laundry.com', 'division' => 'washing'],
    ['name' => 'Petugas Setrika', 'email' => 'setrika@laundry.com', 'division' => 'setrika'],
    ['name' => 'Petugas Packing', 'email' => 'packing@laundry.com', 'division' => 'packing'],
    ['name' => 'Petugas Inventory', 'email' => 'inventory@laundry.com', 'division' => 'inventory'],
];

foreach ($staff as $s) {
    User::create([
        'name' => $s['name'],
        'email' => $s['email'],
        'password' => bcrypt('password'),
        'role' => 'staff',
        'division' => $s['division'],
    ]);
}
```

---

## Solution 6: Debug Online Staff Tracking

### Check cache:
```bash
php artisan tinker
```

```php
// Check online staff cache
$onlineStaff = Cache::get('online_staff_users', []);
dd($onlineStaff);

// Expected output:
// [
//     1 => 1715443200,  // user_id => timestamp
//     2 => 1715443201,
//     3 => 1715443202,
// ]

// Get user details
$userIds = array_keys($onlineStaff);
$users = User::whereIn('id', $userIds)->get(['id', 'name', 'email', 'role', 'division']);
dd($users);
```

### Clear online staff cache:
```php
Cache::forget('online_staff_users');
```

---

## Common Issues

### Issue 1: "Admin jadi forbidden"
**Cause**: You're logged in as staff, not admin  
**Solution**: Logout and login as `admin@laundry.com`

### Issue 2: "Multiple users jadi satu role"
**Cause**: Same browser session  
**Solution**: Use different browsers or incognito windows

### Issue 3: "Staff tidak muncul di widget"
**Cause**: Cache not updated or user not logged in  
**Solution**: 
1. Clear cache
2. Login as staff users
3. Check admin sidebar

### Issue 4: "Session conflict"
**Cause**: Laravel session uses cookies, shared across tabs  
**Solution**: Use different browsers or profiles

---

## Testing Checklist

- [ ] Clear all caches
- [ ] Clear browser cookies
- [ ] Logout all users
- [ ] Check user roles in database
- [ ] Login as admin in Chrome
- [ ] Login as washing in Firefox
- [ ] Login as setrika in Edge
- [ ] Check admin sidebar shows 2 staff online
- [ ] Login more staff users
- [ ] Check admin sidebar shows all staff online

---

## Quick Fix Commands

```bash
# Clear everything
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Check users
php artisan tinker
User::all(['id', 'name', 'email', 'role', 'division']);

# Clear online staff
Cache::forget('online_staff_users');

# Fix admin role
$admin = User::where('email', 'admin@laundry.com')->first();
$admin->update(['role' => 'admin']);

# Fix staff roles
User::where('email', 'washing@laundry.com')->update(['role' => 'staff', 'division' => 'washing']);
User::where('email', 'setrika@laundry.com')->update(['role' => 'staff', 'division' => 'setrika']);
User::where('email', 'packing@laundry.com')->update(['role' => 'staff', 'division' => 'packing']);
User::where('email', 'inventory@laundry.com')->update(['role' => 'staff', 'division' => 'inventory']);
User::where('email', 'kasir@laundry.com')->update(['role' => 'staff', 'division' => 'customer_service']);
```

---

**Date**: May 11, 2026  
**Status**: Debugging Guide  
**Priority**: HIGH
