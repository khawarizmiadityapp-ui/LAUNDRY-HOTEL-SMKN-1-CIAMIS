<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
// Pembayaran model currently not implemented
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    /**
     * Check if user has access to the transaction.
     * Only admin or staff can access all transactions.
     * Regular users can only access their own transactions.
     */
    private function checkTransactionAccess($transaksi)
    {
        $user = Auth::user();
        
        // Admin and staff can access all transactions
        if (in_array($user->role, ['admin', 'staff'])) {
            return true;
        }
        
        // Regular users can only access their own transactions
        // This assumes there's a relationship between user and customer
        // Adjust this logic based on your actual data model
        if ($transaksi->customer_id === $user->customer_id ?? null) {
            return true;
        }
        
        return false;
    }

    public function index(Request $request)
    {
        // Query transaksi dengan user
        $query = Transaksi::with(['user'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status pembayaran
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // Filter berdasarkan search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('transaksi_code', 'like', "%{$search}%");
            });
        }

        $transactions = $query->paginate(10)->appends($request->query());

        // Statistik Real dari Database
        $today = Carbon::today();
        $startOfDay = Carbon::today()->startOfDay();
        $endOfDay = Carbon::today()->endOfDay();

        // Total pendapatan hari ini (transaksi lunas hari ini)
        $totalPendapatanHariIni = Transaksi::where('payment_status', 'lunas')
            ->whereBetween('updated_at', [$startOfDay, $endOfDay])
            ->sum('total_price');

        // Transaksi belum lunas
        $transaksiBelumLunas = Transaksi::where('payment_status', 'belum_bayar')->count();

        // Total transaksi
        $totalTransaksi = Transaksi::count();

        // Metode pembayaran populer (jika ada tabel pembayaran)
        $metodePopulerNama = 'QRIS'; // Default
        $persentaseMetodePopuler = 0;

        if ($totalTransaksi > 0) {
            // Ambil metode paling sering dari transaksi
            $metodePopuler = Transaksi::whereNotNull('payment_method')
                ->select('payment_method', DB::raw('COUNT(*) as count'))
                ->groupBy('payment_method')
                ->orderByDesc('count')
                ->first();

            if ($metodePopuler) {
                $metodePopulerNama = ucfirst($metodePopuler->payment_method);
                $persentaseMetodePopuler = round(($metodePopuler->count / $totalTransaksi) * 100);
            }
        }

        return view('admin.pembayaran.index', compact(
            'transactions',
            'totalPendapatanHariIni',
            'transaksiBelumLunas',
            'metodePopulerNama',
            'persentaseMetodePopuler'
        ));
    }

    public function create()
    {
        // Ambil transaksi yang belum lunas untuk ditampilkan di form
        $transaksiBelumLunas = Transaksi::where('payment_status', 'belum_bayar')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pembayaran.create', compact('transaksiBelumLunas'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'transaksi_id' => 'required|string|exists:transaksi,transaksi_code',
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|in:Tunai,QRIS,Transfer BCA,Transfer Mandiri,Transfer BRI,E-Wallet',
            'tanggal_bayar' => 'required|date',
            'status_pembayaran' => 'required|string|in:Lunas,Belum Lunas,Cicilan',
            'catatan' => 'nullable|string|max:500',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        DB::beginTransaction();

        try {
            // Cari transaksi
            $transaksi = Transaksi::where('transaksi_code', $validated['transaksi_id'])->firstOrFail();

            // Authorization check: only admin and staff can process payments
            // This prevents IDOR (Insecure Direct Object Reference) attacks
            $user = Auth::user();
            if (!in_array($user->role, ['admin', 'staff'])) {
                DB::rollBack();
                abort(403, 'Unauthorized. Only admin and staff can process payments.');
            }

            // Handle upload bukti pembayaran
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')->store('bukti-pembayaran', 'public');
            }

            // Validasi jumlah_bayar vs total_price
            if ($validated['status_pembayaran'] === 'Lunas' && $validated['jumlah_bayar'] < $transaksi->total_price) {
                DB::rollBack();
                // Hapus file yang baru saja diupload jika validasi gagal
                if ($buktiPath && Storage::disk('public')->exists($buktiPath)) {
                    Storage::disk('public')->delete($buktiPath);
                }
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Jumlah bayar (Rp ' . number_format($validated['jumlah_bayar'], 0, ',', '.') . ') kurang dari total tagihan (Rp ' . number_format($transaksi->total_price, 0, ',', '.') . '). Transaksi tidak bisa dilunasi.');
            }

            // Update status pembayaran transaksi
            $updateData = [];
            
            if ($validated['status_pembayaran'] === 'Lunas') {
                $updateData['payment_status'] = 'lunas';
                $updateData['payment_method'] = strtolower(str_replace(' ', '_', $validated['metode_pembayaran']));
            }
            
            if ($buktiPath) {
                $updateData['bukti_pembayaran'] = $buktiPath;
            }
            
            if (!empty($updateData)) {
                $transaksi->update($updateData);
            }

            // TODO: Jika ada tabel pembayaran terpisah, simpan juga ke sana
            // Pembayaran::create([...]);

            DB::commit();

            return redirect()->route('admin.pembayaran.index')
                ->with('success', 'Pembayaran berhasil dicatat! Transaksi #' . $transaksi->transaksi_code . ' telah diupdate.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Hapus file yang terlanjur diupload jika transaksi database gagal
            if (isset($buktiPath) && Storage::disk('public')->exists($buktiPath)) {
                Storage::disk('public')->delete($buktiPath);
            }

            Log::error('Pembayaran store failed', [
                'error' => $e->getMessage(),
                'transaksi_id' => $validated['transaksi_id'],
                'user_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal mencatat pembayaran: ' . $e->getMessage());
        }
    }
}
