<?php

namespace App\Reports;

use App\CollabUsers;
use App\Expense;
use App\Income;
use App\RecurringExpense;
use App\Status;
use App\Time;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Main
{
    public function getAllRecurringExpenses()
    {
        return CollabUsers::where('is_trashed', false)->get()->sum('cost') + RecurringExpense::all()->sum('size');
    }

    public function getCurrentActualExpenses()
    {
        return Expense::whereBetween('issue_date', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->sum('size');
    }

    public function getCurrentExpectedIncome()
    {
        return Income::whereIn('status', [
            Status::PLANNED,
            Status::SENT
        ])->sum('amount');
    }

    public function getCurrentActualIncome()
    {
        return Income::with('currentMonthPayments')
            ->get()
            ->map(function (Income $income) {
                if ($income->vat_size > 0) {
                    return round($income->currentMonthPayments()->sum('amount') / (1 + ($income->vat_size / 100)));
                }
                return $income->currentMonthPayments()->sum('amount');
            })
            ->sum();
    }

    public function expensesByCategory($year = false)
    {
        return Expense::whereBetween('issue_date', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->get()->groupBy(function ($val) {
            return $val->expenseCategory->name;
        });
    }

    public function incomeByCategory()
    {
        return Income::whereBetween('income_date', [
            Carbon::now()->subDays(30)->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->get()
            ->groupBy(function (Income $income) {
                return $income->incomeCategory->name;
            })->map(function (Collection $collection) {
                return $collection->map(function (Income $income) {
                    if ($income->vat_size > 0) {
                        return round($income->paymentTotals() / (1 + ($income->vat_size / 100)));
                    }
                    return $income->paymentTotals();
                })->sum();
            });
    }

    public function getTimeBooked($from, $to, $project = false)
    {
        /** @var \App\TimeCollection $times */
        $times = Time::whereBetween('record_date', [$from, $to])
            ->with('task.project')
            ->get()
            ->groupBy(function (Time $record) {
                return $record->task->project->name;
            });

//        $times = $times->each(function ($record) {
//            return $record->filter(function (Time $time) {
//                if ($time->task->project->id == 15) {
//                    return $time->user_id != 1;
//                }
//                return true;
//            });
////            return $record->first()->task->project->id != 76;
//        });


        if ($project) {
            $times = $times->filter(function ($record) use ($project) {
                return $record->first()->task->project->id == $project;
            });
        }

        return $times;
    }
}
