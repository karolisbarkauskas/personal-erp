<?php

namespace App\Import;

use App\Employee;
use App\Task;
use App\TaskList;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class Youtrack implements ToCollection, WithCustomCsvSettings, WithHeadingRow
{
    private TaskList $week;

    public function __construct(TaskList $list)
    {
        $this->week = $list;
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ","
        ];
    }

    public function collection(Collection $collection)
    {
        $collection->each(function (Collection $row) {
            if ($row->get('item')) {
                $employee = Employee::where(
                    'full_name', trim($row->get('group_name'))
                )->first()->id;
                $task = Task::firstOrCreate([
                    'code' => $row->get('item'),
                ]);

                $this->week->tasks()->updateOrCreate([
                    'employee_id' => $employee,
                    'task_id' => $task->id
                ], [
                    'employee_hours' => round($row->get('spent_time'), 2),
                    'is_open' => false,
                    'automatic' => true,
                    'name' => $row->get('item') . ' ' . $row->get('item_summary'),
                    'link' => "https://invoyer.youtrack.cloud/issue/" . $row->get('item'),
                ]);
            }
        });
    }
}
