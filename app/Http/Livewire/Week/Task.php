<?php

namespace App\Http\Livewire\Week;

use App\Client;
use App\Http\Livewire\AddToWeek;
use App\Http\Livewire\WeekComponent;
use App\Http\Livewire\WeekSums;
use App\Project;
use App\Settings;
use App\WeekTask;
use Livewire\Component;

class Task extends Component
{
    /** @var WeekTask */
    public $task;
    public $clients;

    protected $rules = [
        'task.name' => 'required',
        'task.remarks' => 'nullable',
        'task.sold_to_client' => 'nullable',
        'task.employee_hours' => 'nullable',
        'task.client_id' => 'nullable',
        'task.status' => 'nullable',
    ];

    protected $listeners = ['refreshComponent' => '$refresh'];
    /**
     * @var Project[]|\Illuminate\Database\Eloquent\Collection
     */
    public $projects;
    public $project;

    public function mount($task)
    {
        $this->task = $task;
        $this->clients = Client::orderBy('name')->get();
        $this->projects = Project::all();

        if ($this->task->task) {
            $this->project = optional(Project::whereHas('tasks', function ($query) {
                $query->where('id', $this->task->task->id);
            })->first())->id;
        }
    }

    public function setState($state)
    {
        $this->task->updateTaskParent();
        $this->task->update([
            'is_open' => (bool)$state
        ]);
    }

    public function update()
    {
        $oldTask = $this->task->fresh();
        $this->task->link = $this->createTaskReturnLink();

        $this->task->employee_hours = $this->timeToDecimal($this->task->employee_hours);

        if ($this->project && $this->task->client && $this->task->isProject()) {
            $prime = $this->task->employee->calculateClientUsedTimePrime(
                $this->task->client->rate,
                $this->task->employee_hours
            );
            $this->task->sold_to_client = $prime;
        }

        if ($this->task->client) {
            $employeeHoursDiff =
                $this->task->employee->calculateDelay(
                    $this->task->client->rate,
                    $this->task->sold_to_client
                ) - $this->task->employee_hours;

            $diff = ($employeeHoursDiff * $this->task->employee->hourly_rate_sellable) / Settings::getHourlyRate();
            $this->task->diff = $diff;
            $this->task->resource_time = $this->task->sold_to_client;
            $this->task->resource_time_employee = $this->task->employee_hours;
            $this->task->save();
            $this->task->client->recalculateHourlyDiff();
        }

        /** @var WeekTask $resource */
        $resource = $this->getResourceTask(
            $this->task->client_id,
            $this->task->employee_id
        );

        if ($resource) {
            list($soldToClient, $employeeHours) = $this->getTotals();

            $resource->update([
                'sold_to_client' => $resource->resource_time - $soldToClient,
                'employee_hours' => $resource->resource_time_employee - $employeeHours,
            ]);

            if ($resource->sold_to_client <= 0) {
                $resource->update([
                    'sold_to_client' => 0,
                    'employee_hours' => 0,
                ]);
            }
        }

        $this->task->save();
        $this->task->list->updateTotals();
        $this->setState(0);
        $oldTask->updateTaskParent();
        $this->task = $this->task->fresh();
        $this->task->invoiceTask();

        if ($this->project && $this->task->client && $this->task->isProject()) {
            /** @var Project $project */
            $project = Project::find($this->project);
            $project->tasks()->syncWithoutDetaching([
                $this->task->task->id
            ]);
        }

        $this->emitTo(AddToWeek::class, 'refresh');
        $this->emitTo(WeekSums::class, 'refreshComponent');
    }

    protected function timeToDecimal($time)
    {
        if (str_contains($time, ':') === false) {
            return $time;
        }

        $parts = explode(':', $time);

        $hours = intval($parts[0]);
        $minutes = intval($parts[1]);

        return $hours + ($minutes / 60);
    }

    public function createTaskReturnLink(): ?string
    {
        if (preg_match('/^([^-\s]+-[^-\s]+)/', $this->task->name, $matches)) {
            $task = \App\Task::firstOrCreate([
                'code' => $matches[0]
            ]);
            $this->task->task_id = $task->id;

            return "https://invoyer.youtrack.cloud/issue/" . $matches[0];
        } else {
            return null;
        }
    }

    public function deleteTask()
    {
        $clientId = $this->task->client_id;
        $employeeId = $this->task->employee_id;

        $this->task->delete();
        $this->task->list->updateTotals();
        optional($this->task->client)->recalculateHourlyDiff();

        /** @var WeekTask $resource */
        $resource = $this->getResourceTask($clientId, $employeeId);

        if ($resource) {
            $leftovers = $this->task->list->tasks()
                ->where(function ($query) use ($employeeId, $clientId) {
                    return $query->where([
                        'client_id' => $clientId,
                        'employee_id' => $employeeId
                    ]);
                })
                ->where('status', '!=', WeekTask::RESOURCE)
                ->get();

            $resource->update([
                'sold_to_client' => $resource->resource_time - $leftovers->sum('sold_to_client'),
                'employee_hours' => $resource->resource_time_employee - $leftovers->sum('employee_hours'),
            ]);

            if ($resource->sold_to_client <= 0) {
                $resource->update([
                    'sold_to_client' => 0,
                    'employee_hours' => 0,
                ]);
            }
        }

        $this->emitTo(WeekComponent::class, 'refresh');
        $this->emitTo(AddToWeek::class, 'refresh');
        $this->emitTo(WeekSums::class, 'refreshComponent');
    }

    public function calculate()
    {
        $this->task->save();

        if ($this->task->client) {
            $this->task->employee_hours = $this->task->employee->calculateDelay(
                $this->task->client->rate,
                $this->task->sold_to_client
            );
        }
    }

    public function render()
    {
        return view('livewire.week.task');
    }

    /**
     * @return mixed
     */
    public function getResourceTask($clientId, $employeeId)
    {
        return $this->task->list->tasks()
            ->where(function ($query) use ($employeeId, $clientId) {
                return $query->where([
                    'status' => WeekTask::RESOURCE,
                    'client_id' => $clientId,
                    'employee_id' => $employeeId
                ]);
            })
            ->where('id', '!=', $this->task->id)
            ->first();
    }

    /**
     * @return array
     */
    public function getTotals(): array
    {
        $totals = $this->task->list->tasks()
            ->where(function ($query) {
                return $query->where([
                    'client_id' => $this->task->client_id,
                    'employee_id' => $this->task->employee_id
                ]);
            })
            ->where('status', '!=', WeekTask::RESOURCE)
            ->get();

        $soldToClient = $totals->sum('sold_to_client');
        $employeeHours = $totals->sum('employee_hours');
        return array($soldToClient, $employeeHours);
    }
}
