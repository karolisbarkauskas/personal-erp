<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Collection;

class TaskList extends Model
{
    protected $fillable = [
        'year',
        'week',
        'employee_hours_available', // Hours per week of all AVAILABLE resources
        'employee_hours_used',      // Hours already PLANNED BY PM in the week by employees
        'employee_hours_booked',    // Hours already BOOKED in the week by employees
        'client_sold_hours',        // Hours SOLD TO CLIENT by their hourly rate
        'client_sellable_hours',    // Hours THAT MUST BE SOLD in current week
        'optimal_sellable_hours',   // Hours THAT should be sold in current week (maximum profitability)
        'break_even_hours',   // Break even hours without ANY profit at the moment
        'is_locked', // No changed are allowed to be set
        'is_finished', // Week is finished. No connection to anything, just to mark.
    ];

    public function isCurrentWeek(): bool
    {
        return $this->year == now()->year && $this->week == now()->weekOfYear;
    }

    public function getStartWeek()
    {
        return Carbon::create($this->year)->startOfWeek()->addDays(($this->week - 1) * 7)->format('m-d');
    }

    public function getEndWeek()
    {
        return Carbon::createFromFormat('m-d', $this->getStartWeek())->addDays(4)->format('m-d');
    }

    public function scopeGetCurrentWeek($builder)
    {
        return $builder->where([
            'year' => now()->year,
            'week' => now()->weekOfYear
        ]);
    }

    public function getBookedClass(): string
    {
        if ($this->client_sold_hours == 0) {
            return 'tx-black';
        }

        if ($this->client_sold_hours > $this->client_sellable_hours) {
            return 'text-success';
        }

        if ($this->client_sold_hours > $this->break_even_hours) {
            return 'tx-info';
        }

        return 'tx-danger';
    }

    public function scopeCurrentYear($builder)
    {
        return $builder->where('year', now()->year);
    }

    public function sellGoalMeet(): bool
    {
        return $this->client_sellable_hours <= $this->client_sold_hours;
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(WeekTask::class);
    }

    public function getTasks(Employee $employee): Collection
    {
        return $this->tasks()->where('employee_id', $employee->id)->get();
    }

    public function times(): belongsToMany
    {
        return $this
            ->belongsToMany(Employee::class, 'task_lists_employee')
            ->withPivot('hours');
    }

    public function getWeeklyWorkHours(Employee $employee)
    {
        return $this->getHoursByEmployee($employee) ?? $employee->sellable_hours_per_day * 5;
    }

    public function getSoldTimePercentage(Employee $employee)
    {
        if($this->getWeeklyWorkHours($employee) == 0){
            return 0;
        }

        return round(
            ($this->getTasks($employee)->sum('sold_to_client') * 100) / $this->getWeeklyWorkHours($employee)
        );
    }

    public function timeByEmployee(): HasOneThrough
    {
        return $this->HasOneThrough(
            Employee::class,
            TaskList::class,
            'id',
            'id',
            'id',
            'id'
        );
    }

    public function updateTotals()
    {
        $sellableHours = $this->tasks()->get()->map(function (WeekTask $task) {
            return $task->sold_to_client * (($task->client->rate ?? 0) / Settings::getHourlyRate());
        })->sum();

        if (!$this->is_locked) {
            $sellableHoursTotal = ceil(Settings::getSalesHours(Settings::getMarkup()) / Settings::WEEKS_PER_MONTH_AVG);
            $optimalSellableHours = ceil(
                Settings::getSalesHours(ceil(Employee::active()->get()->average('markup'))) / Settings::WEEKS_PER_MONTH_AVG
            );
            $this->update([
                'break_even_hours' => ceil(Settings::getSalesHours() / \App\Settings::WEEKS_PER_MONTH_AVG),
                'client_sellable_hours' => ($this->client_sellable_hours == 0 ? $sellableHoursTotal : $this->client_sellable_hours),
                'optimal_sellable_hours' => ($this->optimal_sellable_hours == 0 ? $optimalSellableHours : $this->optimal_sellable_hours),
            ]);
        }

        $this->update([
            'employee_hours_used' => $this->tasks()->get()->sum('employee_hours'),
            'client_sold_hours' => $sellableHours,
            'employee_hours_available' => $this->times->sum('pivot.hours'),
        ]);
    }

    /**
     * @param Employee $employee
     * @return mixed
     */
    public function getHoursByEmployee(Employee $employee)
    {
        return $this->belongsToMany(
            Employee::class,
            'task_lists_employee'
        )
            ->wherePivot('employee_id', $employee->id)
            ->withPivot('hours')
            ->first()
            ->pivot
            ->hours ?? false;
    }

    public function getTitle()
    {
        if ($this->week == now()->weekOfYear) {
            return "Current week";
        }

        return "W{$this->week}";
    }

}
