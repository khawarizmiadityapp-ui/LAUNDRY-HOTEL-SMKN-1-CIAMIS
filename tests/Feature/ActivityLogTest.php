<?php

namespace Tests\Feature;

use App\Models\Transaksi;
use App\Models\Inventory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function transaksi_model_can_log_activity()
    {
        $user = User::factory()->create();
        $transaksi = Transaksi::factory()->create(['user_id' => $user->id]);

        $log = $transaksi->logActivity('Test activity', 'custom', ['test' => 'data']);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'Test activity',
            'subject_type' => get_class($transaksi),
            'subject_id' => $transaksi->id,
            'event' => 'custom',
        ]);
    }

    /** @test */
    public function inventory_model_can_log_activity()
    {
        $inventory = Inventory::factory()->create();

        $log = $inventory->logActivity('Inventory updated', 'update', ['quantity' => 10]);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'Inventory updated',
            'subject_type' => get_class($inventory),
            'subject_id' => $inventory->id,
            'event' => 'update',
        ]);
    }
}
