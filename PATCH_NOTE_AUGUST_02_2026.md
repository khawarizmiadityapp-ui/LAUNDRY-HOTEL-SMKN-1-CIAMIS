# Patch Notes - August 02, 2026

## 🛡️ Security, Access Control & Performance Update

This update improves security in file uploads, strengthens division-based access controls for staff tasks, and optimizes dashboard activity notification query performance.

---

### 🔒 Security & Access Control Enhancements

1. **Secure File Extension Resolution (`AdminController.php`)**
   * **[FIXED]** Replaced `$file->getClientOriginalExtension()` with `$file->extension()` for hero and logo settings uploads.
   * **[IMPROVED]** Uses server-side MIME-type inspection rather than trusting user-provided client extensions, preventing file extension spoofing.

2. **Staff Division Access Control (`PetugasController.php`)**
   * **[ADDED]** Enforced `$this->ensureStaffDivisionAccess([$request->stage])` check inside `completeTask()`.
   * **[IMPROVED]** Prevents staff members from completing task stages outside of their designated division permissions.

---

### 🚀 Performance & UI Optimizations

1. **Activity Log Caching & Eager Loading (`layouts/admin.blade.php`)**
   * **[OPTIMIZED]** Cached recent activity logs (`dashboard_recent_activities_limit_5`) for 60 seconds.
   * **[ADDED]** Eager loading `with('causer')` to eliminate potential N+1 database queries on admin header layout renders.
   * **[CLEANUP]** Removed duplicate query execution in the notifications dropdown view template.
