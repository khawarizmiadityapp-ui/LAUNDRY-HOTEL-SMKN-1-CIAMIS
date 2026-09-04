<?php

namespace Tests\Feature;

use App\Models\Petugas;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetugasManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_petugas_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.petugas.index'));
        $response->assertStatus(200);
    }

    public function test_admin_can_create_petugas_with_various_roles(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $roles = ['Washing', 'Ironing', 'Packing', 'Kasir', 'Kurir', 'Admin', 'Operasional'];

        foreach ($roles as $role) {
            $response = $this->actingAs($admin)->postJson(route('admin.petugas.store'), [
                'nama' => 'Petugas ' . $role,
                'role' => $role,
            ]);

            $response->assertStatus(201);
            $this->assertDatabaseHas('petugas', [
                'nama' => 'Petugas ' . $role,
                'role' => $role,
            ]);
        }
    }

    public function test_admin_can_update_petugas_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $petugas = Petugas::create([
            'nama' => 'Petugas Lama',
            'id_petugas' => 'STF-0099',
            'role' => 'Operasional',
            'status' => 'Aktif',
            'shift' => '-',
        ]);

        $response = $this->actingAs($admin)->putJson(route('admin.petugas.update', $petugas->id), [
            'nama' => 'Petugas Diperbarui',
            'role' => 'Washing',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('petugas', [
            'id' => $petugas->id,
            'nama' => 'Petugas Diperbarui',
            'role' => 'Washing',
        ]);
    }
}
