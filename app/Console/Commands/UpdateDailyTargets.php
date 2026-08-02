<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DailyTarget;
use App\Models\Transaksi;
use App\Models\Pengeluaran;
use Carbon\Carbon;

class UpdateDailyTargets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily-targets:update {--date= : Specific date to update (YYYY-MM-DD)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update daily target actuals and carry forward deficit to next day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->option('date') ? Carbon::parse($this->option('date')) : Carbon::yesterday();
        
        $this->info("Updating daily targets for: " . $date->toDateString());
        
        // Get or create target for the date
        $target = DailyTarget::getOrCreateForDate($date);
        
        // Calculate actual income and expense
        $income = Transaksi::where('payment_status', 'lunas')
            ->whereDate('created_at', $date)
            ->sum('total_price');
        
        $expense = Pengeluaran::whereDate('tanggal', $date)->sum('nominal');
        
        // Update actuals
        $target->updateActuals($income, $expense);
        
        $this->info("✓ Updated actuals for {$date->toDateString()}");
        $this->line("  Income: Rp " . number_format($income, 0, ',', '.'));
        $this->line("  Expense: Rp " . number_format($expense, 0, ',', '.'));
        $this->line("  Net: Rp " . number_format($target->net_income, 0, ',', '.'));
        $this->line("  Target: Rp " . number_format($target->adjusted_target, 0, ',', '.'));
        $this->line("  Variance: Rp " . number_format($target->variance, 0, ',', '.'));
        
        // Carry forward deficit to next day if needed
        if ($target->variance < 0) {
            $nextTarget = $target->carryForwardToNextDay();
            if ($nextTarget) {
                $this->warn("! Deficit Rp " . number_format(abs($target->variance), 0, ',', '.') . " carried forward to " . $nextTarget->date->toDateString());
                $this->line("  New target for next day: Rp " . number_format($nextTarget->adjusted_target, 0, ',', '.'));
            }
        } else {
            $this->info("✓ Target achieved! Surplus: Rp " . number_format($target->variance, 0, ',', '.'));
        }
        
        $this->newLine();
        $this->info("Daily target update completed successfully!");
        
        return Command::SUCCESS;
    }
}
