<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Expense;
use App\Project;
use App\Settings;
use App\TaskList;
use App\WeekTask;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TasksController extends Controller
{
    public function index()
    {
        $currentPeriod = TaskList::currentYear()->whereBetween('week', [
            now()->weekOfYear - 1,
            now()->weekOfYear + 1
        ])->orderBy('week')->get();

        $thisYear = TaskList::currentYear()->orderBy('week')->get();
        $currentWeek = TaskList::currentYear()->where('week', now()->weekOfYear)->first();
        $nextWeek = TaskList::currentYear()->where('week', now()->weekOfYear + 1)->first();
        $averageMarkup = ceil(Employee::active()->get()->average('markup'));
        $salaries = Employee::active()->get()->sum('salary_with_vat');
        $expenses = Expense::getRunningExpenses();
        $markup = Settings::getMarkup();
        $hourlyRate = Settings::getHourlyRate();

        $minimalHoursToSellNoProfit = Settings::getSalesHours();
        $optimalHoursToSell = Settings::getSalesHours(Settings::getMarkup());
        $healthyHoursToSell = Settings::getSalesHours($averageMarkup);
        $projects = Project::confirmed()->get();

        return view('tasks.index', get_defined_vars());
    }

    public function open(Request $request)
    {
        $tasks = WeekTask::where('status', '!=', WeekTask::INVOICED)
            ->with('task')
            ->whereNotNull('task_id')
            ->orderBy('task_id')
            ->get()
            ->groupBy('task_id');

        if ($request->get('search')) {
            $tasks = WeekTask::whereHas('task', function ($query) use ($request) {
                $query->where('code', 'LIKE', "%" . $request->get('search') . "%");
            })
                ->whereNotNull('task_id')
                ->get()
                ->groupBy('task_id');
        }

        $totalWorth = $tasks->sum(function (Collection $list) {
            return $list->map(function ($item) {
                return $item->task->getPrice();
            })->sum();
        });

        return view('tasks.open', get_defined_vars());
    }
}
