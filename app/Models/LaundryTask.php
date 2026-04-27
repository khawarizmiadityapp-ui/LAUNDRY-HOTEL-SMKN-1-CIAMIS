<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaundryTask extends Model
{
    protected $fillable = [
        'transaksi_id',
        'task_type',
        'petugas_id',
        'status',
        'completed_at',
        'notes'
    ];

    protected $casts = [
        'completed_at' => 'datetime'
    ];

    // Backward compatibility: existing views/controllers may still reference $task->stage.
    public function getStageAttribute()
    {
        return $this->task_type;
    }

    public function setStageAttribute($value)
    {
        $this->attributes['task_type'] = $value;
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
