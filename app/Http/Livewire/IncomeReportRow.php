<?php

namespace App\Http\Livewire;

use App\Task;
use Livewire\Component;

class IncomeReportRow extends Component
{
    public $total;
    public $hourly_rate;
    public $hours;
    public $total_row;
    public $done;
    public $name;
    public $taskId;
    public $include;
    public $taskLink;
    public $display;
    public $task;

    public $report;
    protected $listeners = ['refreshComponent' => '$refresh'];

    public function mount($reportLine)
    {
        $this->report = $reportLine;
        $this->total_row = $this->report->total;

        $this->name = $reportLine->name;
        $this->hourly_rate = $reportLine->hourly_rate;
        $this->hours = $reportLine->hours;
        $this->done = $reportLine->done;
        $this->include = $reportLine->include;
        $this->taskId = $reportLine->task_id;
        $this->taskLink = $reportLine->task_link;
        $this->display = auth()->user()->isRoot();

        $this->task = Task::where('code', $this->taskId)->first();
    }

    public function render()
    {
        return view('livewire.income-report-row');
    }

    public function delete()
    {
        $this->report->delete();
        $this->report->income->updateTotalProgrammingServices();

        $this->emitTo(IncomeReport::class, 'refreshComponent');
    }

    public function updatedHours()
    {
        $this->validate([
            'hours' => 'numeric|required',
            'hourly_rate' => 'numeric|required',
        ]);

        $this->updateTotalRow();
    }

    public function updatedTaskId()
    {
        preg_match('/^([^-\s]+-[^-\s]+)/', $this->taskId, $matches);
        $this->taskId = $matches[0] ?? null;

        $task = Task::where('code', $this->taskId)->first();
        if ($task) {
            if ($task->weeklyTasks->isNotEmpty()) {
                $taskName = ltrim(str_replace($this->taskId, '', $task->weeklyTasks->first()->name));
                $originalName = $this->name;
                if (strpos($this->name, $taskName) === false) {
                    $this->name = ltrim(str_replace($this->taskId, '', $task->weeklyTasks->first()->name)) . "\n" . $originalName;
                }
                $this->hours = $task->client_time_sold;
            }
        }

        $this->updateTotalRow();
        $this->updateRow();
    }

    public function updatedTaskLink()
    {
        $this->updateRow();
    }

    /**
     * @return void
     */
    public function updateTotalRow(): void
    {
        $this->validate([
            'hours' => 'numeric|required',
            'hourly_rate' => 'numeric|required',
        ]);

        $this->total_row = round($this->hours * $this->hourly_rate, 2);

        $this->updateRow();
        $this->report->income->updateTotalProgrammingServices();

        $this->emitTo(IncomeReport::class, 'refreshComponent');
    }

    public function updatedHourlyRate()
    {
        $this->validate([
            'hours' => 'numeric|required',
            'hourly_rate' => 'numeric|required',
        ]);

        $this->updateTotalRow();
    }

    public function updatedDone()
    {
        $this->report->update([
            'done' => $this->done
        ]);
    }
    public function updatedInclude()
    {
        $this->report->update([
            'include' => $this->include
        ]);

        $this->updateTotalRow();
    }

    public function updatedName()
    {
        $this->report->update([
            'name' => $this->name,
        ]);

        $this->updateTotalRow();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'hours' => 'required',
            'hourly_rate' => 'required',
            'total_row' => 'required',
        ]);

        $this->updateRow();
        $this->report->income->updateTotalProgrammingServices();

        $this->emitTo(IncomeReport::class, 'refreshComponent');
    }

    /**
     * @return void
     */
    public function updateRow(): void
    {
        $this->report->update([
            'name' => $this->name,
            'task_id' => $this->taskId,
            'task_link' => $this->taskLink,
            'include' => $this->include,
            'hourly_rate' => $this->hourly_rate,
            'hours' => $this->hours,
            'total' => $this->total_row
        ]);
    }
}
