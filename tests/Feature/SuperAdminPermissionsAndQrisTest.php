<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class SuperAdminPermissionsAndQrisTest extends TestCase
{
    use RefreshDatabase;

    private array $createdImageFiles = [];

    protected function tearDown(): void
    {
        foreach ($this->createdImageFiles as $filePath) {
            if ($filePath && file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        parent::tearDown();
    }

    public function test_super_admin_and_admin_both_see_dedicated_qris_panel_and_modal(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $admin = User::factory()->create(['role' => 'admin']);

        foreach ([$superAdmin, $admin] as $user) {
            $settingsRes = $this->actingAs($user)->get(route('admin.settings'));
            $settingsRes->assertStatus(200);
            $settingsRes->assertSee('id="qris-config"', false);
            $settingsRes->assertSee(route('admin.settings.qris'));
            $settingsRes->assertSee('Portal Petugas (Semua Stasiun)');

            $pembayaranRes = $this->actingAs($user)->get(route('admin.pembayaran.index'));
            $pembayaranRes->assertStatus(200);
            $pembayaranRes->assertSee('Atur / Ganti QRIS');
            $pembayaranRes->assertSee(route('admin.settings.qris'));
        }
    }

    public function test_super_admin_can_update_qris_details_payload_and_image_and_reset(): void
    {
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin Master',
            'role' => 'super_admin',
        ]);

        $fakeQrImage = UploadedFile::fake()->image('qris_toko_resmi.png', 300, 300);

        $response = $this->actingAs($superAdmin)->post(route('admin.settings.qris'), [
            'qris_merchant_name' => 'BENING LAUNDRY OFFICIAL',
            'qris_nmid' => 'ID9988776655',
            'qris_payload' => '0002010102115802ID5914BENING LAUNDRY',
            'bank_transfer_info' => 'Bank BJB 0011223344 a.n. SMKN 1 Ciamis',
            'qris_image' => $fakeQrImage,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $config = get_qris_config();
        $this->assertSame('BENING LAUNDRY OFFICIAL', $config['merchant_name']);
        $this->assertSame('ID9988776655', $config['nmid']);
        $this->assertSame('0002010102115802ID5914BENING LAUNDRY', $config['qris_payload']);
        $this->assertSame('Bank BJB 0011223344 a.n. SMKN 1 Ciamis', $config['bank_info']);
        $this->assertNotNull($config['qris_image']);
        $this->assertStringStartsWith('/images/qris_', $config['qris_image']);

        $uploadedAbsolute = public_path(ltrim($config['qris_image'], '/'));
        $this->createdImageFiles[] = $uploadedAbsolute;
        $this->assertFileExists($uploadedAbsolute);

        // Verifikasi QRIS baru langsung tersinkron ke POS dan Nota
        $trx = Transaksi::create([
            'transaksi_code' => 'TRX-QRIS-001',
            'customer_name' => 'Pelanggan QRIS',
            'customer_phone' => '081234567890',
            'user_id' => $superAdmin->id,
            'service_type' => 'kiloan',
            'weight' => 2,
            'price_per_kg' => 7000,
            'total_price' => 14000,
            'payment_method' => 'qris',
            'payment_status' => 'lunas',
            'status' => 'diterima',
        ]);

        $posRes = $this->actingAs($superAdmin)->get(route('admin.pos.index'));
        $posRes->assertStatus(200);
        $posRes->assertSee('BENING LAUNDRY OFFICIAL');
        $posRes->assertSee(json_encode($config['qris_image']), false);

        $notaRes = $this->actingAs($superAdmin)->get(route('pos.nota', $trx->id));
        $notaRes->assertStatus(200);
        $notaRes->assertSee('BENING LAUNDRY OFFICIAL');
        $notaRes->assertSee($config['qris_image']);

        // Reset gambar kustom kembali ke QR otomatis
        $resetRes = $this->actingAs($superAdmin)->post(route('admin.settings.qris'), [
            'qris_merchant_name' => 'BENING LAUNDRY OFFICIAL',
            'qris_nmid' => 'ID9988776655',
            'qris_payload' => '0002010102115802ID5914BENING LAUNDRY',
            'reset_qris_image' => '1',
        ]);

        $resetRes->assertStatus(302);
        $this->assertNull(get_qris_config()['qris_image']);
    }

    public function test_admin_can_also_update_qris_and_staff_cannot(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff', 'division' => 'customer_service']);
        $customer = User::factory()->create(['role' => 'customer']);

        $this->actingAs($admin)->post(route('admin.settings.qris'), [
            'qris_merchant_name' => 'MERCHANT ADMIN UPDATE',
            'qris_nmid' => 'ID11223344',
        ])->assertStatus(302)->assertSessionHas('success');

        $this->assertSame('MERCHANT ADMIN UPDATE', get_qris_config()['merchant_name']);

        $this->actingAs($staff)->post(route('admin.settings.qris'), [
            'qris_merchant_name' => 'STAFF TIDAK BOLEH',
        ])->assertRedirect(route('petugas_piket.dashboard'));

        $this->actingAs($customer)->post(route('admin.settings.qris'), [
            'qris_merchant_name' => 'CUSTOMER TIDAK BOLEH',
        ])->assertStatus(403);

        $this->assertSame('MERCHANT ADMIN UPDATE', get_qris_config()['merchant_name']);
    }

    public function test_super_admin_can_access_all_admin_and_petugas_role_workspaces(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $adminRoutes = [
            'admin.dashboard',
            'admin.settings',
            'admin.pembayaran.index',
            'admin.pembayaran.create',
            'admin.pos.index',
            'admin.transactions.index',
            'admin.customers.index',
            'admin.jadwal.index',
            'admin.laporan_keuangan.index',
            'admin.laporan_petugas.index',
            'admin.pengeluaran.index',
            'admin.inventory.index',
        ];

        foreach ($adminRoutes as $routeName) {
            $this->actingAs($superAdmin)->get(route($routeName))->assertStatus(200);
        }

        foreach (['petugas.pos.index', 'petugas_piket.transaksi.index'] as $sharedRouteName) {
            $sharedRes = $this->actingAs($superAdmin)->get(route($sharedRouteName));
            $sharedRes->assertStatus(200);
            $sharedRes->assertSee('Portal Petugas (Semua Stasiun)');
        }

        $petugasRoutes = [
            'petugas_piket.dashboard',
            'petugas_piket.washing.index',
            'petugas_piket.setrika.index',
            'petugas_piket.packing.index',
            'petugas_piket.inventory.index',
            'petugas_piket.history.index',
        ];

        foreach ($petugasRoutes as $routeName) {
            $res = $this->actingAs($superAdmin)->get(route($routeName));
            $res->assertStatus(200);
            $res->assertSee('Kembali ke Portal Admin');
        }
    }

    public function test_invalid_qris_upload_returns_visible_validation_error(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $badFile = UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf');

        $res = $this->actingAs($superAdmin)
            ->from(route('admin.settings'))
            ->followingRedirects()
            ->post(route('admin.settings.qris'), [
                'qris_image' => $badFile,
            ]);

        $res->assertStatus(200);
        $res->assertSee('File QRIS harus berupa gambar yang valid.');
    }
}
