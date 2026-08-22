<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JadwalPetugas;
use App\Models\Petugas;
use App\Imports\JadwalPetugasImport;
use App\Exports\JadwalPetugasExportTemplate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JadwalPetugasController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->get('date', Carbon::today()->format('Y-m-d'));
        $search = $request->get('search');
        $station = $request->get('station');

        $query = JadwalPetugas::query();

        if ($selectedDate) {
            $query->whereDate('tanggal', $selectedDate);
        }

        if ($search) {
            $query->where('nama', 'like', "%{$search}%");
        }

        if ($station && $station !== 'all') {
            $query->where('selected_station', $station);
        }

        $jadwalList = $query->orderBy('shift')->orderBy('nama')->paginate(20)->withQueryString();

        // Statistik Hari Ini (atau tanggal terpilih)
        $targetDate = $selectedDate ?: Carbon::today()->format('Y-m-d');
        $statsToday = [
            'total' => JadwalPetugas::whereDate('tanggal', $targetDate)->count(),
            'checked_in' => JadwalPetugas::whereDate('tanggal', $targetDate)->where('selected_station', '!=', 'none')->count(),
            'washing' => JadwalPetugas::whereDate('tanggal', $targetDate)->where('selected_station', 'washing')->count(),
            'setrika' => JadwalPetugas::whereDate('tanggal', $targetDate)->where('selected_station', 'setrika')->count(),
            'packing' => JadwalPetugas::whereDate('tanggal', $targetDate)->where('selected_station', 'packing')->count(),
            'pending_checkin' => JadwalPetugas::whereDate('tanggal', $targetDate)->where('selected_station', 'none')->count(),
        ];

        $petugasMaster = Petugas::orderBy('nama')->get();

        return view('admin.jadwal_petugas.index', compact(
            'jadwalList',
            'statsToday',
            'selectedDate',
            'petugasMaster'
        ));
    }

    public function downloadTemplate()
    {
        return Excel::download(new JadwalPetugasExportTemplate, 'template_jadwal_petugas.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            $import = new JadwalPetugasImport();
            Excel::import($import, $request->file('file'));

            $count = $import->getImportedCount();
            $errors = $import->getErrors();

            if ($count === 0 && !empty($errors)) {
                return redirect()->back()->with('error', 'Gagal import: ' . implode('<br>', $errors));
            }

            $msg = "Berhasil mengimpor {$count} data jadwal petugas!";
            if (!empty($errors)) {
                $msg .= " Namun ada beberapa baris dilewati: " . implode(', ', $errors);
            }

            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            Log::error('Import Jadwal Petugas Gagal: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengimpor file: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'nama' => 'required|string|max:255',
            'shift' => 'required|string|max:50',
            'selected_station' => 'nullable|in:washing,setrika,packing,kasir,none',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $petugas = Petugas::where('nama', $request->nama)->first();
        if (!$petugas) {
            $nextId = ((int) Petugas::max('id')) + 1;
            $petugas = Petugas::create([
                'nama' => $request->nama,
                'id_petugas' => 'STF-' . str_pad((string) $nextId, 4, '0', STR_PAD_LEFT),
                'role' => 'Washing',
                'status' => 'Aktif',
                'shift' => $request->shift,
            ]);
        }

        JadwalPetugas::updateOrCreate(
            [
                'tanggal' => $request->tanggal,
                'nama' => $request->nama,
            ],
            [
                'id_petugas' => $petugas->id_petugas,
                'shift' => $request->shift,
                'selected_station' => $request->selected_station ?? 'none',
                'checked_in_at' => ($request->selected_station && $request->selected_station !== 'none') ? now() : null,
                'status' => 'terjadwal',
                'keterangan' => $request->keterangan,
            ]
        );

        return redirect()->back()->with('success', 'Jadwal petugas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'shift' => 'required|string|max:50',
            'selected_station' => 'required|in:washing,setrika,packing,kasir,none',
            'status' => 'required|in:terjadwal,hadir,izin,alpha',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $jadwal = JadwalPetugas::findOrFail($id);

        $checkedInAt = $jadwal->checked_in_at;
        if ($request->selected_station !== 'none' && !$checkedInAt) {
            $checkedInAt = now();
        } elseif ($request->selected_station === 'none') {
            $checkedInAt = null;
        }

        $jadwal->update([
            'shift' => $request->shift,
            'selected_station' => $request->selected_station,
            'checked_in_at' => $checkedInAt,
            'status' => $request->status,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Jadwal petugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $jadwal = JadwalPetugas::findOrFail($id);
        $jadwal->delete();

        return redirect()->back()->with('success', 'Jadwal petugas berhasil dihapus.');
    }
}
