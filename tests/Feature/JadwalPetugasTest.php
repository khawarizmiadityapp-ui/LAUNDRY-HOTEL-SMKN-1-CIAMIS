<?php

namespace Tests\Feature;

use App\Models\JadwalPetugas;
use App\Models\Petugas;
use App\Models\User;
use App\Imports\JadwalPetugasImport;
use App\Exports\JadwalPetugasExportTemplate;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class JadwalPetugasTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_jadwal_page(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.jadwal.index'));
        $response->assertStatus(200);
        $response->assertSee('Jadwal Piket Petugas');
    }

    public function test_admin_can_download_excel_template(): void
    {
        Excel::fake();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.jadwal.template'));
        $response->assertStatus(200);

        Excel::assertDownloaded('template_jadwal_petugas.xlsx', function (JadwalPetugasExportTemplate $export) {
            return count($export->headings()) === 5;
        });
    }

    public function test_admin_can_create_manual_schedule(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $today = Carbon::today()->format('Y-m-d');

        $response = $this->actingAs($admin)->post(route('admin.jadwal.store'), [
            'tanggal' => $today,
            'nama' => 'Siswa Test Satu',
            'shift' => 'Pagi',
            'selected_station' => 'none',
            'keterangan' => 'Piket Pagi Lab 1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jadwal_petugas', [
            'nama' => 'Siswa Test Satu',
            'shift' => 'Pagi',
            'selected_station' => 'none',
        ]);
    }

    public function test_student_can_checkin_and_select_station_model_1(): void
    {
        $staff = User::factory()->create([
            'role' => 'staff',
            'division' => 'washing',
        ]);

        $today = Carbon::today()->format('Y-m-d');
        $jadwal = JadwalPetugas::create([
            'tanggal' => $today,
            'nama' => 'Rina Melati',
            'shift' => 'Pagi',
            'selected_station' => 'none',
            'status' => 'terjadwal',
        ]);

        $response = $this->actingAs($staff)->post(route('petugas_piket.checkin.station'), [
            'jadwal_id' => $jadwal->id,
            'station' => 'washing',
        ]);

        $response->assertRedirect(route('petugas_piket.washing.index'));
        $response->assertSessionHas('active_piket_nama', 'Rina Melati');
        $response->assertSessionHas('active_piket_station', 'washing');

        $this->assertDatabaseHas('jadwal_petugas', [
            'id' => $jadwal->id,
            'selected_station' => 'washing',
            'status' => 'hadir',
        ]);
    }

    public function test_admin_can_update_and_delete_jadwal(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $today = Carbon::today()->format('Y-m-d');
        $jadwal = JadwalPetugas::create([
            'tanggal' => $today,
            'nama' => 'Doni Saputra',
            'shift' => 'Pagi',
            'selected_station' => 'none',
            'status' => 'terjadwal',
        ]);

        // Test Update
        $response = $this->actingAs($admin)->put(route('admin.jadwal.update', $jadwal->id), [
            'shift' => 'Siang',
            'selected_station' => 'setrika',
            'status' => 'hadir',
            'keterangan' => 'Tukar shift',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jadwal_petugas', [
            'id' => $jadwal->id,
            'shift' => 'Siang',
            'selected_station' => 'setrika',
            'status' => 'hadir',
        ]);

        // Test Delete
        $delResponse = $this->actingAs($admin)->delete(route('admin.jadwal.destroy', $jadwal->id));
        $delResponse->assertRedirect();
        $this->assertDatabaseMissing('jadwal_petugas', [
            'id' => $jadwal->id,
        ]);
    }
}
