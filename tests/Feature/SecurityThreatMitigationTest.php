<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SecurityThreatMitigationTest extends TestCase
{
    use RefreshDatabase;

    // ─── Access Control Tests ────────────────────────────────────────

    /** @test */
    public function test_customer_cannot_access_petugas_dashboard(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        $response = $this->actingAs($customer)->get('/petugas');

        $response->assertStatus(403);
    }

    /** @test */
    public function test_customer_cannot_complete_tasks(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
        ]);

        // Attempt to POST to a staff-only task completion route
        $response = $this->actingAs($customer)->post('/petugas/tasks/1/complete', [
            'stage' => 'washing',
            'petugas_name' => 'Hacker',
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_staff_can_access_petugas_dashboard(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'division' => 'washing',
        ]);

        $response = $this->actingAs($staff)->get('/petugas');

        // Should get 200 (dashboard page loads)
        $response->assertStatus(200);
    }

    // ─── Masking Helper Tests ────────────────────────────────────────

    /** @test */
    public function test_mask_phone_number(): void
    {
        // Load helper if not yet loaded
        if (!function_exists('mask_phone_number')) {
            require_once __DIR__ . '/../../app/helpers.php';
        }

        // Standard Indonesian number
        $this->assertEquals('0821*****029', mask_phone_number('082116035029'));

        // Already formatted with country code
        $this->assertEquals('6282******029', mask_phone_number('6282116035029'));

        // With non-numeric characters
        $this->assertEquals('0821*****029', mask_phone_number('0821-1603-5029'));

        // Short number
        $this->assertEquals('08***90', mask_phone_number('0812390'));

        // Null and empty
        $this->assertEquals('', mask_phone_number(null));
        $this->assertEquals('', mask_phone_number(''));
    }

    /** @test */
    public function test_mask_name(): void
    {
        if (!function_exists('mask_name')) {
            require_once __DIR__ . '/../../app/helpers.php';
        }

        // Two-word name
        $this->assertEquals('B**i S*****o', mask_name('Budi Santoso'));

        // Single word
        $this->assertEquals('B**i', mask_name('Budi'));

        // Very short name (2 chars)
        $this->assertEquals('Bu', mask_name('Bu'));

        // Null and empty
        $this->assertEquals('', mask_name(null));
        $this->assertEquals('', mask_name(''));
    }

    /** @test */
    public function test_mask_name_security_xss(): void
    {
        if (!function_exists('mask_name')) {
            require_once __DIR__ . '/../../app/helpers.php';
        }

        // XSS payload - should be treated as a word and masked
        $result = mask_name("<script>alert('xss')</script>");
        $this->assertStringNotContainsString('<script>', $result);
    }

    /** @test */
    public function test_mask_phone_security_injection(): void
    {
        if (!function_exists('mask_phone_number')) {
            require_once __DIR__ . '/../../app/helpers.php';
        }

        // SQL injection payload - all non-numeric chars are stripped
        $result = mask_phone_number("082116035029'; DROP TABLE users; --");
        $this->assertStringNotContainsString("'", $result);
        $this->assertStringNotContainsString("DROP", $result);
    }
}
