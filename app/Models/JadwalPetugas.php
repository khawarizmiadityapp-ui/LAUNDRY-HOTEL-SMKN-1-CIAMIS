<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JadwalPetugas extends Model
{
    use HasFactory;

    protected $table = 'jadwal_petugas';

    protected $fillable = [
        'tanggal',
        'nama',
        'id_petugas',
        'shift',
        'selected_station',
        'checked_in_at',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'checked_in_at' => 'datetime',
    ];

    /**
     * Scope untuk mengambil jadwal hari ini
     */
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal', Carbon::today());
    }

    /**
     * Scope untuk mengambil jadwal berdasarkan stasiun kerja
     */
    public function scopeStation($query, string $station)
    {
        return $query->where('selected_station', $station);
    }

    /**
     * Scope untuk mengambil yang sudah check-in
     */
    public function scopeCheckedIn($query)
    {
        return $query->whereNotNull('checked_in_at')
                     ->where('selected_station', '!=', 'none');
    }
}
