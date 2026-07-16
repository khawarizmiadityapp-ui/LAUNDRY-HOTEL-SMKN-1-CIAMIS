<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\EnsureUserIsStaffOrAdmin;

class RoleAccessTest extends TestCase
{
    /**
     * Set up a test route that uses the new middleware.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Create a dummy route protected by our new middleware
        Route::middleware(['web', EnsureUserIsStaffOrAdmin::class])->get('/_test_secure_route', function () {
            return 'Authorized';
        });
    }

    public function test_unauthenticated_user_is_redirected()
    {
        $response = $this->get('/_test_secure_route');
        
        $response->assertStatus(302);
        $response->assertRedirect(route('login'));
    }

    public function test_customer_role_is_forbidden()
    {
        // Create a mock user with customer role
        $user = new User();
        $user->id = 1;
        $user->name = 'Customer User';
        $user->role = 'customer';
        
        $this->actingAs($user);
        
        $response = $this->get('/_test_secure_route');
        
        $response->assertStatus(403);
    }

    public function test_admin_role_is_allowed()
    {
        // Create a mock user with admin role
        $user = new User();
        $user->id = 2;
        $user->name = 'Admin User';
        $user->role = 'admin';
        
        $this->actingAs($user);
        
        $response = $this->get('/_test_secure_route');
        
        $response->assertStatus(200);
        $response->assertSee('Authorized');
    }

    public function test_staff_role_is_allowed()
    {
        // Create a mock user with staff role
        $user = new User();
        $user->id = 3;
        $user->name = 'Staff User';
        $user->role = 'staff';
        
        $this->actingAs($user);
        
        $response = $this->get('/_test_secure_route');
        
        $response->assertStatus(200);
        $response->assertSee('Authorized');
    }
}
