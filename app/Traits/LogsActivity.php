<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Log a custom activity.
     */
    public function logActivity(string $description, string $event = 'custom', array $properties = [])
    {
        return ActivityLog::create([
            'log_name' => 'default',
            'description' => $description,
            'subject_type' => get_class($this),
            'subject_id' => $this->getKey(),
            'event' => $event,
            'causer_type' => Auth::check() ? get_class(Auth::user()) : null,
            'causer_id' => Auth::id(),
            'properties' => $properties,
        ]);
    }
}
