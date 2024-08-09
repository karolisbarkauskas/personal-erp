<?php

namespace App\Reports;

use App\OffDays;
use App\Time;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Productivity
{
    public function getTimeBookedToday()
    {
        return Time::where('record_date', Carbon::today())
            ->where('is_trashed', false)
            ->get()
            ->groupBy('created_by_name');
    }

    public function getTimeBookedYesterday()
    {
        return Time::where('record_date', Carbon::yesterday())
            ->with('task')
            ->where('is_trashed', false)
            ->orderBy('created_by_name')
            ->get()
            ->whereNotIn('task.project_id', [15, 93, 82, 57])
            ->groupBy('created_by_name');
    }

    public function getTimeBookedThisMonth()
    {
        return Time::whereBetween('record_date', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])
            ->where('is_trashed', false)
            ->get()
            ->groupBy('created_by_name');
    }

    public function getTimeBookedThisWeek()
    {
        return Time::whereBetween('record_date', [
            Carbon::now()->startofWeek(),
            Carbon::now()->endOfWeek()
        ])
            ->whereHas('task', function (Builder $builder) {
                return $builder->whereNotIn('project_id', [15, 70]);
            })
            ->where('is_trashed', false)
            ->get()
            ->groupBy('created_by_name');
    }

    public function getDaysOffWithinWeek()
    {
        return OffDays::whereBetween('day',
            [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ]
        )->count();
    }

    public function getDaysOffWithinMonth()
    {
        return OffDays::whereBetween('day',
            [
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            ]
        )->count();
    }
}
