<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'kategori',
        'harga',
        'estimasi',
        'status',
        'badge',
        'icon',
    ];

    protected $casts = [
        'status' => 'boolean',
        'harga'  => 'decimal:2',
    ];

    // Scope: hanya yang aktif
    public function scopeAktif($query)
    {
        return $query->where('status', true);
    }

    // Scope: filter kategori
    public function scopeKategori($query, $kategori)
    {
        return $query->where('kategori', $kategori);
    }

    // Format harga ke Rupiah
    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Satuan harga (per kg / per pcs)
    public function getSatuanAttribute(): string
    {
        return $this->kategori === 'kiloan' ? '/kg' : '/pcs';
    }
}
