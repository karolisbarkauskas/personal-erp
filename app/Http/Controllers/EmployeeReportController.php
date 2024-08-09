<?php

namespace App\Http\Controllers;


use App\CollabUsers;
use App\Time;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeReportController extends Controller
{
    public function show(CollabUsers $collabUsers)
    {
        $result = [];

        $times = Time::where('time_records.is_trashed', 0)
            ->select(
                'time_records.created_on',
                'time_records.value',
                'time_records.billable_status'
            )
            ->join('tasks', function ($join){
                $join->on('time_records.parent_id','=', 'tasks.id');
                $join->on('time_records.parent_type','=',  DB::raw("'Task'"));
            })
            ->join('projects', function ($join){
                $join->on('tasks.project_id','=', 'projects.id');
                $join->on('projects.ignore','=',   DB::raw("'0'"));
            })
            ->where('time_records.user_id', $collabUsers->id)
            ->where('time_records.created_on', '>', '2020-01-01 00:00:00')
            ->orderBy('time_records.created_on')
            ->get()
            ->groupBy(function ($val) {
                return Carbon::parse($val->created_on)->format('Y-m');
            });

        $result['data'] = [];
        /** TODO  INCLUDE Holidays and vacations */
        $holidays = [
            Carbon::create(2021, 1, 1),
            Carbon::create(2014, 4, 17),
            Carbon::create(2014, 5, 19),
            Carbon::create(2014, 7, 3),
        ];
        $start = Carbon::now()->startOfMonth();
        $days = $start->diffInDaysFiltered(function (Carbon $date) use ($holidays) {
            return $date->isWeekday() && !in_array($date, $holidays);
        }, Carbon::now());

        foreach ($times as $month => $time) {
            $quota = NULL;
            $hours = round($time->sum('value'), 2);
            $bill = round($time->where('billable_status', 1)->sum('value'), 2);
            $notBill = round($time->where('billable_status', 0)->sum('value'), 2);
            if ( $month == Carbon::now()->startOfMonth()->format('Y-m')) {
                $quota = $collabUsers->expected_work_time * $days;
                $result['series'] = [
                    [
                        'axis'=> "hours",
                        'valueField'=> "current",
                        'name'=> "This month",
                        'color'=> $hours >= $quota ? "#10b759" : "#dc3545",
                        'type' =>"bar"
                    ],[
                        'axis'=> "hours",
                        'valueField'=> "hours",
                        'name'=> "Work Hours",
                        'color'=> "#0158d4"
                    ],[
                        'axis'=> "hours",
                        'valueField'=> "bill",
                        'name'=> "Billable",
                        'color'=> "#085f2e"
                    ],[
                        'axis'=> "hours",
                        'valueField'=> "notBill",
                        'name'=> "Non billable",
                        'color'=> "#721c24"
                    ]
                ];

            }
            $result['data'][] =
                [
                    'year' => $month,
                    'current' => $quota,
                    'hours' => $hours,
                    'bill' => $bill,
                    'notBill' => $notBill,
                ];
        }

        if (!isset($result['series']) || empty($result['series'])){
            $result['series'] =[
                [
                    'axis'=> "hours",
                    'valueField'=> "hours",
                    'name'=> "Total",
                    'color'=> "#0158d4"
                ],[
                    'axis'=> "hours",
                    'valueField'=> "bill",
                    'name'=> "Billable",
                    'color'=> "#10b759"
                ],[
                    'axis'=> "hours",
                    'valueField'=> "notBill",
                    'name'=> "Non billable",
                    'color'=> "#dc3545"
                ]
            ];
        }
        return response()->json($result);
    }

}
