<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Petugas;
use App\Models\JadwalPetugas;
use App\Models\Transaksi;
use App\Models\LaundryTask;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LaporanPetugasTest extends TestCase
{
    use RefreshDatabase;
    public function test_laporan_petugas_index_accessible_by_admin()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.laporan_petugas.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Pekerjaan Petugas');
    }

    public function test_laporan_petugas_pdf_export()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.laporan_petugas.pdf'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_laporan_petugas_excel_export()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.laporan_petugas.excel'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }
}
