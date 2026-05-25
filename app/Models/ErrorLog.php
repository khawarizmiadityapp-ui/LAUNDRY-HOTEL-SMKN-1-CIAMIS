<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ErrorLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'severity',
        'message',
        'file',
        'line',
        'trace',
        'context',
        'user_id',
        'user_email',
        'ip_address',
        'url',
        'method',
        'additional_data',
        'resolved',
        'resolved_at',
        'resolved_by',
        'resolution_notes',
    ];

    protected $casts = [
        'additional_data' => 'array',
        'resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the user that caused the error.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that resolved the error.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Scope a query to only include critical errors.
     */
    public function scopeCritical($query)
    {
        return $query->where('severity', 'CRITICAL');
    }

    /**
     * Scope a query to only include unresolved errors.
     */
    public function scopeUnresolved($query)
    {
        return $query->where('resolved', false);
    }

    /**
     * Scope a query to only include errors from the last N days.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Mark error as resolved.
     */
    public function markAsResolved(string $notes = null): bool
    {
        return $this->update([
            'resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => auth()->id(),
            'resolution_notes' => $notes,
        ]);
    }

    /**
     * Get severity color class.
     */
    public function getSeverityColorAttribute(): string
    {
        return match($this->severity) {
            'CRITICAL' => 'red',
            'ERROR' => 'orange',
            'WARNING' => 'yellow',
            'INFO' => 'blue',
            default => 'gray',
        };
    }

    /**
     * Get severity badge class.
     */
    public function getSeverityBadgeClassAttribute(): string
    {
        return match($this->severity) {
            'CRITICAL' => 'bg-red-100 text-red-700 border-red-200',
            'ERROR' => 'bg-orange-100 text-orange-700 border-orange-200',
            'WARNING' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'INFO' => 'bg-blue-100 text-blue-700 border-blue-200',
            default => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    }
}
