<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'id_petugas',
        'role',
        'status',
        'shift',
    ];

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }
}
