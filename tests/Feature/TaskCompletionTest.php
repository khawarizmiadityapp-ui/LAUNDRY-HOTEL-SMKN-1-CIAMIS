<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskCompletionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_washing_staff_can_complete_washing_task(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'division' => 'washing',
        ]);

        \App\Models\Inventory::create([
            'name' => 'Detergent Liquid',
            'category' => 'detergent',
            'unit_type' => 'botol',
            'capacity_per_unit' => 1000,
            'unit_of_measurement' => 'ml',
            'stock_units' => 5,
            'stock_subunits' => 500,
            'quantity' => 5,
            'minimum_stock' => 1,
            'type' => 'consumable',
        ]);

        \App\Models\Inventory::create([
            'name' => 'Fragrance Lavender',
            'category' => 'fragrance',
            'unit_type' => 'botol',
            'capacity_per_unit' => 1000,
            'unit_of_measurement' => 'ml',
            'stock_units' => 5,
            'stock_subunits' => 500,
            'quantity' => 5,
            'minimum_stock' => 1,
            'type' => 'consumable',
        ]);
        
        $customer = Customer::create([
            'nama' => 'Budi',
            'no_hp' => '081234567890',
            'alamat' => ''
        ]);

        $transaksi = Transaksi::create([
            'transaksi_code' => 'TRX-TEST-001',
            'user_id' => $staff->id,
            'customer_id' => $customer->id,
            'customer_name' => $customer->nama,
            'customer_phone' => $customer->no_hp,
            'service_type' => 'regular',
            'weight' => 2,
            'price_per_kg' => 0,
            'total_price' => 10000,
            'status' => 'diterima',
            'payment_status' => 'belum_bayar',
            'payment_method' => 'tunai',
        ]);
        
        $task = $transaksi->tasks()->create([
            'stage' => 'washing',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($staff)->post(route('petugas_piket.tasks.complete', $transaksi->id), [
            'stage' => 'washing',
            'petugas_name' => 'Baim'
        ]);

        $response->assertStatus(302); // Redirect back
        
        $this->assertDatabaseHas('laundry_tasks', [
            'id' => $task->id,
            'status' => 'completed',
        ]);
        
        $this->assertDatabaseHas('transaksi', [
            'id' => $transaksi->id,
            'status' => 'disetrika'
        ]);
    }
}
