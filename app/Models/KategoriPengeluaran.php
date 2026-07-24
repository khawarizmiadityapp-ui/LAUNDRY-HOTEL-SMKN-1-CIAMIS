<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriPengeluaran extends Model
{
    use HasFactory;

    protected $table = 'kategori_pengeluaran';

    protected $fillable = [
        'nama',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relasi ke Pengeluaran
    public function pengeluarans()
    {
        return $this->hasMany(Pengeluaran::class, 'kategori_id');
    }

    // Scope untuk kategori aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Cek apakah kategori masih digunakan
    public function isUsed()
    {
        return $this->pengeluarans()->exists();
    }
}
