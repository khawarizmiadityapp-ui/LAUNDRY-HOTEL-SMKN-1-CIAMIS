<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DailyTarget extends Model
{
    protected $fillable = [
        'date',
        'base_target',
        'carry_forward',
        'adjusted_target',
        'actual_income',
        'actual_expense',
        'net_income',
        'variance',
        'is_achieved',
    ];

    protected $casts = [
        'date' => 'date',
        'is_achieved' => 'boolean',
    ];

    /**
     * Get or create daily target for a specific date
     */
    public static function getOrCreateForDate(Carbon $date)
    {
        $dateString = $date->toDateString();
        
        $target = self::firstOrCreate(
            ['date' => $dateString],
            [
                'base_target' => self::calculateBaseTarget(),
                'carry_forward' => 0,
                'adjusted_target' => self::calculateBaseTarget(),
                'actual_income' => 0,
                'actual_expense' => 0,
                'net_income' => 0,
                'variance' => 0,
                'is_achieved' => false,
            ]
        );

        return $target;
    }

    /**
     * Get target days in month (custom setting or actual days in month)
     */
    public static function getTargetDaysInMonth($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $customDays = env('TARGET_DAYS_PER_MONTH');
        if ($customDays && is_numeric($customDays) && (int) $customDays > 0) {
            return (int) $customDays;
        }
        return $date->daysInMonth;
    }

    /**
     * Calculate base daily target from monthly target
     */
    public static function calculateBaseTarget($date = null)
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $monthlyTarget = self::getMonthlyTarget();
        $daysInMonth = self::getTargetDaysInMonth($date);
        
        return (int) ceil($monthlyTarget / $daysInMonth);
    }

    /**
     * Get configured monthly target
     */
    public static function getMonthlyTarget()
    {
        if (env('MONTHLY_INCOME_LIMIT')) {
            return (int) env('MONTHLY_INCOME_LIMIT');
        }
        if (env('ANNUAL_INCOME_LIMIT')) {
            return (int) ceil(((int) env('ANNUAL_INCOME_LIMIT')) / 12);
        }
        return 50000000;
    }

    /**
     * Get configured annual target
     */
    public static function getAnnualTarget()
    {
        if (env('ANNUAL_INCOME_LIMIT')) {
            return (int) env('ANNUAL_INCOME_LIMIT');
        }
        return self::getMonthlyTarget() * 12;
    }

    /**
     * Recalculate daily targets for a given month sequentially
     * to apply carry-forward deficits accurately.
     */
    public static function recalculateMonthTargets($year = null, $month = null)
    {
        $year = $year ?? Carbon::now()->year;
        $month = $month ?? Carbon::now()->month;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $calendarDaysInMonth = $startDate->daysInMonth;
        $targetDaysInMonth = self::getTargetDaysInMonth($startDate);
        
        $monthlyTarget = self::getMonthlyTarget();
        $baseDailyTarget = (int) ceil($monthlyTarget / $targetDaysInMonth);

        $runningDeficit = 0;
        $results = collect();

        for ($day = 1; $day <= $calendarDaysInMonth; $day++) {
            $currentDate = Carbon::createFromDate($year, $month, $day)->startOfDay();
            $dateString = $currentDate->toDateString();

            // Fetch actual income and expenses for current date
            $income = Transaksi::where('payment_status', 'lunas')
                ->whereDate('created_at', $currentDate)
                ->sum('total_price');

            $expense = Pengeluaran::whereDate('tanggal', $currentDate)
                ->sum('nominal');

            $carryForward = $runningDeficit;
            $adjustedTarget = $baseDailyTarget + $carryForward;
            $netIncome = $income - $expense;
            $variance = $netIncome - $adjustedTarget;
            $isAchieved = $netIncome >= $adjustedTarget;

            // Carry forward deficit to next day if net income is below adjusted target
            if ($variance < 0) {
                $runningDeficit = abs($variance);
            } else {
                $runningDeficit = 0; // Reset deficit when target achieved or surplus
            }

            $targetRecord = self::updateOrCreate(
                ['date' => $dateString],
                [
                    'base_target' => $baseDailyTarget,
                    'carry_forward' => $carryForward,
                    'adjusted_target' => $adjustedTarget,
                    'actual_income' => $income,
                    'actual_expense' => $expense,
                    'net_income' => $netIncome,
                    'variance' => $variance,
                    'is_achieved' => $isAchieved,
                ]
            );

            $results->push($targetRecord);
        }

        return $results;
    }

    /**
     * Update today's actual values and calculate variance
     */
    public function updateActuals($income, $expense)
    {
        $netIncome = $income - $expense;
        $variance = $netIncome - $this->adjusted_target;
        
        $this->update([
            'actual_income' => $income,
            'actual_expense' => $expense,
            'net_income' => $netIncome,
            'variance' => $variance,
            'is_achieved' => $netIncome >= $this->adjusted_target,
        ]);

        return $this;
    }

    /**
     * Carry forward deficit/surplus to next day
     */
    public function carryForwardToNextDay()
    {
        $nextDate = Carbon::parse($this->date)->addDay();
        
        // Don't carry forward to next month
        if ($nextDate->month !== Carbon::parse($this->date)->month) {
            return null;
        }

        $nextDayTarget = self::getOrCreateForDate($nextDate);
        
        // Only carry forward if there's a deficit (variance is negative)
        if ($this->variance < 0) {
            $carryAmount = abs($this->variance);
            $newAdjustedTarget = $nextDayTarget->base_target + $carryAmount;
            
            $nextDayTarget->update([
                'carry_forward' => $carryAmount,
                'adjusted_target' => $newAdjustedTarget,
            ]);
        }

        return $nextDayTarget;
    }

    /**
     * Get current achievement percentage
     */
    public function getAchievementPercentageAttribute()
    {
        if ($this->adjusted_target <= 0) {
            return 0;
        }

        return min(100, round(($this->net_income / $this->adjusted_target) * 100, 2));
    }

    /**
     * Get status color for display
     */
    public function getStatusColorAttribute()
    {
        if ($this->net_income >= $this->adjusted_target) {
            return 'green'; // Success
        } elseif ($this->net_income >= ($this->adjusted_target * 0.7)) {
            return 'yellow'; // Warning
        } else {
            return 'red'; // Danger
        }
    }
}
