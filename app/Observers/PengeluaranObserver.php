<?php

namespace App\Observers;

use App\Models\Pengeluaran;
use Illuminate\Support\Facades\Cache;

class PengeluaranObserver
{
    /**
     * Clear dashboard caches.
     */
    private function clearDashboardCache()
    {
        Cache::forget('dashboard_stats');
        Cache::forget('dashboard_chart_data');
    }

    /**
     * Handle the Pengeluaran "created" event.
     */
    public function created(Pengeluaran $pengeluaran): void
    {
        $this->clearDashboardCache();
    }

    /**
     * Handle the Pengeluaran "updated" event.
     */
    public function updated(Pengeluaran $pengeluaran): void
    {
        $this->clearDashboardCache();
    }

    /**
     * Handle the Pengeluaran "deleted" event.
     */
    public function deleted(Pengeluaran $pengeluaran): void
    {
        $this->clearDashboardCache();
    }
}
