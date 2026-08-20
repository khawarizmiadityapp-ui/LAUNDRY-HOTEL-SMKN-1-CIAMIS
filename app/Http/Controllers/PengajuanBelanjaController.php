<?php

namespace App\Http\Controllers;

use App\Models\PengajuanBelanja;
use App\Models\Pengeluaran;
use App\Models\KategoriPengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengajuanBelanjaController extends Controller
{
    /**
     * Tampilkan daftar pengajuan belanja.
     */
    public function index(Request $request)
    {
        $query = PengajuanBelanja::with(['user', 'approver', 'kategoriPengeluaran', 'pengeluaran'])
            ->when($request->status, fn($q) => $q->status($request->status))
            ->when($request->urgensi, fn($q) => $q->urgensi($request->urgensi))
            ->when($request->kategori_id, fn($q) => $q->where('kategori_id', $request->kategori_id))
            ->when($request->search, function($q, $search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('nama_pengajuan', 'like', "%{$search}%")
                        ->orWhere('kode_pengajuan', 'like', "%{$search}%")
                        ->orWhere('alasan', 'like', "%{$search}%");
                });
            })
            ->when($request->dari || $request->sampai, fn($q) => $q->dateRange($request->dari, $request->sampai))
            ->orderByDesc('tanggal_pengajuan')
            ->orderByDesc('id');

        $pengajuans = $query->paginate(10)->withQueryString();

        // ─── Stat Cards ─────────────────────────────────────────────────
        $totalPengajuan = PengajuanBelanja::count();
        $menungguApproval = PengajuanBelanja::where('status', 'diajukan')->count();
        $disetujui = PengajuanBelanja::where('status', 'disetujui')->count();
        $totalEstimasiBulanIni = PengajuanBelanja::whereMonth('tanggal_pengajuan', now()->month)
            ->whereYear('tanggal_pengajuan', now()->year)
            ->whereIn('status', ['diajukan', 'disetujui'])
            ->sum('estimasi_biaya');

        $kategoriList = KategoriPengeluaran::active()->orderBy('nama')->get();

        return view('admin.pengajuan_belanja.index', compact(
            'pengajuans',
            'totalPengajuan',
            'menungguApproval',
            'disetujui',
            'totalEstimasiBulanIni',
            'kategoriList'
        ));
    }

    /**
     * Form pengajuan belanja baru.
     */
    public function create()
    {
        $kodePengajuan = PengajuanBelanja::generateKodePengajuan();
        $kategoriList = KategoriPengeluaran::active()->orderBy('nama')->get();

        return view('admin.pengajuan_belanja.create', compact('kodePengajuan', 'kategoriList'));
    }

    /**
     * Simpan pengajuan belanja baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengajuan' => 'required|string|max:255',
            'kategori_id'    => 'required|exists:kategori_pengeluaran,id',
            'estimasi_biaya' => 'required|numeric|min:1000',
            'urgensi'        => 'required|in:biasa,mendesak,sangat_mendesak',
            'alasan'         => 'required|string|max:1000',
            'tanggal_pengajuan' => 'required|date',
            'lampiran'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        $validated['kode_pengajuan'] = PengajuanBelanja::generateKodePengajuan();
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'diajukan';

        if ($request->hasFile('lampiran')) {
            $validated['lampiran'] = $request->file('lampiran')->store('lampiran-pengajuan', 'public');
        }

        PengajuanBelanja::create($validated);

        return redirect()->route('admin.pengajuan_belanja.index')
            ->with('success', 'Pengajuan belanja berhasil dikirim dan menunggu persetujuan.');
    }

    /**
     * Ambil data detail pengajuan untuk modal (JSON).
     */
    public function show(PengajuanBelanja $pengajuanBelanja)
    {
        $pengajuanBelanja->load(['user', 'approver', 'kategoriPengeluaran', 'pengeluaran']);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pengajuanBelanja->id,
                'kode_pengajuan' => $pengajuanBelanja->kode_pengajuan,
                'nama_pengajuan' => $pengajuanBelanja->nama_pengajuan,
                'pemohon' => $pengajuanBelanja->user?->name ?? 'Petugas',
                'kategori' => $pengajuanBelanja->kategoriPengeluaran?->nama ?? '-',
                'estimasi_biaya' => $pengajuanBelanja->estimasi_biaya,
                'estimasi_biaya_format' => 'Rp ' . number_format($pengajuanBelanja->estimasi_biaya, 0, ',', '.'),
                'urgensi' => $pengajuanBelanja->urgensi,
                'urgensi_badge' => $pengajuanBelanja->urgensi_badge,
                'status' => $pengajuanBelanja->status,
                'status_badge' => $pengajuanBelanja->status_badge,
                'alasan' => $pengajuanBelanja->alasan,
                'tanggal_pengajuan' => $pengajuanBelanja->tanggal_pengajuan->format('d M Y'),
                'tanggal_disetujui' => $pengajuanBelanja->tanggal_disetujui ? $pengajuanBelanja->tanggal_disetujui->format('d M Y') : null,
                'approver' => $pengajuanBelanja->approver?->name ?? null,
                'catatan_approval' => $pengajuanBelanja->catatan_approval,
                'lampiran_url' => $pengajuanBelanja->lampiran ? asset('storage/' . $pengajuanBelanja->lampiran) : null,
                'pengeluaran' => $pengajuanBelanja->pengeluaran ? [
                    'id_transaksi' => $pengajuanBelanja->pengeluaran->id_transaksi,
                    'nominal' => 'Rp ' . number_format($pengajuanBelanja->pengeluaran->nominal, 0, ',', '.'),
                    'tanggal' => $pengajuanBelanja->pengeluaran->tanggal->format('d M Y'),
                ] : null,
            ]
        ]);
    }

    /**
     * Setujui atau Tolak Pengajuan Belanja (Admin Only).
     */
    public function updateStatus(Request $request, PengajuanBelanja $pengajuanBelanja)
    {
        $validated = $request->validate([
            'status'           => 'required|in:disetujui,ditolak',
            'catatan_approval' => 'nullable|string|max:500',
        ]);

        $pengajuanBelanja->update([
            'status'           => $validated['status'],
            'disetujui_oleh'   => Auth::id(),
            'tanggal_disetujui'=> now(),
            'catatan_approval' => $validated['catatan_approval'] ?? null,
        ]);

        $pesan = $validated['status'] === 'disetujui'
            ? "Pengajuan belanja {$pengajuanBelanja->kode_pengajuan} berhasil disetujui."
            : "Pengajuan belanja {$pengajuanBelanja->kode_pengajuan} telah ditolak.";

        return redirect()->back()->with('success', $pesan);
    }

    /**
     * Realisasikan Pengajuan Belanja yang Disetujui ke Pengeluaran Riil (1-Click Convert).
     */
    public function convertToPengeluaran(Request $request, PengajuanBelanja $pengajuanBelanja)
    {
        if ($pengajuanBelanja->status !== 'disetujui') {
            return redirect()->back()->with('error', 'Hanya pengajuan dengan status Disetujui yang dapat direalisasikan.');
        }

        $validated = $request->validate([
            'nominal_riil' => 'required|numeric|min:0',
            'tanggal'      => 'required|date',
            'keterangan'   => 'nullable|string|max:255',
            'bon_file'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:3072',
        ]);

        DB::beginTransaction();
        try {
            $bonPath = null;
            if ($request->hasFile('bon_file')) {
                $bonPath = $request->file('bon_file')->store('bon-pengeluaran', 'public');
            } elseif ($pengajuanBelanja->lampiran) {
                // Copy existing attachment from proposal
                $bonPath = $pengajuanBelanja->lampiran;
            }

            $kategori = KategoriPengeluaran::find($pengajuanBelanja->kategori_id);

            $pengeluaran = Pengeluaran::create([
                'id_transaksi' => Pengeluaran::generateIdTransaksi(),
                'nama'         => $pengajuanBelanja->nama_pengajuan,
                'kategori_id'  => $pengajuanBelanja->kategori_id,
                'kategori'     => $kategori?->nama ?? 'Operasional',
                'keterangan'   => $validated['keterangan'] ?? "Realisasi dari pengajuan {$pengajuanBelanja->kode_pengajuan}: " . $pengajuanBelanja->alasan,
                'tanggal'      => $validated['tanggal'],
                'nominal'      => $validated['nominal_riil'],
                'bon_file'     => $bonPath,
            ]);

            $pengajuanBelanja->update([
                'status'         => 'selesai',
                'pengeluaran_id' => $pengeluaran->id,
            ]);

            DB::commit();

            return redirect()->route('admin.pengeluaran.index')
                ->with('success', "Pengajuan {$pengajuanBelanja->kode_pengajuan} berhasil direalisasikan ke Pengeluaran Riil ({$pengeluaran->id_transaksi}).");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal merealisasikan pengeluaran: ' . $e->getMessage());
        }
    }

    /**
     * Hapus pengajuan belanja.
     */
    public function destroy(PengajuanBelanja $pengajuanBelanja)
    {
        if ($pengajuanBelanja->lampiran && Storage::disk('public')->exists($pengajuanBelanja->lampiran)) {
            Storage::disk('public')->delete($pengajuanBelanja->lampiran);
        }

        $pengajuanBelanja->delete();

        return redirect()->back()->with('success', 'Pengajuan belanja berhasil dihapus.');
    }
}
