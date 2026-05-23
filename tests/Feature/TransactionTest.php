<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Transaksi;
use App\Models\Customer;
use App\Models\ServicePrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        // Create admin user
        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create service prices
        ServicePrice::create([
            'service_name' => 'Regular',
            'service_type' => 'regular',
            'price_per_kg' => 6000,
        ]);

        ServicePrice::create([
            'service_name' => 'Express',
            'service_type' => 'express',
            'price_per_kg' => 7000,
        ]);
    }

    public function test_admin_can_create_transaction()
    {
        $response = $this->actingAs($this->admin)->post('/admin/transaksi', [
            'customer_name' => 'Test Customer',
            'customer_phone' => '08123456789',
            'service_type' => 'regular',
            'weight' => 5.5,
            'payment_method' => 'tunai',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transaksi', [
            'customer_name' => 'Test Customer',
            'customer_phone' => '08123456789',
            'service_type' => 'regular',
            'weight' => 5.5,
            'payment_status' => 'belum_bayar',
        ]);
    }

    public function test_admin_can_update_transaction_status()
    {
        $transaction = Transaksi::factory()->create([
            'status' => 'diterima',
            'payment_status' => 'belum_bayar',
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/transaksi/{$transaction->id}/status", [
            'status' => 'dicuci',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transaksi', [
            'id' => $transaction->id,
            'status' => 'dicuci',
        ]);
    }

    public function test_admin_can_update_payment_status()
    {
        $transaction = Transaksi::factory()->create([
            'status' => 'selesai',
            'payment_status' => 'belum_bayar',
        ]);

        $response = $this->actingAs($this->admin)->patch("/admin/transaksi/{$transaction->id}/payment", [
            'payment_status' => 'lunas',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('transaksi', [
            'id' => $transaction->id,
            'payment_status' => 'lunas',
        ]);
    }

    public function test_admin_can_delete_transaction()
    {
        $transaction = Transaksi::factory()->create();

        $response = $this->actingAs($this->admin)->delete("/admin/transaksi/{$transaction->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('transaksi', [
            'id' => $transaction->id,
        ]);
    }

    public function test_transaction_requires_validation()
    {
        $response = $this->actingAs($this->admin)->post('/admin/transaksi', [
            'customer_name' => '',
            'customer_phone' => '',
            'service_type' => 'invalid',
            'weight' => -1,
        ]);

        $response->assertSessionHasErrors();
    }
}
