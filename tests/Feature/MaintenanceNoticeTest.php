<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaintenanceNoticeTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_sees_maintenance_panel_in_settings(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)->get(route('admin.settings'));

        $response->assertStatus(200);
        $response->assertSee('id="maintenance-panel"', false);
        $response->assertSee(route('admin.settings.maintenance'));
    }

    public function test_regular_admin_does_not_see_maintenance_panel_in_settings(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.settings'));

        $response->assertStatus(200);
        $response->assertDontSee('id="maintenance-panel"', false);
        $response->assertDontSee(route('admin.settings.maintenance'));
    }

    public function test_super_admin_can_enable_update_and_disable_maintenance_notice(): void
    {
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin Utama',
            'role' => 'super_admin',
        ]);

        $enableResponse = $this->actingAs($superAdmin)->post(route('admin.settings.maintenance'), [
            'enabled' => '1',
            'mode' => 'banner',
            'title' => 'Perbaikan Server Malam Ini',
            'message' => 'Sistem sedang diperbarui untuk peningkatan performa.',
            'eta' => '23:00 WIB',
        ]);

        $enableResponse->assertStatus(302);
        $enableResponse->assertSessionHas('success');

        $config = get_maintenance_config();
        $this->assertTrue($config['enabled']);
        $this->assertSame('banner', $config['mode']);
        $this->assertSame('Perbaikan Server Malam Ini', $config['title']);
        $this->assertSame('Sistem sedang diperbarui untuk peningkatan performa.', $config['message']);
        $this->assertSame('23:00 WIB', $config['eta']);
        $this->assertSame('Super Admin Utama', $config['updated_by']);

        $disableResponse = $this->actingAs($superAdmin)->post(route('admin.settings.maintenance'), [
            'enabled' => '0',
            'mode' => 'banner',
            'title' => 'Perbaikan Server Malam Ini',
            'message' => 'Sistem sedang diperbarui untuk peningkatan performa.',
            'eta' => '',
        ]);

        $disableResponse->assertStatus(302);
        $disableResponse->assertSessionHas('success');
        $this->assertFalse(get_maintenance_config()['enabled']);
    }

    public function test_non_super_admin_cannot_update_maintenance_notice(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff', 'division' => 'washing']);
        $customer = User::factory()->create(['role' => 'customer']);

        $payload = [
            'enabled' => '1',
            'mode' => 'full',
            'title' => 'Percobaan Tidak Sah',
            'message' => 'Tidak boleh tersimpan.',
            'eta' => 'Besok',
        ];

        $this->actingAs($admin)
            ->post(route('admin.settings.maintenance'), $payload)
            ->assertStatus(403);

        $this->actingAs($staff)
            ->post(route('admin.settings.maintenance'), $payload)
            ->assertRedirect(route('petugas_piket.dashboard'));

        $this->actingAs($customer)
            ->post(route('admin.settings.maintenance'), $payload)
            ->assertStatus(403);

        $this->assertFalse(get_maintenance_config()['enabled']);
    }

    public function test_banner_mode_displays_notice_across_public_login_staff_and_admin_pages(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff', 'division' => 'washing']);

        $this->actingAs($superAdmin)->post(route('admin.settings.maintenance'), [
            'enabled' => '1',
            'mode' => 'banner',
            'title' => 'Pemberitahuan Update Aplikasi',
            'message' => 'Beberapa fitur laporan sedang disempurnakan.',
            'eta' => '17:30 WIB',
        ])->assertStatus(302);

        auth()->logout();

        // Pengunjung publik (halaman utama & login) melihat banner tanpa tombol pengaturan
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Sedang Maintenance');
        $homeResponse->assertSee('Pemberitahuan Update Aplikasi');
        $homeResponse->assertSee('Beberapa fitur laporan sedang disempurnakan.');
        $homeResponse->assertSee('Estimasi selesai: 17:30 WIB');
        $homeResponse->assertDontSee('Atur / Matikan');

        $loginResponse = $this->get(route('login'));
        $loginResponse->assertStatus(200);
        $loginResponse->assertSee('Pemberitahuan Update Aplikasi');
        $loginResponse->assertDontSee('Atur / Matikan');

        // Staff melihat banner di portal petugas
        $staffResponse = $this->actingAs($staff)->get(route('petugas_piket.dashboard'));
        $staffResponse->assertStatus(200);
        $staffResponse->assertSee('Pemberitahuan Update Aplikasi');
        $staffResponse->assertDontSee('Atur / Matikan');

        // Admin biasa melihat banner, tapi tidak melihat tombol Atur / Matikan maupun badge topbar Super Admin
        $adminResponse = $this->actingAs($admin)->get(route('admin.settings'));
        $adminResponse->assertStatus(200);
        $adminResponse->assertSee('Pemberitahuan Update Aplikasi');
        $adminResponse->assertDontSee('Atur / Matikan');
        $adminResponse->assertDontSee('Maintenance (Banner)');

        // Super Admin melihat banner + tombol Atur / Matikan + badge topbar
        $superResponse = $this->actingAs($superAdmin)->get(route('admin.settings'));
        $superResponse->assertStatus(200);
        $superResponse->assertSee('Pemberitahuan Update Aplikasi');
        $superResponse->assertSee('Atur / Matikan');
        $superResponse->assertSee('Maintenance (Banner)');
    }

    public function test_full_mode_blocks_public_and_non_super_admin_while_allowing_login_and_super_admin(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff', 'division' => 'washing']);

        $this->actingAs($superAdmin)->post(route('admin.settings.maintenance'), [
            'enabled' => '1',
            'mode' => 'full',
            'title' => 'Pemeliharaan Database Utama',
            'message' => 'Akses sementara ditutup selama migrasi server.',
            'eta' => '21:00 WIB',
        ])->assertStatus(302);

        auth()->logout();

        // Pengunjung publik dihentikan dengan halaman 503
        $guestHome = $this->get('/');
        $guestHome->assertStatus(503);
        $guestHome->assertSee('Sedang Dalam Perbaikan');
        $guestHome->assertSee('Pemeliharaan Database Utama');
        $guestHome->assertSee('Akses sementara ditutup selama migrasi server.');
        $guestHome->assertSee('Estimasi selesai: 21:00 WIB');
        $guestHome->assertSee('Login Super Admin');

        // Request JSON mendapat respons 503 terstruktur
        $jsonResponse = $this->getJson('/');
        $jsonResponse->assertStatus(503)->assertJson([
            'message' => 'Pemeliharaan Database Utama',
            'detail' => 'Akses sementara ditutup selama migrasi server.',
            'eta' => '21:00 WIB',
        ]);

        // Halaman login tetap terbuka agar Super Admin bisa masuk
        $this->get(route('login'))
            ->assertStatus(200)
            ->assertSee('Pemeliharaan Database Utama');

        // Staff & Admin biasa juga terkena halaman 503 pada mode full
        $this->actingAs($staff)
            ->get(route('petugas_piket.dashboard'))
            ->assertStatus(503)
            ->assertSee('Pemeliharaan Database Utama');

        $this->actingAs($admin)
            ->get(route('admin.settings'))
            ->assertStatus(503)
            ->assertSee('Pemeliharaan Database Utama');

        // Super Admin tetap memiliki akses penuh
        $this->actingAs($superAdmin)
            ->get('/')
            ->assertStatus(200);

        $superSettings = $this->actingAs($superAdmin)->get(route('admin.settings'));
        $superSettings->assertStatus(200);
        $superSettings->assertSee('Maintenance (Tutup)');
    }
}
