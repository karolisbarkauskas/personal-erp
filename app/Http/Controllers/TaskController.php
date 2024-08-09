<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Import\Youtrack;
use App\TaskList;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class TaskController extends Controller
{
    public function edit(TaskList $list)
    {
        $employees = $this->getEmployees($list);
        $title = $list->getTitle();

        return view('tasks.edit', compact('list', 'employees', 'title'));
    }

    public function current()
    {
        $list = TaskList::getCurrentWeek()->first();
        if (!$list) {
            abort('404');
        }

        $employees = $this->getEmployees($list);
        $title = $list->getTitle();

        return view('tasks.edit', compact('list', 'employees', 'title'));
    }

    public function import(Request $request, TaskList $list)
    {
        Excel::import(new Youtrack($list), $request->file('youtrack'));

        return redirect()->back();
    }

    /**
     * @param TaskList $list
     * @return Employee[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getEmployees(TaskList $list)
    {
        $employees = Employee::all();

        foreach (Employee::all() as $employee) {
            if (!$list->getHoursByEmployee($employee)) {
                $list->times()->attach($employee->id, [
                    'hours' => $employee->sellable_hours_per_day * 5
                ]);
            }
        }
        return $employees;
    }
}
