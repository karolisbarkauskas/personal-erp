<?php

namespace App\Observers;

use App\IncomeReport;
use App\Task;
use App\WeekTask;

class ProfitObserver
{
    public function updated(IncomeReport $report)
    {
        $this->calculateProfit($report);
    }

    /**
     * @param IncomeReport $report
     * @return void
     */
    public function calculateProfit(IncomeReport $report): void
    {
        $task = Task::where('code', $report->task_id)->first();
        if ($task) {
            $earned = $report->total;
            $primeCost = $task
                ->weeklyTasks
                ->groupBy('employee_id')
                ->map(function ($employees) use ($report, $earned) {
                    return $employees->map(function (WeekTask $weekTask) {
                        return $weekTask->employee_hours * $weekTask->employee->hourly_rate_without_markup;
                    });
                })
                ->flatten(3)
                ->sum();

            $report->updateQuietly([
                'profit' => $earned - $primeCost,
            ]);
        }
    }

    public function created(IncomeReport $report)
    {
        $this->calculateProfit($report);
    }
}
