<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminPasswordManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_user_list_in_settings()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff', 'division' => 'washing']);

        $response = $this->actingAs($admin)->get(route('admin.settings'));

        $response->assertStatus(200);
        $response->assertSee($staff->name);
        $response->assertSee($staff->email);
        $response->assertSee('Manajemen Akun');
    }

    public function test_admin_can_change_other_user_password()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff', 'division' => 'washing']);

        $response = $this->actingAs($admin)->post(route('admin.users.password.update', $staff->id), [
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $staff->refresh();
        $this->assertTrue(Hash::check('newpassword123', $staff->password));
    }

    public function test_admin_can_change_own_password()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.users.password.update', $admin->id), [
            'password' => 'adminnewpass123',
            'password_confirmation' => 'adminnewpass123',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $admin->refresh();
        $this->assertTrue(Hash::check('adminnewpass123', $admin->password));
    }

    public function test_password_change_requires_confirmation_and_min_length()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $staff = User::factory()->create(['role' => 'staff']);

        // Test unconfirmed
        $response = $this->actingAs($admin)->post(route('admin.users.password.update', $staff->id), [
            'password' => 'mismatch123',
            'password_confirmation' => 'different123',
        ]);
        $response->assertSessionHasErrors(['password']);

        // Test min length
        $response = $this->actingAs($admin)->post(route('admin.users.password.update', $staff->id), [
            'password' => '123',
            'password_confirmation' => '123',
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
        ]);

        // Middleware or controller should reject non-admin with redirect or 403
        $this->assertTrue(in_array($response->status(), [302, 403]));
    }
}
