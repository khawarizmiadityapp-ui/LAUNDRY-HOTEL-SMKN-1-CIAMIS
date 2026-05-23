<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginFixTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_can_login_with_correct_credentials()
    {
        // Create admin user with hashed password
        $user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Attempt login
        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password',
        ]);

        // Should redirect to admin dashboard
        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function staff_can_login_with_correct_credentials()
    {
        // Create staff user with hashed password
        $user = User::create([
            'name' => 'Staff Test',
            'email' => 'staff@test.com',
            'password' => Hash::make('123456'),
            'role' => 'staff',
            'division' => 'washing',
        ]);

        // Attempt login
        $response = $this->post('/login', [
            'email' => 'staff@test.com',
            'password' => '123456',
        ]);

        // Should redirect to staff dashboard
        $response->assertRedirect(route('petugas_piket.washing.index'));
        $this->assertAuthenticatedAs($user);
    }
}
