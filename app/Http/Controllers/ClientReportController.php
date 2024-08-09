<?php

namespace App\Http\Controllers;

use App\Client;
use App\CollabUsers;
use App\Expense;
use App\Income;
use App\Task;
use App\Time;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientReportController extends Controller
{
    public function show(Client $client, $type)
    {
        switch ($type) {
            case 'income':
                return $this->showIncomeGraph($client);
            case 'task':
                return $this->showTaskGraph($client);
        }
    }


    private function showIncomeGraph(Client $client)
    {
        $result = [];

        $times = Time::where('time_records.is_trashed', 0)
            ->select(
                'time_records.created_on',
                'time_records.value',
                'time_records.billable_status'
            )
            ->join('tasks', 'time_records.parent_id', 'tasks.id')
            ->join('projects', 'tasks.project_id', 'projects.id')
            ->where('time_records.parent_type', 'Task')
            ->where('time_records.created_on', '>', '2020-01-01 00:00:00')
            ->where('projects.company_id', $client->id)
            ->orderBy('time_records.created_on')
            ->get()
            ->groupBy(function ($val) {
                return Carbon::parse($val->created_on)->format('Y-m');
            });

        $result['data'] = [];
        foreach ($times as $month => $time) {
            $monthDate = Carbon::parse($month);
            $adjHours = $client->getAverageTimeForPeriod($monthDate->endOfMonth()->format('Y-m-d'), $monthDate->firstOfMonth()->format('Y-m-d'));
            $income = Income::whereBetween('income_date', [
                    $monthDate->firstOfMonth()->format('Y-m-d'),
                    $monthDate->endOfMonth()->format('Y-m-d')]
            )
                ->where('client', $client->id)
                ->select(DB::raw('sum(`amount`) as amount'))
                ->first();
            $result['data'][] =
                [
                    'year' => $month,
                    'hours' => round($time->sum('value'), 2),
                    'hours_adj' => round($adjHours, 2),
                    'income' => round($income->amount, 2),
                ];
        }
        return response()->json($result);
    }

    private function showTaskGraph(Client $client)
    {
        $result = [];

        $taskGroupsByMonth = Task::where('tasks.is_trashed', 0)
            ->select(
                'tasks.created_by_id',
                'tasks.created_on',
                'tasks.created_by_name',
                'tasks.completed_by_id',
                'tasks.completed_on',
                'tasks.completed_by_name',
                DB::raw('TIMESTAMPDIFF(DAY, tasks.created_on, tasks.completed_on) as "duration"')
            )
            ->join('projects', 'tasks.project_id', 'projects.id')
            ->where('projects.company_id', $client->id)
            ->where('tasks.created_on', '>', '2020-01-01 00:00:00')
            ->where('tasks.is_trashed', false)
            ->orderBy('tasks.created_on')
            ->get()
            ->groupBy(function ($val) {
                return Carbon::parse($val->created_on)->format('Y-m');
            });

        $result['data'] = [];
//        $result['series'] = [];
//
//        foreach (CollabUsers::all() as $collabUser) {
//            $result['series'][] = [
//                'valueField' => 'cl_'.Str::slug($collabUser->first_name . '_' . $collabUser->last_name, '_'),
//                'name' => $collabUser->first_name . '_' . $collabUser->last_name,
//                'stack' => "closed"
//            ];
//            $result['series'][] = [
//                'valueField' => 'op_'.Str::slug($collabUser->first_name . '_' . $collabUser->last_name, '_'),
//                'name' => $collabUser->first_name . '_' . $collabUser->last_name,
//                'stack' => "open"
//            ];
//        }

        foreach ($taskGroupsByMonth as $month => $taskGroup) {
            $durations = [];
            $created = [];
            $closed = [];
            $tOpen = 0;
            $tClosed = 0;
            foreach ($taskGroup as $task) {
                if (!isset($created[$task->created_by_id])) {
                    $created[$task->created_by_id] = 1;
                } else {
                    $created[$task->created_by_id]++;
                }

                if ($task->created_on) {
                    $tOpen++;
                }
                if ($task->completed_on) {
                    $tClosed++;
                    $durations[] = $task->duration;
                } else {
                    $jan = Carbon::createFromTimeString($task->created_on);
                    $diff = $jan->diffInDays(Carbon::now());
                    $durations[] = $diff;
                }
                if (!isset($closed[$task->completed_by_id])) {
                    $closed[$task->completed_by_id] = 1;
                } else {
                    $closed[$task->completed_by_id]++;
                }

            }
            $avg = $stdNeg = $stdPoss = 0;
            if (count($durations)) {
                $avg = array_sum($durations) / count($durations);
            }

            $deviations = [
                'poss' => [],
                'neg' => [],
            ];
            foreach ($durations as $duration){
                $diff = $duration - $avg;
                if ($diff >= 0){
                    $deviations['poss'][] =abs($diff);
                } else {
                    $deviations['neg'][] = abs($diff);
                }
            }

            if (count($deviations['poss'])) {
                $stdPoss = array_sum($deviations['poss'])/count($deviations['poss']);
            }
            if (count($deviations['neg'])) {
                $stdNeg = array_sum($deviations['neg'])/count($deviations['neg']);
            }

            $result['data'][] =
                [
                    'month' => $month,
                    'created' => $tOpen,
                    'closed' => $tClosed,
                    'active' => $tOpen - $tClosed,
                    'avg' => round($avg, 2),
                    'avgMin' => round($avg - $stdNeg,2),
                    'avgMax' => round($stdPoss + $avg,2),
                ];
        }
        return response()->json($result);
    }
}
