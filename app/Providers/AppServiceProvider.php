<?php

namespace App\Providers;

use App\Models\Petugas;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('admin.sidebar', function ($view) {
            try {
                $activePetugas = Petugas::query()
                    ->where('status', 'Aktif')
                    ->orderBy('nama')
                    ->get(['id', 'nama', 'shift']);

                $view->with('sidebarOnDutyCount', $activePetugas->count());
                $view->with('sidebarOnDutyPetugas', $activePetugas->take(5));
            } catch (Throwable $e) {
                $view->with('sidebarOnDutyCount', 0);
                $view->with('sidebarOnDutyPetugas', collect());
            }
        });
    }
}
