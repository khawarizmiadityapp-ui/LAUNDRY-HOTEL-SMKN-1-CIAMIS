<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    public const KATEGORI_DIIZINKAN = [
        'Operasional',
        'Bahan Kimia & Sabun',
        'Listrik & Air',
    ];

    protected $table = 'pengeluarans';

    protected $fillable = [
        'id_transaksi',
        'nama',
        'kategori',
        'keterangan',
        'tanggal',
        'nominal',
        'status',
        'bon_file',
    ];

    protected $casts = [
        'tanggal'  => 'date',
        'nominal'  => 'integer',
    ];

    // ─── Scope: filter by status ───────────────────────────────────────
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // ─── Scope: filter by kategori ─────────────────────────────────────
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // ─── Scope: filter by date range ───────────────────────────────────
    public function scopeDateRange($query, $dari, $sampai)
    {
        if ($dari)    $query->whereDate('tanggal', '>=', $dari);
        if ($sampai)  $query->whereDate('tanggal', '<=', $sampai);
        return $query;
    }

    // ─── Auto-generate ID transaksi ────────────────────────────────────
    public static function generateIdTransaksi(): string
    {
        $num = 2401;
        $last = static::orderByDesc('id')->first();
        if ($last && $last->id_transaksi) {
            // Parse EXP-2401 -> 2401
            $parts = explode('-', $last->id_transaksi);
            $num = (isset($parts[1]) && is_numeric($parts[1])) ? (int)$parts[1] + 1 : 2401;
        }

        while (static::where('id_transaksi', 'EXP-' . $num)->exists()) {
            $num++;
        }

        return 'EXP-' . $num;
    }
}
