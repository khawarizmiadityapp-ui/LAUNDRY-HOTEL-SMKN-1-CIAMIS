<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DailyTarget extends Model
{
    protected $fillable = [
        'date',
        'is_workday',
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
        'is_workday' => 'boolean',
        'is_achieved' => 'boolean',
    ];

    /**
     * Get or create daily target for a specific date
     */
    public static function getOrCreateForDate(Carbon $date)
    {
        $dateString = $date->toDateString();
        $isWorkday = self::isWorkDay($date);
        $baseTarget = $isWorkday ? self::calculateBaseTarget($date) : 0;
        
        $target = self::firstOrCreate(
            ['date' => $dateString],
            [
                'is_workday' => $isWorkday,
                'base_target' => $baseTarget,
                'carry_forward' => 0,
                'adjusted_target' => $baseTarget,
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
     * Get workdays mode from settings (default: 'senin_jumat')
     */
    public static function getWorkdaysMode(): string
    {
        return Setting::getValue('target_workdays_mode', 'senin_jumat');
    }

    /**
     * Get custom holiday dates from settings
     */
    public static function getHolidayDates(): array
    {
        $raw = Setting::getValue('target_holiday_dates', '');
        if (empty($raw)) {
            return [];
        }

        if (is_array($raw)) {
            return $raw;
        }

        $decoded = json_decode($raw, true);
        if (is_array($decoded)) {
            return $decoded;
        }

        return array_map('trim', explode(',', $raw));
    }

    /**
     * Check if a specific date is a working day based on mode & holidays
     */
    public static function isWorkDay(Carbon $date): bool
    {
        $dateStr = $date->toDateString();
        $holidays = self::getHolidayDates();

        // If date is in custom holidays list -> not a working day
        if (in_array($dateStr, $holidays, true)) {
            return false;
        }

        $mode = self::getWorkdaysMode();

        return match ($mode) {
            'senin_jumat' => $date->isWeekday(), // Monday through Friday only
            'senin_sabtu' => !$date->isSunday(), // Monday through Saturday
            'setiap_hari' => true,                // All days
            'custom'      => $date->isWeekday(), // Default to weekday if custom count mode
            default       => $date->isWeekday(),
        };
    }

    /**
     * Get active work days count in a given month
     */
    public static function getTargetDaysInMonth($date = null): int
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $year = $date->year;
        $month = $date->month;

        $mode = self::getWorkdaysMode();

        // If mode is 'custom' and specific number of days is configured
        if ($mode === 'custom') {
            $customDays = (int) Setting::getValue('target_custom_days', 22);
            if ($customDays > 0) {
                return $customDays;
            }
        }

        // Count dynamically by inspecting each day of the month
        $calendarDays = $date->daysInMonth;
        $workdaysCount = 0;

        for ($d = 1; $d <= $calendarDays; $d++) {
            $current = Carbon::create($year, $month, $d);
            if (self::isWorkDay($current)) {
                $workdaysCount++;
            }
        }

        // Subtract additional holiday count if set without specific dates
        $holidayCount = (int) Setting::getValue('target_holidays_count', 0);
        $finalDays = max(1, $workdaysCount - $holidayCount);

        return $finalDays;
    }

    /**
     * Calculate base daily target from monthly target divided by active work days
     */
    public static function calculateBaseTarget($date = null): int
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $monthlyTarget = self::getMonthlyTarget();
        $activeWorkDays = self::getTargetDaysInMonth($date);
        
        return $activeWorkDays > 0 ? (int) ceil($monthlyTarget / $activeWorkDays) : 0;
    }

    /**
     * Get configured monthly target (supports custom target for specific month/year)
     */
    public static function getMonthlyTarget($date = null): int
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $year = $date->year;
        $month = str_pad((string) $date->month, 2, '0', STR_PAD_LEFT);

        // 1. Check custom target for this specific month (e.g. target_monthly_2026_09)
        $monthKey = "target_monthly_{$year}_{$month}";
        $monthSpecificTarget = Setting::getValue($monthKey);
        if ($monthSpecificTarget && is_numeric($monthSpecificTarget) && (int) $monthSpecificTarget > 0) {
            return (int) $monthSpecificTarget;
        }

        // 2. Check global database default target_monthly
        $dbTarget = Setting::getValue('target_monthly');
        if ($dbTarget && is_numeric($dbTarget) && (int) $dbTarget > 0) {
            return (int) $dbTarget;
        }

        // 3. Check environment variables
        if (env('MONTHLY_INCOME_LIMIT')) {
            return (int) env('MONTHLY_INCOME_LIMIT');
        }
        if (env('ANNUAL_INCOME_LIMIT')) {
            return (int) ceil(((int) env('ANNUAL_INCOME_LIMIT')) / 12);
        }

        return 50000000;
    }

    /**
     * Check if a specific month has a custom monthly target configured
     */
    public static function isMonthCustomTarget($date = null): bool
    {
        $date = $date ? Carbon::parse($date) : Carbon::now();
        $year = $date->year;
        $month = str_pad((string) $date->month, 2, '0', STR_PAD_LEFT);

        $monthKey = "target_monthly_{$year}_{$month}";
        $monthSpecificTarget = Setting::getValue($monthKey);
        return !empty($monthSpecificTarget) && is_numeric($monthSpecificTarget) && (int) $monthSpecificTarget > 0;
    }

    /**
     * Get configured annual target
     */
    public static function getAnnualTarget(): int
    {
        $dbAnnual = Setting::getValue('target_annual');
        if ($dbAnnual && is_numeric($dbAnnual) && (int) $dbAnnual > 0) {
            return (int) $dbAnnual;
        }

        if (env('ANNUAL_INCOME_LIMIT')) {
            return (int) env('ANNUAL_INCOME_LIMIT');
        }

        return self::getMonthlyTarget() * 12;
    }

    /**
     * Recalculate daily targets for a given month sequentially
     * to apply workdays filter and carry-forward deficits accurately.
     */
    public static function recalculateMonthTargets($year = null, $month = null)
    {
        $year = $year ?? Carbon::now()->year;
        $month = $month ?? Carbon::now()->month;

        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $calendarDaysInMonth = $startDate->daysInMonth;
        $targetDaysInMonth = self::getTargetDaysInMonth($startDate);
        
        $monthlyTarget = self::getMonthlyTarget();
        $baseDailyTarget = $targetDaysInMonth > 0 ? (int) ceil($monthlyTarget / $targetDaysInMonth) : 0;

        $runningDeficit = 0;
        $results = collect();

        for ($day = 1; $day <= $calendarDaysInMonth; $day++) {
            $currentDate = Carbon::createFromDate($year, $month, $day)->startOfDay();
            $dateString = $currentDate->toDateString();
            $isWorkday = self::isWorkDay($currentDate);

            // Fetch actual income and expenses for current date
            $income = Transaksi::where('payment_status', 'lunas')
                ->whereDate('created_at', $currentDate)
                ->sum('total_price');

            $expense = Pengeluaran::whereDate('tanggal', $currentDate)
                ->sum('nominal');

            $netIncome = $income - $expense;

            if ($isWorkday) {
                // Working day: target applies
                $dayBaseTarget = $baseDailyTarget;
                $carryForward = $runningDeficit;
                $adjustedTarget = $dayBaseTarget + $carryForward;
                $variance = $netIncome - $adjustedTarget;
                $isAchieved = $netIncome >= $adjustedTarget;

                // Carry forward deficit to next day if net income is below adjusted target
                if ($variance < 0) {
                    $runningDeficit = abs($variance);
                } else {
                    $runningDeficit = 0; // Reset deficit when target achieved
                }
            } else {
                // Non-working day (Weekend / Hari Libur): no mandatory target
                $dayBaseTarget = 0;
                $carryForward = 0;
                $adjustedTarget = 0;
                $variance = $netIncome; // Any income is bonus
                $isAchieved = $netIncome > 0;

                // If bonus income occurs on a weekend/holiday, reduce existing deficit for Monday!
                if ($netIncome > 0 && $runningDeficit > 0) {
                    $runningDeficit = max(0, $runningDeficit - $netIncome);
                }
            }

            $targetRecord = self::updateOrCreate(
                ['date' => $dateString],
                [
                    'is_workday' => $isWorkday,
                    'base_target' => $dayBaseTarget,
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
     * Get current achievement percentage
     */
    public function getAchievementPercentageAttribute()
    {
        if ($this->adjusted_target <= 0) {
            return $this->net_income > 0 ? 100 : 0;
        }

        return min(100, round(($this->net_income / $this->adjusted_target) * 100, 2));
    }

    /**
     * Get status color for display
     */
    public function getStatusColorAttribute()
    {
        if (!$this->is_workday) {
            return 'slate'; // Hari Libur
        }

        if ($this->net_income >= $this->adjusted_target) {
            return 'green'; // Success
        } elseif ($this->net_income >= ($this->adjusted_target * 0.7)) {
            return 'yellow'; // Warning
        } else {
            return 'red'; // Danger
        }
    }
}
