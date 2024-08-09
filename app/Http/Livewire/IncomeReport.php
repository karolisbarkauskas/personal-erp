<?php

namespace App\Http\Livewire;

use App\Income;
use App\Mail\InformAboutNewIncome;
use App\Task;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class IncomeReport extends Component
{
    /** @var Income */
    public $income;
    public $total;
    public $hourly_rate;
    public $hours;
    public $total_row;
    public $name;
    public $done;
    public $taskId;
    public $include;
    public $taskLink;

    protected $listeners = ['refreshComponent' => '$refresh'];

    /**
     * @param Income $income
     * @return void
     */
    public function mount(Income $income)
    {
        $this->income = $income;
        $this->total = $income->reports()->where('include', true)->sum('total');

        $this->hourly_rate = $income->incomeClient->rate;
    }

    public function render()
    {
        $this->total = $this->income->fresh()->reports()->where('include', true)->sum('total');
        return view('livewire.income-report');
    }

    public function add()
    {
        $this->validate([
            'name' => 'required',
            'hours' => 'required',
            'hourly_rate' => 'required',
            'total_row' => 'required',
        ]);

        $this->income->reports()->create([
            'name' => $this->name,
            'task_id' => $this->taskId,
            'task_link' => $this->taskLink,
            'include' => $this->include,
            'hourly_rate' => $this->hourly_rate,
            'hours' => $this->hours,
            'total' => $this->total_row
        ]);

        $this->total = $this->income->fresh()->reports()->where('include', true)->sum('total');

        $this->updateIncomeTotal();

        Mail::send(new InformAboutNewIncome($this->income->fresh()));

        $this->reset('name', 'hours', 'taskId', 'taskLink');
        $this->emitSelf('refreshComponent');
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
                $this->include = true;
            }
        }

        $this->updateTotalRow();
    }

    public function updatedTaskLink()
    {
        $this->updateTotalRow();
    }

    /**
     * @return void
     */
    public function updateIncomeTotal(): void
    {
        $this->income->updateTotalProgrammingServices();
    }

    public function updatedHours()
    {
        $this->validate([
            'hours' => 'numeric|required',
            'hourly_rate' => 'numeric|required',
        ]);

        $this->updateTotalRow();
    }

    /**
     * @return void
     */
    public function updateTotalRow(): void
    {
        $this->total_row = round($this->hours * $this->hourly_rate, 2);
        $this->updateIncomeTotal();
    }

    public function updatedHourlyRate()
    {
        $this->validate([
            'hours' => 'numeric|required',
            'hourly_rate' => 'numeric|required',
        ]);

        $this->updateTotalRow();
    }
}
