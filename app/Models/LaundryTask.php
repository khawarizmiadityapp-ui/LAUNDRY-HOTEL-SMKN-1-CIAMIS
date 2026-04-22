<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaundryTask extends Model
{
    protected $fillable = [
        'transaksi_id',
        'stage',
        'petugas_id',
        'status',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
