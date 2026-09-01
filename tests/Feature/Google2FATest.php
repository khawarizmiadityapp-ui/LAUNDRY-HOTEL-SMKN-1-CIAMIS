<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Services\Google2FAService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Google2FATest extends TestCase
{
    use RefreshDatabase;

    public function test_service_generates_valid_base32_secret_and_calculates_valid_totp()
    {
        $secret = Google2FAService::generateSecretKey();
        $this->assertNotEmpty($secret);
        $this->assertTrue(strlen($secret) >= 16);

        $otp = Google2FAService::calculateOtp($secret);
        $this->assertEquals(6, strlen($otp));
        $this->assertTrue(ctype_digit($otp));

        $this->assertTrue(Google2FAService::verifyKey($secret, $otp));
        $this->assertFalse(Google2FAService::verifyKey($secret, '999999'));
    }

    public function test_admin_login_redirects_to_2fa_page()
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('login.2fa'));
        $response->assertSessionHas('2fa_admin_id', $admin->id);
        $this->assertFalse(Auth::check()); // Not yet fully authenticated
    }

    public function test_staff_login_does_not_require_2fa()
    {
        $staff = User::factory()->create([
            'email' => 'staff@test.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'division' => 'washing',
        ]);

        $response = $this->post('/login', [
            'email' => 'staff@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('petugas_piket.washing.index'));
        $this->assertTrue(Auth::check());
        $this->assertEquals($staff->id, Auth::id());
    }

    public function test_admin_can_view_2fa_verification_page()
    {
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'role' => 'admin',
            'google2fa_secret' => Google2FAService::generateSecretKey(),
        ]);

        $response = $this->withSession([
            '2fa_admin_id' => $admin->id,
            '2fa_expires_at' => now()->addMinutes(10)->timestamp,
        ])->get(route('login.2fa'));

        $response->assertStatus(200);
        $response->assertSee('Authenticator (QR)');
        $response->assertSee('MASUKKAN 6 DIGIT KODE VERIFIKASI');
    }

    public function test_admin_successful_2fa_verification_logs_in()
    {
        $secret = Google2FAService::generateSecretKey();
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'role' => 'admin',
            'google2fa_secret' => $secret,
        ]);

        $otp = Google2FAService::calculateOtp($secret);

        $response = $this->withSession([
            '2fa_admin_id' => $admin->id,
            '2fa_expires_at' => now()->addMinutes(10)->timestamp,
        ])->post(route('login.2fa.verify'), [
            'code' => $otp,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertTrue(Auth::check());
        $this->assertEquals($admin->id, Auth::id());
    }

    public function test_admin_fails_2fa_verification_with_wrong_code()
    {
        $secret = Google2FAService::generateSecretKey();
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'role' => 'admin',
            'google2fa_secret' => $secret,
        ]);

        $response = $this->withSession([
            '2fa_admin_id' => $admin->id,
            '2fa_expires_at' => now()->addMinutes(10)->timestamp,
        ])->post(route('login.2fa.verify'), [
            'code' => '000000',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertFalse(Auth::check());
    }

    public function test_admin_can_cancel_2fa_and_return_to_login()
    {
        $response = $this->withSession([
            '2fa_admin_id' => 1,
            '2fa_expires_at' => now()->addMinutes(10)->timestamp,
        ])->post(route('login.2fa.cancel'));

        $response->assertRedirect(route('login'));
        $response->assertSessionMissing('2fa_admin_id');
    }
}
