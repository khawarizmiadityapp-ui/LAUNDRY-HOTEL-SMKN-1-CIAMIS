<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosOrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_pos_order(): void
    {
        $cs = User::factory()->create([
            'role' => 'staff',
            'division' => 'customer_service',
        ]);

        $customer = Customer::create([
            'nama' => 'Budi',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Mawar'
        ]);
        
        $layanan = Layanan::create([
            'nama' => 'Cuci Kemeja',
            'kategori' => 'satuan',
            'harga' => 5000,
            'status' => true,
            'needs_washing' => true,
            'needs_ironing' => true,
            'needs_packing' => true,
        ]);

        $response = $this->actingAs($cs)->post(route('pos.order.store'), [
            'customer_id' => $customer->id,
            'items' => [
                [
                    'layanan_id' => $layanan->id,
                    'qty' => 2
                ]
            ],
            'payment_method' => 'tunai',
            'payment_status' => 'belum_bayar',
            'notes' => 'Tolong cepat'
        ]);

        $response->assertStatus(302); // Redirects to nota
        $this->assertDatabaseHas('transaksi', [
            'customer_id' => $customer->id,
            'total_price' => 10000,
            'status' => 'diterima'
        ]);
        
        $this->assertDatabaseHas('laundry_tasks', [
           'stage' => 'washing',
           'status' => 'pending'
        ]);
    }
}
