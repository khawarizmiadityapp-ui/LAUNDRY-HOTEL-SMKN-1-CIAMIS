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
    public function test_can_create_pos_order(): void
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
            'kasir_name' => $cs->name,
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

    /** @test */
    public function test_unpaid_transaction_renders_as_invoice_on_receipt(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = Customer::create([
            'nama' => 'Asep',
            'no_hp' => '081234567891',
            'alamat' => 'Ciamis'
        ]);

        $transaksi = \App\Models\Transaksi::create([
            'transaksi_code' => 'TRX-TEST-INVOICE',
            'customer_id' => $customer->id,
            'customer_name' => $customer->nama,
            'customer_phone' => $customer->no_hp,
            'service_type' => 'kiloan',
            'weight' => 3,
            'price_per_kg' => 6000,
            'total_price' => 18000,
            'payment_status' => 'belum_bayar',
            'payment_method' => 'tunai',
            'status' => 'diterima',
            'user_id' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('pos.nota', $transaksi->id));

        $response->assertStatus(200);
        $response->assertSee('INVOICE / TAGIHAN');
        $response->assertSee('No. Invoice');
        $response->assertSee('INVOICE (BELUM LUNAS)');
        $response->assertSee('TOTAL TAGIHAN');
    }

    /** @test */
    public function test_paid_transaction_renders_as_struk_on_receipt(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $customer = Customer::create([
            'nama' => 'Dewi',
            'no_hp' => '081234567892',
            'alamat' => 'Ciamis'
        ]);

        $transaksi = \App\Models\Transaksi::create([
            'transaksi_code' => 'TRX-TEST-LUNAS',
            'customer_id' => $customer->id,
            'customer_name' => $customer->nama,
            'customer_phone' => $customer->no_hp,
            'service_type' => 'kiloan',
            'weight' => 2,
            'price_per_kg' => 6000,
            'total_price' => 12000,
            'payment_status' => 'lunas',
            'payment_method' => 'qris',
            'status' => 'selesai',
            'user_id' => $admin->id,
        ]);

        $response = $this->actingAs($admin)->get(route('pos.nota', $transaksi->id));

        $response->assertStatus(200);
        $response->assertSee('STRUK PEMBAYARAN');
        $response->assertSee('No. Struk');
        $response->assertSee('LUNAS');
    }
}
