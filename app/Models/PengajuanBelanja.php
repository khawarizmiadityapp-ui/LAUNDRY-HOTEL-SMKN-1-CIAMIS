<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanBelanja extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_belanjas';

    protected $fillable = [
        'kode_pengajuan',
        'user_id',
        'nama_pengajuan',
        'kategori_id',
        'estimasi_biaya',
        'urgensi',
        'status',
        'alasan',
        'tanggal_pengajuan',
        'tanggal_disetujui',
        'disetujui_oleh',
        'catatan_approval',
        'lampiran',
        'pengeluaran_id',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'tanggal_disetujui' => 'date',
        'estimasi_biaya'    => 'integer',
    ];

    // ─── RELATIONSHIPS ──────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    public function kategoriPengeluaran()
    {
        return $this->belongsTo(KategoriPengeluaran::class, 'kategori_id');
    }

    public function pengeluaran()
    {
        return $this->belongsTo(Pengeluaran::class, 'pengeluaran_id');
    }

    // ─── SCOPES ─────────────────────────────────────────────────────────

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeUrgensi($query, $urgensi)
    {
        return $query->where('urgensi', $urgensi);
    }

    public function scopeDateRange($query, $dari, $sampai)
    {
        if ($dari)   $query->whereDate('tanggal_pengajuan', '>=', $dari);
        if ($sampai) $query->whereDate('tanggal_pengajuan', '<=', $sampai);
        return $query;
    }

    // ─── METHODS ────────────────────────────────────────────────────────

    public static function generateKodePengajuan(): string
    {
        $prefix = 'REQ-' . date('Ym') . '-';
        $count = static::whereYear('tanggal_pengajuan', now()->year)
            ->whereMonth('tanggal_pengajuan', now()->month)
            ->count() + 1;

        $kode = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);

        while (static::where('kode_pengajuan', $kode)->exists()) {
            $count++;
            $kode = $prefix . str_pad($count, 3, '0', STR_PAD_LEFT);
        }

        return $kode;
    }

    public function getUrgensiBadgeAttribute(): array
    {
        return match ($this->urgensi) {
            'sangat_mendesak' => ['label' => 'Sangat Mendesak', 'class' => 'bg-rose-100 text-rose-700 border-rose-200'],
            'mendesak'        => ['label' => 'Mendesak', 'class' => 'bg-amber-100 text-amber-700 border-amber-200'],
            default           => ['label' => 'Biasa', 'class' => 'bg-slate-100 text-slate-700 border-slate-200'],
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'diajukan'  => ['label' => 'Menunggu Approval', 'class' => 'bg-amber-50 text-amber-600 border-amber-200'],
            'disetujui' => ['label' => 'Disetujui', 'class' => 'bg-blue-50 text-blue-600 border-blue-200'],
            'ditolak'   => ['label' => 'Ditolak', 'class' => 'bg-rose-50 text-rose-600 border-rose-200'],
            'selesai'   => ['label' => 'Direalisasikan', 'class' => 'bg-emerald-50 text-emerald-600 border-emerald-200'],
            default     => ['label' => ucfirst($this->status), 'class' => 'bg-slate-50 text-slate-600 border-slate-200'],
        };
    }
}
