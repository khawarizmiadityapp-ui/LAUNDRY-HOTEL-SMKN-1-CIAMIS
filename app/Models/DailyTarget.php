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
     * Calculate base daily target from monthly target
     */
    public static function calculateBaseTarget()
    {
        $monthlyTarget = (int) env('MONTHLY_INCOME_LIMIT', 50000000);
        $daysInMonth = Carbon::now()->daysInMonth;
        
        return (int) ceil($monthlyTarget / $daysInMonth);
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
