# Patch Notes - October 30, 2026

## 📦 Inventory Management, Item Edit Modal & Sidebar Restoration Update

This update resolves the missing inventory menu reported after deployment, introduces full editing capabilities for supply items in both the Admin and Staff Piket portals, and expands category visibility.

---

### 🚀 Key Highlights & Enhancements

#### 1. Navigation & Access Control (`config/sidebar.php`)
* **[RESTORED]** Uncommented and restored the `Inventory` menu link under `admin_menus`.
* **[ACCESSIBILITY]** Administrators can now seamlessly navigate to the Supply Management dashboard (`/admin/inventory`) directly from the primary sidebar navigation.

#### 2. Inventory Item Edit Capability (`routes/web.php`, `InventoryController.php`, `PetugasController.php`)
* **[ADDED]** Added RESTful update routes:
  * `PUT /admin/inventory/{id}` (`admin.inventory.update_item`)
  * `PUT /petugas/inventory/{id}` (`petugas_piket.inventory.update`)
* **[ADDED]** Implemented `update(Request $request, $id)` in `InventoryController` and `updateInventory(Request $request, $id)` in `PetugasController`.
* **[VALIDATION]** Enforced strict server-side validation:
  * `name`: string, required, max:255
  * `category`: required, formatted to lowercase
  * `unit_type`: required (`botol`, `sachet`, `pcs`)
  * `capacity_per_unit`: integer, min:1
  * `stock_subunits`: integer, min:0
  * `quantity`: integer, min:0
  * `minimum_stock`: integer, min:0
* **[AUTOMATION]** Auto-determines `unit_of_measurement` (`pcs` vs `ml`) and synchronizes `stock_units` and `quantity`.
* **[OBSERVABILITY]** Integrated with `ErrorLoggingService` for full failure logging and traceability.

#### 3. User Interface & Modals (`admin/inventory/index.blade.php`, `petugas_piket/inventory/index.blade.php`)
* **[ADDED]** Modern `#edit-item-modal` styled with Tailwind CSS to edit item metadata without re-creating records.
* **[ADDED]** Edit buttons (`pencil` icon) across all inventory card types:
  * Premium Detergents
  * Fragrance Library
  * Essential Hangers
  * Perlengkapan Lainnya & Packaging
* **[ADDED]** Dynamic JavaScript helpers (`openEditModal`, `closeEditModal`, `toggleEditUnitFields`) that prepopulate form data and dynamically adapt input fields depending on whether the item is liquid (`ml`) or discrete (`pcs`).
* **[FIXED]** Added a dedicated **"Perlengkapan Lainnya & Packaging"** section in the admin inventory view so items with non-standard categories (e.g. `packaging`, `lainnya`) are no longer omitted.

#### 4. Automated Testing & Verification (`tests/Feature/InventoryManagementTest.php`)
* **[ADDED]** Automated feature test suite covering:
  * `admin_can_view_inventory_page`
  * `admin_sidebar_displays_inventory_menu`
  * `admin_can_update_inventory_item`
  * `admin_can_update_pcs_unit_type`
  * `staff_with_inventory_division_can_update_inventory`
* **[VERIFIED]** Test suite results: **84 passed (277 assertions)** across all project test suites.

---

### 📂 Modified & Created Files

* `config/sidebar.php`
* `routes/web.php`
* `app/Http/Controllers/InventoryController.php`
* `app/Http/Controllers/PetugasController.php`
* `resources/views/admin/inventory/index.blade.php`
* `resources/views/petugas_piket/inventory/index.blade.php`
* `tests/Feature/InventoryManagementTest.php`
* `PATCH_NOTE_OCTOBER_30_2026.md`
