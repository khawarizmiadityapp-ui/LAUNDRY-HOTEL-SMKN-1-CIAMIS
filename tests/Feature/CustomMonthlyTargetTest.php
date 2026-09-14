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

    public function test_admin_can_update_annual_target_with_equal_split()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.update_target'), [
            'target' => 120000000,
            'target_type' => 'tahunan',
            'target_year' => 2026,
            'tahunan_mode' => 'bagi_rata',
        ]);

        $response->assertRedirect();
        $this->assertEquals(120000000, DailyTarget::getAnnualTarget(2026));
        $this->assertEquals(10000000, DailyTarget::getMonthlyTarget('2026-05-01'));
    }

    public function test_admin_can_update_annual_target_with_custom_monthly_targets()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $monthlyTargets = [
            1 => 8000000,
            2 => 8000000,
            3 => 10000000,
            4 => 10000000,
            5 => 12000000,
            6 => 12000000,
            7 => 15000000,
            8 => 15000000,
            9 => 10000000,
            10 => 10000000,
            11 => 10000000,
            12 => 20000000,
        ]; // Total: 140.000.000

        $response = $this->actingAs($admin)->post(route('admin.update_target'), [
            'target' => 140000000,
            'target_type' => 'tahunan',
            'target_year' => 2026,
            'tahunan_mode' => 'kustom_bulan',
            'monthly_targets' => $monthlyTargets,
        ]);

        $response->assertRedirect();
        $this->assertEquals(140000000, DailyTarget::getAnnualTarget(2026));
        $this->assertEquals(8000000, DailyTarget::getMonthlyTarget('2026-01-01'));
        $this->assertEquals(12000000, DailyTarget::getMonthlyTarget('2026-05-01'));
        $this->assertEquals(20000000, DailyTarget::getMonthlyTarget('2026-12-01'));
    }

    public function test_admin_cannot_submit_negative_target_values()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Negative main target
        $response = $this->actingAs($admin)->post(route('admin.update_target'), [
            'target' => -500000,
            'target_type' => 'bulanan',
        ]);
        $response->assertSessionHasErrors(['target']);

        // Negative monthly target in tahunan mode
        $response2 = $this->actingAs($admin)->post(route('admin.update_target'), [
            'target' => 10000000,
            'target_type' => 'tahunan',
            'tahunan_mode' => 'kustom_bulan',
            'monthly_targets' => [
                1 => -1000000,
            ],
        ]);
        $response2->assertSessionHasErrors(['monthly_targets.1']);
    }
}
