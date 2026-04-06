<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengeluaran extends Model
{
    use HasFactory;

    protected $table = 'pengeluarans';

    protected $fillable = [
        'id_transaksi',
        'nama',
        'kategori',
        'keterangan',
        'tanggal',
        'nominal',
        'status',
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
        $last = static::orderByDesc('id')->first();
        $num  = $last ? (intval(substr($last->id_transaksi, 4)) + 1) : 2401;
        return 'EXP-' . $num;
    }
}