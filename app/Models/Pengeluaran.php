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
        'kategori', // Legacy - akan di-phase out
        'kategori_id', // New - relasi ke tabel kategori_pengeluaran
        'keterangan',
        'tanggal',
        'nominal',
        'bon_file',
    ];

    protected $casts = [
        'tanggal'  => 'date',
        'nominal'  => 'integer',
    ];

    // ─── RELATIONSHIPS ──────────────────────────────────────────────────

    // Relasi ke KategoriPengeluaran
    public function kategoriPengeluaran()
    {
        return $this->belongsTo(KategoriPengeluaran::class, 'kategori_id');
    }

    // ─── SCOPES ─────────────────────────────────────────────────────────

    // Scope: filter by status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Scope: filter by kategori (using kategori_id)
    public function scopeKategori($query, $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    // Scope: filter by date range
    public function scopeDateRange($query, $dari, $sampai)
    {
        if ($dari)    $query->whereDate('tanggal', '>=', $dari);
        if ($sampai)  $query->whereDate('tanggal', '<=', $sampai);
        return $query;
    }

    // ─── METHODS ────────────────────────────────────────────────────────

    // Auto-generate ID transaksi
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

    // Accessor untuk nama kategori (backward compatibility)
    public function getKategoriNamaAttribute()
    {
        return $this->kategoriPengeluaran?->nama ?? $this->kategori;
    }
}
