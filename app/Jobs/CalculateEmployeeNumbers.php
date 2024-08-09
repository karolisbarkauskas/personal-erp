<?php

namespace App\Jobs;

use App\Employee;
use App\RecurringExpense;
use App\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateEmployeeNumbers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $workDays = Settings::WORK_DAYS_PER_MONTH;
        $expenses = RecurringExpense::where('installment', 0)->get()->sum('size') + Employee::active()->sum('salary_to_cover');
        $totalWorkHoursPerDay = Employee::active()->sum('sellable_hours_per_day');

        /** @var Employee $employee */
        foreach (Employee::active()->get() as $employee) {
            $expensesToCover = round(($employee->sellable_hours_per_day * 100) / $totalWorkHoursPerDay, 2);
            $expensesEurToCover = round(($expensesToCover * $expenses) / 100);
            $toEarnMin = $employee->salary_with_vat + $expensesEurToCover;
            $toEarnNormal = $toEarnMin * (1 + ($employee->markup / 100));

            $employee->update([
                'hourly_rate_without_markup' => round($toEarnMin / ($employee->sellable_hours_per_day * $workDays), 2),
                'hourly_rate_with_markup' => round($toEarnNormal / ($employee->sellable_hours_per_day * $workDays), 2),
            ]);
        }

    }
}
