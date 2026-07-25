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
        'needs_washing',
        'needs_ironing',
        'needs_packing',
    ];

    protected $casts = [
        'status' => 'boolean',
        'harga'  => 'decimal:2',
        'estimasi' => 'integer',
        'needs_washing' => 'boolean',
        'needs_ironing' => 'boolean',
        'needs_packing' => 'boolean',
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

    /**
     * Get human-readable estimasi label.
     * e.g. 24 → "1×24 Jam", 48 → "2×24 Jam", 6 → "6 Jam"
     */
    public function getEstimasiLabelAttribute(): ?string
    {
        if (!$this->estimasi) return null;

        $jam = (int) $this->estimasi;

        if ($jam >= 24 && $jam % 24 === 0) {
            $hari = $jam / 24;
            return "{$hari}×24 Jam";
        }

        return "{$jam} Jam";
    }

    /**
     * Calculate estimated completion datetime from a given start time.
     *
     * @param \Carbon\Carbon|null $startTime
     * @return \Carbon\Carbon|null
     */
    public function estimasiSelesai($startTime = null): ?\Carbon\Carbon
    {
        if (!$this->estimasi) return null;

        $start = $startTime ?? now();
        return $start->copy()->addHours($this->estimasi);
    }

    /**
     * Get all transaksi details using this layanan
     */
    public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class);
    }
}
