<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Services\Google2FAService;
use Illuminate\Support\Facades\Hash;

class AdminPasswordManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_view_user_list_in_settings()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $staff = User::factory()->create(['role' => 'staff', 'division' => 'washing']);

        $response = $this->actingAs($superAdmin)->get(route('admin.settings'));

        $response->assertStatus(200);
        $response->assertSee($staff->name);
        $response->assertSee($staff->email);
        $response->assertSee('Manajemen Akun');
        $response->assertSee('Khusus Super Admin');
    }

    public function test_regular_admin_cannot_see_user_management_section_in_settings()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff', 'division' => 'washing']);

        $response = $this->actingAs($admin)->get(route('admin.settings'));

        $response->assertStatus(200);
        $response->assertDontSee('Khusus Super Admin');
        $response->assertDontSee($staff->email);
    }

    public function test_regular_admin_cannot_create_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'New User',
            'email' => 'newuser@test.com',
            'password' => 'password123',
            'role' => 'staff',
            'division' => 'washing',
        ]);

        $response->assertStatus(403);
    }

    public function test_super_admin_can_create_user()
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        $response = $this->actingAs($superAdmin)->post(route('admin.users.store'), [
            'name' => 'New Staff User',
            'email' => 'newstaff@test.com',
            'password' => 'password123',
            'role' => 'staff',
            'division' => 'washing',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('users', ['email' => 'newstaff@test.com']);
    }

    public function test_super_admin_can_change_other_user_password_with_valid_2fa_otp()
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'google2fa_secret' => Google2FAService::generateSecretKey(),
        ]);
        $staff = User::factory()->create(['role' => 'staff', 'division' => 'washing']);

        $otp = Google2FAService::calculateOtp($superAdmin->google2fa_secret);

        $response = $this->actingAs($superAdmin)->post(route('admin.users.password.update', $staff->id), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
            'otp' => $otp,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $staff->refresh();
        $this->assertTrue(Hash::check('newpassword123', $staff->password));
    }

    public function test_super_admin_can_change_own_password_with_valid_2fa_otp()
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'google2fa_secret' => Google2FAService::generateSecretKey(),
        ]);

        $otp = Google2FAService::calculateOtp($superAdmin->google2fa_secret);

        $response = $this->actingAs($superAdmin)->post(route('admin.users.password.update', $superAdmin->id), [
            'password' => 'adminnewpass123',
            'password_confirmation' => 'adminnewpass123',
            'otp' => $otp,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $superAdmin->refresh();
        $this->assertTrue(Hash::check('adminnewpass123', $superAdmin->password));
    }

    public function test_admin_cannot_change_user_password()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'google2fa_secret' => Google2FAService::generateSecretKey(),
        ]);
        $staff = User::factory()->create(['role' => 'staff', 'division' => 'washing']);
        $otp = Google2FAService::calculateOtp($admin->google2fa_secret);

        $response = $this->actingAs($admin)->post(route('admin.users.password.update', $staff->id), [
            'password' => 'adminAttemptPass123',
            'password_confirmation' => 'adminAttemptPass123',
            'otp' => $otp,
        ]);

        // Regular admin must receive 403 Forbidden
        $response->assertStatus(403);
    }

    public function test_password_change_fails_with_invalid_otp()
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'google2fa_secret' => Google2FAService::generateSecretKey(),
        ]);
        $staff = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($superAdmin)->post(route('admin.users.password.update', $staff->id), [
            'password' => 'newpass123',
            'password_confirmation' => 'newpass123',
            'otp' => '000000', // invalid code
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }

    public function test_password_change_requires_confirmation_and_min_length()
    {
        $superAdmin = User::factory()->create([
            'role' => 'super_admin',
            'google2fa_secret' => Google2FAService::generateSecretKey(),
        ]);
        $staff = User::factory()->create(['role' => 'staff']);
        $otp = Google2FAService::calculateOtp($superAdmin->google2fa_secret);

        // Test unconfirmed
        $response = $this->actingAs($superAdmin)->post(route('admin.users.password.update', $staff->id), [
            'password' => 'mismatch123',
            'password_confirmation' => 'different123',
            'otp' => $otp,
        ]);
        $response->assertSessionHasErrors(['password']);

        // Test min length
        $response = $this->actingAs($superAdmin)->post(route('admin.users.password.update', $staff->id), [
            'password' => '123',
            'password_confirmation' => '123',
            'otp' => $otp,
        ]);
        $response->assertSessionHasErrors(['password']);
    }

    public function test_non_admin_cannot_change_password()
    {
        $staff = User::factory()->create(['role' => 'staff']);
        $targetUser = User::factory()->create(['role' => 'staff']);

        $response = $this->actingAs($staff)->post(route('admin.users.password.update', $targetUser->id), [
            'password' => 'hackedpassword123',
            'password_confirmation' => 'hackedpassword123',
            'otp' => '123456',
        ]);

        // Middleware or controller should reject non-admin with redirect or 403
        $this->assertTrue(in_array($response->status(), [302, 403]));
    }
}
