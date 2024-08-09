<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * @property Client $client
 * @property Employee $employee
 * @property float $employee_hours
 * @property TaskList $task
 */
class WeekTask extends Model
{
    use SoftDeletes;

    const IN_PROGRESS = 0;
    const COMPLETED = 1;
    const INVOICED = 2;
    const TODO = 3;
    const RESOURCE = 4;
    const PROJECT_RESOURCE = 5;

    protected $fillable = [
        'employee_id',
        'client_id',
        'link',
        'name',
        'remarks',
        'sold_to_client',
        'employee_hours',
        'is_open',
        'status',
        'diff',
        'task_id',
        'resource_time',
        'resource_time_employee',
        'automatic',
    ];

    public function client()
    {
        return $this->hasOne(
            Client::class,
            'id',
            'client_id'
        );
    }

    public function employee()
    {
        return $this->hasOne(
            Employee::class,
            'id',
            'employee_id'
        );
    }

    public function list()
    {
        return $this->hasOne(
            TaskList::class,
            'id',
            'task_list_id'
        );
    }

    public function isInProgress(): bool
    {
        return $this->status == self::IN_PROGRESS;
    }

    public function isResource(): bool
    {
        return $this->status == self::RESOURCE;
    }

    public function isToDo(): bool
    {
        return $this->status == self::TODO;
    }

    public function isCompleted(): bool
    {
        return $this->status == self::COMPLETED;
    }

    public function isInvoiced(): bool
    {
        return $this->status == self::INVOICED;
    }

    public function isProject(): bool
    {
        return $this->status == self::PROJECT_RESOURCE;
    }

    public function getStyleAttributes()
    {
        switch ($this->status) {
            case self::IN_PROGRESS:
                return 'font-size: 1rem;background: rgba(255,0,0,0.07)';
            case self::COMPLETED:
                return 'font-size: 0.8rem;background: rgba(57,255,2,0.29);';
            case self::INVOICED:
                return 'font-size: 0.8rem;background: rgb(17 48 145);color: #ffffff';
            case self::TODO:
                return 'font-size: 1rem;background: rgb(0 48 255 / 25%);';
            case self::RESOURCE:
                return 'font-size: 1rem;background: rgb(255 190 0 / 89%);';
            case self::PROJECT_RESOURCE:
                return 'font-size: 0.8rem;background: rgb(255, 255, 255);color: #000000;';
        }
    }

    public function task(): HasOne
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }

    public function updateTaskParent()
    {
        preg_match('/^([^-\s]+-[^-\s]+)/', $this->name, $matches);
        if ($matches && $this->task) {
            /** @var Collection $weeklyTasks */
            $weeklyTasks = $this->task->weeklyTasks;

            $this->task->update([
                'client_time_sold' => $weeklyTasks->sum('sold_to_client'),
                'client_time_used' => $weeklyTasks->sum(function (WeekTask $weekTask) {
                    if (!$weekTask->client) {
                        return 0;
                    }
                    return $weekTask->employee->calculateClientUsedTime(
                        $weekTask->client->rate,
                        $weekTask->employee_hours
                    );
                }),
            ]);
        }
    }

    public function invoiceTask($delete = false)
    {
        if ($this->client) {
            /** @var Income $income */
            $income = $this->client
                ->incomes()
                ->whereNull('invoice_no')
                ->where([
                    'company_id' => auth()->user()->company->id,
                    'status' => Income::NONE,
                ])
                ->firstOrCreate([
                    'company_id' => auth()->user()->company->id,
                    'status' => Income::NONE,
                ]);
            $income->syncToReport($this, $delete);
        }
    }

    public function delete()
    {
        $this->invoiceTask(true);

        parent::delete();
    }

    public function getCurrentProfit(): float
    {
        if (!$this->task || !$this->client) {
            return 0;
        }

        $primeCost = $this->task
            ->weeklyTasks
            ->groupBy('employee_id')
            ->map(function ($employees) {
                return $employees->map(function (WeekTask $weekTask) {
                    return $weekTask->employee_hours * $weekTask->employee->hourly_rate_without_markup;
                });
            })
            ->flatten(3)
            ->sum();

        $toBeEarned = $this->task->client_time_sold * $this->client->rate;

        return round($toBeEarned - $primeCost, 2);
    }

    public function getProfitPercentage()
    {
        if (!$this->task || !$this->client) {
            return 0;
        }

        $toBeEarned = $this->task->client_time_sold * $this->client->rate;
        return round(
            ($this->getCurrentProfit() * 100) / ($toBeEarned == 0 ? 1 : $toBeEarned)
        );
    }

    public function getBadgeClass()
    {
        switch ($this->getProfitPercentage()) {
            case $this->getProfitPercentage() < 0:
                return 'danger';
            case $this->getProfitPercentage() < Settings::getMarkup():
                return 'warning';
            case $this->getProfitPercentage() >= Settings::getMarkup():
                return 'success';
        }

        return 'info';
    }
}
