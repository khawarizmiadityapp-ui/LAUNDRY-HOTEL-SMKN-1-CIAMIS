<?php

namespace App\Observers;

use App\Models\Transaksi;
use Illuminate\Support\Facades\Cache;

class TransaksiObserver
{
    /**
     * Clear dashboard caches.
     */
    private function clearDashboardCache()
    {
        Cache::forget('dashboard_stats');
        Cache::forget('dashboard_chart_data');
        Cache::forget('dashboard_recent_transactions');
    }

    /**
     * Handle the Transaksi "created" event.
     */
    public function created(Transaksi $transaksi): void
    {
        $this->clearDashboardCache();
    }

    /**
     * Handle the Transaksi "updated" event.
     */
    public function updated(Transaksi $transaksi): void
    {
        $this->clearDashboardCache();
    }

    /**
     * Handle the Transaksi "deleted" event.
     */
    public function deleted(Transaksi $transaksi): void
    {
        $this->clearDashboardCache();
    }
}
