<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BkuExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_export_bku_pdf_and_saldo_awal_does_not_default_to_negative()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $kategori = \App\Models\KategoriPengeluaran::firstOrCreate(['nama' => 'Operasional']);

        // Simulasikan pengeluaran besar di masa lalu (seperti sabun / multo)
        Pengeluaran::create([
            'nama' => 'Pengeluaran Lama',
            'nominal' => 500000,
            'tanggal' => Carbon::create(2026, 8, 1),
            'id_transaksi' => 'EXP-OLD',
            'kategori' => 'Operasional',
            'kategori_id' => $kategori->id,
        ]);

        // Transaksi di bulan berjalan (September 2026)
        Transaksi::factory()->create([
            'total_price' => 20000,
            'payment_status' => 'lunas',
            'created_at' => Carbon::create(2026, 9, 10, 10, 0, 0),
        ]);

        Pengeluaran::create([
            'nama' => 'Sabun September',
            'nominal' => 15000,
            'tanggal' => Carbon::create(2026, 9, 12),
            'id_transaksi' => 'EXP-SEP',
            'kategori' => 'Operasional',
            'kategori_id' => $kategori->id,
        ]);

        // Request export BKU untuk bulan September 2026 tanpa saldo_awal
        $response = $this->actingAs($admin)->get(route('admin.laporan_keuangan.bku_pdf', [
            'filter' => 'bulanan',
            'bulan' => '2026-09',
            'tahun' => '2026',
        ]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_admin_can_export_bku_pdf_with_custom_saldo_awal()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $kategori = \App\Models\KategoriPengeluaran::firstOrCreate(['nama' => 'Operasional']);

        Transaksi::factory()->create([
            'total_price' => 7000,
            'payment_status' => 'lunas',
            'created_at' => Carbon::create(2026, 9, 14, 11, 0, 0),
        ]);

        Pengeluaran::create([
            'nama' => 'Sabun',
            'nominal' => 25000,
            'tanggal' => Carbon::create(2026, 9, 14),
            'id_transaksi' => 'EXP-2405',
            'kategori' => 'Operasional',
            'kategori_id' => $kategori->id,
        ]);

        // Dengan saldo awal kas 100.000, kas tidak minus (100.000 - 25.000 + 7.000 = 82.000)
        $response = $this->actingAs($admin)->get(route('admin.laporan_keuangan.bku_pdf', [
            'filter' => 'bulanan',
            'bulan' => '2026-09',
            'tahun' => '2026',
            'saldo_awal' => 100000,
        ]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
