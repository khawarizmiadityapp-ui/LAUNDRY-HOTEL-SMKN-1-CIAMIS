<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaundryTask extends Model
{
    protected $fillable = [
        'transaksi_id',
        'stage',
        'petugas_id',
        'petugas_name',
        'status',
        'completed_at',
        'notes'
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    // Backward compatibility: existing views/controllers may still reference $task->task_type.
    public function getTaskTypeAttribute()
    {
        return $this->stage;
    }

    public function setTaskTypeAttribute($value)
    {
        $this->attributes['stage'] = $value;
    }


    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }
}
