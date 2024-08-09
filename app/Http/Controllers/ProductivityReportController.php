<?php

namespace App\Http\Controllers;

use App\Reports\Productivity;
use App\Time;
use App\TimeCollection;

class ProductivityReportController extends Controller
{
    public function index()
    {
        $report = new Productivity();
        $timeToday = $report->getTimeBookedToday();
        $timeThisMonth = $report->getTimeBookedThisMonth();
        /** @var \Illuminate\Database\Eloquent\Collection $timeThisWeek */
        $timeThisWeek = $report->getTimeBookedThisWeek();
        $offDaysInWeek = $report->getDaysOffWithinWeek();
        $offDaysInMonth = $report->getDaysOffWithinMonth();

        $timeThisWeekDaily = $timeThisWeek->map(function (TimeCollection $val) {
            return $val->groupBy(function (Time $item) {
                return $item->record_date;
            });
        });

        return view(
            'report.productivity',
            compact(
                'timeToday',
                'timeThisMonth',
                'timeThisWeek',
                'timeThisWeekDaily',
                'offDaysInMonth',
                'offDaysInWeek'
            )
        );
    }
}
