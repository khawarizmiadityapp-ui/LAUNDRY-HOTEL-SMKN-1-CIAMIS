<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Setting;
use App\Models\DailyTarget;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomMonthlyTargetTest extends TestCase
{
    use RefreshDatabase;

    public function test_custom_monthly_target_and_fallback_logic()
    {
        // 1. Set global target default
        Setting::setValue('target_monthly', 50000000);

        // 2. Set custom target for September 2026
        Setting::setValue('target_monthly_2026_09', 65000000);

        // Assert September 2026 returns custom target
        $this->assertEquals(65000000, DailyTarget::getMonthlyTarget('2026-09-10'));
        $this->assertTrue(DailyTarget::isMonthCustomTarget('2026-09-01'));

        // Assert October 2026 falls back to global default
        $this->assertEquals(50000000, DailyTarget::getMonthlyTarget('2026-10-10'));
        $this->assertFalse(DailyTarget::isMonthCustomTarget('2026-10-01'));
    }

    public function test_admin_can_update_target_specifically_for_a_month()
    {
        $admin = User::factory()->create([
            'role' => 'super_admin',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.update_target'), [
            'target' => 75000000,
            'target_type' => 'bulan_spesifik',
            'target_month' => '2026-11',
            'workdays_mode' => 'senin_jumat',
        ]);

        $response->assertRedirect();
        $this->assertEquals(75000000, DailyTarget::getMonthlyTarget('2026-11-01'));
        $this->assertTrue(DailyTarget::isMonthCustomTarget('2026-11-01'));
    }

    public function test_admin_can_view_laporan_keuangan_for_specific_month()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        Setting::setValue('target_monthly_2026_08', 40000000);

        $response = $this->actingAs($admin)->get(route('admin.laporan_keuangan.index', [
            'filter' => 'bulanan',
            'bulan' => '2026-08',
        ]));

        $response->assertStatus(200);
        $response->assertSee('40.000.000');
        $response->assertSee('Target Khusus Bulan Ini');
    }
}
