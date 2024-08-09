<?php

namespace App\Http\Controllers;

use App\Services\Statistics\TimeReport;

class MainReportController extends Controller
{
    public function index()
    {
        $report = new TimeReport(request()->has('minimal'));
        $report
            ->setYearFrom(request('yearFrom', now()->year))
            ->setWeekFrom(request('weekFrom', 10))
            ->setYearTo(request('yearTo', now()->year))
            ->setWeekTo(request('weekTo', now()->weekOfYear))
            ->report()
            ->normalizeValues();

        return view(
            'report.main',
            compact(
                'report'
            )
        );
    }

    public function demo()
    {
        return view(
            'report.report'
        );
    }

}
