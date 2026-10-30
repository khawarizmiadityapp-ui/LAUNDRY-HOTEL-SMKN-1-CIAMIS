<?php

namespace Tests\Feature;

use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_inventory_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        Inventory::create([
            'name' => 'Detergent Soft Touch',
            'category' => 'detergent',
            'unit_type' => 'botol',
            'capacity_per_unit' => 1000,
            'unit_of_measurement' => 'ml',
            'stock_units' => 10,
            'stock_subunits' => 500,
            'quantity' => 10,
            'minimum_stock' => 5,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.inventory.index'));

        $response->assertStatus(200);
        $response->assertSee('Detergent Soft Touch');
        $response->assertSee('Edit Data Barang');
    }

    public function test_admin_sidebar_displays_inventory_menu(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Inventory');
    }

    public function test_admin_can_update_inventory_item(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $item = Inventory::create([
            'name' => 'Old Detergent Name',
            'category' => 'detergent',
            'unit_type' => 'botol',
            'capacity_per_unit' => 1000,
            'unit_of_measurement' => 'ml',
            'stock_units' => 5,
            'stock_subunits' => 200,
            'quantity' => 5,
            'minimum_stock' => 3,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.inventory.update_item', $item->id), [
            'name' => 'Updated Detergent Ultra Clean',
            'category' => 'detergent',
            'unit_type' => 'botol',
            'capacity_per_unit' => 2000,
            'stock_subunits' => 400,
            'quantity' => 15,
            'minimum_stock' => 8,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('inventories', [
            'id' => $item->id,
            'name' => 'Updated Detergent Ultra Clean',
            'capacity_per_unit' => 2000,
            'quantity' => 15,
            'stock_units' => 15,
            'stock_subunits' => 400,
            'minimum_stock' => 8,
        ]);
    }

    public function test_admin_can_update_pcs_unit_type(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $item = Inventory::create([
            'name' => 'Hanger Standar',
            'category' => 'hanger',
            'unit_type' => 'pcs',
            'capacity_per_unit' => 1,
            'unit_of_measurement' => 'pcs',
            'stock_units' => 50,
            'stock_subunits' => 0,
            'quantity' => 50,
            'minimum_stock' => 10,
        ]);

        $response = $this->actingAs($admin)->put(route('admin.inventory.update_item', $item->id), [
            'name' => 'Hanger Plastik Hitam Tebal',
            'category' => 'hanger',
            'unit_type' => 'pcs',
            'capacity_per_unit' => 1,
            'stock_subunits' => 0,
            'quantity' => 120,
            'minimum_stock' => 20,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('inventories', [
            'id' => $item->id,
            'name' => 'Hanger Plastik Hitam Tebal',
            'unit_type' => 'pcs',
            'unit_of_measurement' => 'pcs',
            'stock_units' => 120,
            'minimum_stock' => 20,
        ]);
    }

    public function test_staff_with_inventory_division_can_update_inventory(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'division' => 'inventory',
        ]);

        $item = Inventory::create([
            'name' => 'Pewangi Lavender',
            'category' => 'fragrance',
            'unit_type' => 'botol',
            'capacity_per_unit' => 1000,
            'unit_of_measurement' => 'ml',
            'stock_units' => 8,
            'stock_subunits' => 300,
            'quantity' => 8,
            'minimum_stock' => 4,
        ]);

        $response = $this->actingAs($staff)->put(route('petugas_piket.inventory.update', $item->id), [
            'name' => 'Pewangi French Lavender Supreme',
            'category' => 'Fragrance',
            'unit_type' => 'botol',
            'capacity_per_unit' => 1000,
            'stock_subunits' => 500,
            'quantity' => 12,
            'minimum_stock' => 6,
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('inventories', [
            'id' => $item->id,
            'name' => 'Pewangi French Lavender Supreme',
            'quantity' => 12,
            'stock_units' => 12,
            'minimum_stock' => 6,
        ]);
    }
}
