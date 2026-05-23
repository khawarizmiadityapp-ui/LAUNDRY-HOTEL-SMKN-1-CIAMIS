<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $table = 'activity_log';

    protected $fillable = [
        'log_name',
        'description',
        'subject_type', 'subject_id',
        'event',
        'causer_type', 'causer_id',
        'attribute_changes',
        'properties'
    ];

    protected $casts = [
        'attribute_changes' => 'array',
        'properties' => 'array',
    ];

    public function causer()
    {
        return $this->morphTo();
    }

    public function subject()
    {
        return $this->morphTo();
    }
}
