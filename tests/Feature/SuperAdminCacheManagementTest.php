<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SuperAdminCacheManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        foreach (['config.php', 'routes-v7.php', 'events.php'] as $cacheFile) {
            $path = base_path('bootstrap/cache/' . $cacheFile);
            if (file_exists($path)) {
                @unlink($path);
            }
        }

        parent::tearDown();
    }

    public function test_only_super_admin_sees_system_cache_panel_in_settings(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $admin = User::factory()->create(['role' => 'admin']);

        $superRes = $this->actingAs($superAdmin)->get(route('admin.settings'));
        $superRes->assertStatus(200);
        $superRes->assertSee('id="system-cache-panel"', false);
        $superRes->assertSee(route('admin.settings.cache'));
        $superRes->assertSee('php artisan optimize:clear');
        $superRes->assertSee('php artisan config:cache');
        $superRes->assertSee('php artisan route:cache');
        $superRes->assertSee('php artisan view:cache');

        $adminRes = $this->actingAs($admin)->get(route('admin.settings'));
        $adminRes->assertStatus(200);
        $adminRes->assertDontSee('id="system-cache-panel"', false);
        $adminRes->assertDontSee(route('admin.settings.cache'));
    }

    public function test_non_super_admin_cannot_run_system_cache_commands(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff', 'division' => 'washing']);
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($admin)
            ->post(route('admin.settings.cache'), ['action' => 'optimize_clear'])
            ->assertStatus(403);

        $this->actingAs($staff)
            ->post(route('admin.settings.cache'), ['action' => 'optimize_clear'])
            ->assertRedirect(route('petugas_piket.dashboard'));

        $this->actingAs($customer)
            ->post(route('admin.settings.cache'), ['action' => 'optimize_clear'])
            ->assertStatus(403);
    }

    public function test_super_admin_can_execute_individual_and_full_cache_actions(): void
    {
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin Cache',
            'role' => 'super_admin',
        ]);

        // Validasi action ilegal ditolak
        $this->actingAs($superAdmin)
            ->post(route('admin.settings.cache'), ['action' => 'migrate_fresh'])
            ->assertSessionHasErrors('action');

        foreach ([
            'view_cache' => '$ php artisan view:cache',
            'config_cache' => '$ php artisan config:cache',
            'route_cache' => '$ php artisan route:cache',
            'optimize_clear' => '$ php artisan optimize:clear',
        ] as $action => $expectedCliLine) {
            $res = $this->actingAs($superAdmin)
                ->post(route('admin.settings.cache'), ['action' => $action]);

            $res->assertRedirect(route('admin.settings') . '#system-cache-panel');
            $res->assertSessionHas('success');
            $res->assertSessionHas('cache_output');
            $this->assertStringContainsString($expectedCliLine, (string) session('cache_output'));
        }

        // Jalankan semua sekaligus (optimize_all)
        $allRes = $this->actingAs($superAdmin)
            ->post(route('admin.settings.cache'), ['action' => 'optimize_all']);

        $allRes->assertRedirect(route('admin.settings') . '#system-cache-panel');
        $allRes->assertSessionHas('success');
        $allOutput = (string) session('cache_output');
        $this->assertStringContainsString('$ php artisan optimize:clear', $allOutput);
        $this->assertStringContainsString('$ php artisan config:cache', $allOutput);
        $this->assertStringContainsString('$ php artisan route:cache', $allOutput);
        $this->assertStringContainsString('$ php artisan view:cache', $allOutput);

        // Pastikan activity log tercatat
        $this->assertDatabaseHas('activity_log', [
            'log_name' => 'system_cache',
            'event' => 'system_cache_optimize_all',
            'causer_id' => $superAdmin->id,
        ]);

        // Bersihkan bootstrap cache agar tidak mempengaruhi test selanjutnya
        Artisan::call('optimize:clear');
    }
}
