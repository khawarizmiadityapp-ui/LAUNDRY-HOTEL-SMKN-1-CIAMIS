<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ErrorLoggingService
{
    /**
     * Log error with context
     */
    public function logError(\Exception $exception, string $context = '', array $additionalData = [])
    {
        $logData = [
            'severity' => $this->getSeverity($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'context' => $context,
            'user_id' => Auth::id(),
            'user_email' => Auth::check() ? Auth::user()->email : null,
            'ip_address' => Request::ip(),
            'url' => Request::fullUrl(),
            'method' => Request::method(),
            'timestamp' => now()->toIso8601String(),
            'additional_data' => $additionalData,
        ];

        // Log to Laravel's default log
        Log::error($context . ': ' . $exception->getMessage(), $logData);

        // Store in database for dashboard
        $this->storeErrorLog($logData);

        // Send notification for critical errors
        if ($this->isCritical($exception)) {
            $this->sendCriticalAlert($logData);
        }

        return $logData;
    }

    /**
     * Log warning with context
     */
    public function logWarning(string $message, array $data = [])
    {
        $logData = [
            'severity' => 'WARNING',
            'message' => $message,
            'user_id' => Auth::id(),
            'user_email' => Auth::check() ? Auth::user()->email : null,
            'ip_address' => Request::ip(),
            'url' => Request::fullUrl(),
            'timestamp' => now()->toIso8601String(),
            'data' => $data,
        ];

        Log::warning($message, $logData);
        $this->storeErrorLog($logData);

        return $logData;
    }

    /**
     * Log info with context
     */
    public function logInfo(string $message, array $data = [])
    {
        $logData = [
            'severity' => 'INFO',
            'message' => $message,
            'user_id' => Auth::id(),
            'user_email' => Auth::check() ? Auth::user()->email : null,
            'timestamp' => now()->toIso8601String(),
            'data' => $data,
        ];

        Log::info($message, $logData);
        $this->storeErrorLog($logData);

        return $logData;
    }

    /**
     * Determine error severity
     */
    private function getSeverity(\Exception $exception): string
    {
        if ($this->isCritical($exception)) {
            return 'CRITICAL';
        }

        if ($exception instanceof \Illuminate\Database\QueryException) {
            return 'ERROR';
        }

        if ($exception instanceof \Illuminate\Validation\ValidationException) {
            return 'WARNING';
        }

        return 'ERROR';
    }

    /**
     * Check if error is critical
     */
    private function isCritical(\Exception $exception): bool
    {
        $criticalPatterns = [
            'database',
            'connection',
            'timeout',
            'memory',
            'disk',
            'permission',
            'authentication',
        ];

        $message = strtolower($exception->getMessage());
        foreach ($criticalPatterns as $pattern) {
            if (strpos($message, $pattern) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Store error log in database
     */
    private function storeErrorLog(array $logData): void
    {
        try {
            \App\Models\ErrorLog::create([
                'severity' => $logData['severity'],
                'message' => $logData['message'],
                'file' => $logData['file'] ?? null,
                'line' => $logData['line'] ?? null,
                'trace' => $logData['trace'] ?? null,
                'context' => $logData['context'] ?? null,
                'user_id' => $logData['user_id'],
                'user_email' => $logData['user_email'],
                'ip_address' => $logData['ip_address'],
                'url' => $logData['url'],
                'method' => $logData['method'],
                'additional_data' => json_encode($logData['additional_data'] ?? []),
            ]);
        } catch (\Exception $e) {
            // If database logging fails, just log to file
            Log::error('Failed to store error log in database: ' . $e->getMessage());
        }
    }

    /**
     * Send critical error alert
     */
    private function sendCriticalAlert(array $logData): void
    {
        try {
            // Send email to admin
            $adminEmail = config('app.admin_email', 'admin@example.com');
            
            \Illuminate\Support\Facades\Mail::raw(
                "CRITICAL ERROR ALERT\n\n" .
                "Message: {$logData['message']}\n" .
                "Context: {$logData['context']}\n" .
                "User: {$logData['user_email']}\n" .
                "URL: {$logData['url']}\n" .
                "Time: {$logData['timestamp']}\n" .
                "File: {$logData['file']}:{$logData['line']}",
                function ($message) use ($adminEmail) {
                    $message->to($adminEmail)
                        ->subject('CRITICAL ERROR: Laundry Management System')
                        ->from(config('mail.from.address', config('mail.username')));
                }
            );
        } catch (\Exception $e) {
            Log::error('Failed to send critical error alert: ' . $e->getMessage());
        }
    }

    /**
     * Get error statistics
     */
    public function getErrorStats(int $days = 7): array
    {
        try {
            $stats = \App\Models\ErrorLog::where('created_at', '>=', now()->subDays($days))
                ->selectRaw('
                    severity,
                    COUNT(*) as count,
                    COUNT(DISTINCT user_id) as affected_users
                ')
                ->groupBy('severity')
                ->get()
                ->keyBy('severity');

            return [
                'total' => \App\Models\ErrorLog::where('created_at', '>=', now()->subDays($days))->count(),
                'by_severity' => $stats,
                'critical_count' => $stats->get('CRITICAL')?->count ?? 0,
                'error_count' => $stats->get('ERROR')?->count ?? 0,
                'warning_count' => $stats->get('WARNING')?->count ?? 0,
                'info_count' => $stats->get('INFO')?->count ?? 0,
            ];
        } catch (\Exception $e) {
            return [
                'total' => 0,
                'by_severity' => collect(),
                'critical_count' => 0,
                'error_count' => 0,
                'warning_count' => 0,
                'info_count' => 0,
            ];
        }
    }

    /**
     * Get recent errors
     */
    public function getRecentErrors(int $limit = 50): \Illuminate\Database\Eloquent\Collection
    {
        try {
            return \App\Models\ErrorLog::with('user')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            return collect();
        }
    }
}
