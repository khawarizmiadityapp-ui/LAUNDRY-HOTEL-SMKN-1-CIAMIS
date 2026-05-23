<?php

$dbPath = dirname(__DIR__) . '/database/database.sqlite';
if (!file_exists($dbPath) || filesize($dbPath) < 100) {
    @file_put_contents($dbPath, '');
}

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register custom middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureUserIsAdmin::class,
        ]);
    })
    ->withSchedule(function ($schedule): void {
        // Automated Backups - Daily at 2 AM
        $schedule->command('backup:clean')->daily()->at('01:00');
        $schedule->command('backup:run')->daily()->at('02:00');

        // Monitor backup health - Daily at 3 AM
        $schedule->command('backup:monitor')->daily()->at('03:00');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Custom exception handling for API requests
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Terjadi kesalahan sistem',
                    'message' => app()->environment('production') ? null : $e->getMessage(),
                ], 500);
            }
        });

        // Log all exceptions
        $exceptions->report(function (Throwable $e) {
            \Log::error('Unhandled Exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        });
    })->create();
