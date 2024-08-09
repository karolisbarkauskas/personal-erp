<?php

namespace App;

use App\Traits\TimeConvert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CollabUsers extends Model
{
    use TimeConvert;

    const WORKDAYS_PER_MONTH = 21;
    const WORKDAYS_PER_WEEK = 5;
    const VACATION_RATE = 1.09;
    public $timestamps = false;
    protected $table = 'users';
    protected $connection = 'collab';

    protected $fillable = [
        'position',
        'profit',
        'rate',
        'cost',
        'non_profit',
        'expected_work_time',
        'team'
    ];

    public static function getRate($employee_id, $time)
    {
        if (get_class($time) == "Illuminate\Support\Carbon") {
            $time = $time->format('Y-m-d H:i:s');
        }
        $history = EmployeeRateHistory::where('collab_user', $employee_id)->where('from', '<=', $time)->orderBy('from', 'desc')->first();
        if ($history) {
            return $history->rate;
        }
    }

    public function scopeActive(Builder $builder)
    {
        return $builder->where('company_id', 1)->whereNull('trashed_on')->where('is_archived', false);
    }

    public function scopeONESOFT(Builder $builder)
    {
        return $this->active()->GetProgrammers()->where('team', Income::ONESOFT)->orderBy('expected_work_time', 'desc');
    }

    public function scopePrestaPRO(Builder $builder)
    {
        return $this->active()->GetProgrammers()->where('team', Income::PRESTAPRO)->orderBy('expected_work_time', 'desc');
    }

    public function scopeInter(Builder $builder)
    {
        return $this->active()->GetProgrammers()->whereNull('team')->orderBy('expected_work_time', 'desc');
    }

    public function scopeByName(Builder $builder)
    {
        return $builder->orderBy('first_name', 'asc');
    }

    public function scopeProfitable(Builder $builder)
    {
        if ((auth()->user()->isRoot() || auth()->user()->projectManager()) && request()->get('employee')) {
            $builder->where('id', request()->get('employee'));
        } elseif (!auth()->user()->isRoot() && !auth()->user()->projectManager() || auth()->user()->private_tasks_view) {
            $builder->where('id', auth()->user()->collab_user);
        }

        return $builder->getProgrammers();
    }

    public function scopeGetProgrammers(Builder $builder)
    {
        return $builder->where('non_profit', false);
    }

    public function getWallpaper()
    {
        $paper = $this->hasOne(ConfigOptions::class, 'parent_id', 'id')
            ->where('name', 'wallpaper')
            ->where('parent_type', 'User')
            ->first();

        if (!$paper) {
            return 'wallpaper.jpg';
        }

        return unserialize($paper->value);
    }

    public function rate($time)
    {
        return $this->hasOne(EmployeeRateHistory::class, 'collab_user', 'id')
            ->where('from', '<=', $time)
            ->orderBy('from', 'desc')
            ->first();
    }

    public function getCompanyHourlyRate()
    {
        $employees = $this->getProgrammers()->active()->get();
        $total = 0;
        /** @var \App\CollabUsers $employee */
        foreach ($employees as $employee) {
            $total += $employee->getHourlyRate();
        }

        return round($total / $employees->count(), 2);
    }

    public function getHourlyRate($time = false)
    {
        if (($time !== false) && $oldRate = $this->getOldRate($time)) {
            return $oldRate->rate;
        }

        return $this->rate;
    }

    public function getOldRate($time)
    {
        if ($time instanceof Carbon) {
            $time = $time->startOfDay()->format('Y-m-d H:i:s');
        }

        return EmployeeRateHistory::where('collab_user', $this->id)->where('from', '<=', $time)->orderBy('from', 'desc')->first();
    }

    public function getNoProfitRate()
    {
        if ($this->non_profit) {
            return 0;
        }
        $now = Carbon::now();
        return $this->getHourRateCost($now->month, $now->year);
    }

    /**
     * Returns what is the cost for work hour of specific month
     * hour_cost = ((sum_running_expenses * salary_part + salary * vacationRate) / work_days_in_month / employee_work_hours_per_day)
     *
     * @param int $month
     * @param int $year
     * @return float
     */
    public function getHourRateCost(int $month, int $year): float
    {
        $salaryPart = $this->getSalaryPart($month, $year);
        $expensesTotal = Expense::getRunningCostForMonth($month, $year);
        return (($expensesTotal * $salaryPart + $this->getSalary($year, $month) * self::VACATION_RATE) / Time::getWorkdays($year, $month) / $this->expected_work_time);
    }

    /**
     * Returns employees salary part in all selected employees salaries sum.
     *
     * @param int $month
     * @param $year
     * @return float
     */
    public function getSalaryPart(int $month, $year = false): float
    {
        $cost = self::getEmployeesCost($month, $year, false, false);

        return round($this->getSalary($year, $month) / ($cost ?: 1), 6);
    }

    /**
     * Getting selected employees salaries sum for end of given month.
     *
     * Selecting employees which worked at start of month.
     * And taking last salary from end of month retrospectively
     *
     * @param int $month
     * @param int $year
     * @param bool $nonProfit
     * @param bool $all
     * @return float
     */
    public static function getEmployeesCost(int $month, int $year, bool $all = true, bool $nonProfit = false): float
    {
        $cost = 0;
        /** @var CollabUsers $employee */
        foreach (CollabUsers::getEmployees($month, $year, $all, $nonProfit) as $employee) {
            $cost += $employee->getSalary($year, $month);
        }
        return $cost;
    }

    /**
     * @param int $month
     * @param int $year
     * @param bool $all
     * @param bool $nonProfit
     * @return mixed
     */
    public static function getEmployees(int $month, int $year, bool $all = true, bool $nonProfit = false)
    {
        if (!$year) {
            $year = Carbon::now()->format('Y');
        }
        $month = Carbon::createFromFormat('Y m', $year . ' ' . $month);
        $startOfMonth = $month->startOfMonth();
        $employees = CollabUsers::where(function (Builder $builder) use ($startOfMonth) {
            $builder->where(function (Builder $query) use ($startOfMonth) {
                /** Active Employee's */
                $query->whereNull('trashed_on');
                $query->where('is_archived', false);
                $query->where('created_on', '<=', $startOfMonth);
            });
            $builder->orWhere(function (Builder $query) use ($startOfMonth) {
                /** Archived Employee's */
                $query->where('is_archived', true);
                $query->where('updated_on', '>', $startOfMonth);
                $query->where('created_on', '<=', $startOfMonth);
            });
        });
        if (!$all) {
            $employees = $employees->where('non_profit', $nonProfit);
        }
        return $employees->get();
    }

    /**
     * Getting last salary from that end of month retrospectively
     *
     * @param int $year
     * @param int $month
     * @return int
     */
    public function getSalary(int $year, int $month)
    {
        if (!$year) {
            $year = Carbon::now()->format('Y');
        }
        $month = Carbon::createFromFormat('Y m', $year . ' ' . $month);

        $salary = EmployeeRateHistory::where('collab_user', $this->id)
            ->where('from', '<=', $month->endOfMonth())
            ->orderBy('from', 'desc')
            ->first();
        return $salary ? $salary->salary_bruto : 0;
    }

    public function estimatedEarnings()
    {
        if ($this->non_profit) {
            return 0;
        }
        return round($this->expensesToCover() + ($this->cost * (1 + $this->profit / 100)), 2);
    }

    public function expensesToCover()
    {
        if ($this->non_profit) {
            return 0;
        }

        $profitableEmployees = $this->getProgrammers()->active()->count();
        return round($this->getMonthlyExpensesTotal() / $profitableEmployees, 2);
    }

    /**
     * @return mixed
     */
    private function getMonthlyExpensesTotal()
    {
        return $this->getRecurringExpenses() + $this->getNonProfitEmployees();
    }

    /**
     * @return mixed
     */
    private function getRecurringExpenses()
    {
        return RecurringExpense::sum('size');
    }

    /**
     * @return mixed
     */
    private function getNonProfitEmployees()
    {
        return CollabUsers::where('non_profit', true)->sum('cost');
    }

    public function estimatedWorkHours()
    {
        $now = Carbon::now();
        return ($this->expected_work_time * Time::getWorkdays($now->year, $now->month));
    }

    /**
     * @return mixed
     */
    public function getProfitableEmployees()
    {
        return CollabUsers::Profitable()->active();
    }

    public function getCurrentOrLateTasks()
    {
        return $this->tasks()
            ->where(function (Builder $builder) {
                $builder
                    ->whereBetween('completed_on', [
                        date('Y-m-d') . ' 00:00:00',
                        date('Y-m-d') . ' 23:59:59',
                    ])
                    ->orWhereNull('completed_on');
            })
            ->whereNull('trashed_on')
            ->where('start_on', '<=', date('Y-m-d'))
            ->whereHas('assigned')
            ->whereDoesntHave('qa')
            ->whereDoesntHave('stopped')
            ->get()
            ->filter(function (Task $task) {
                return $task->start_on <= date('Y-m-d') && !$task->isTaskCompleted();
            });
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, 'assignee_id', 'id')
            ->where('is_trashed', false);
    }

    public function getFullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getRangedTasks(Carbon $date)
    {
        $date = $date->format('Y-m-d');
        return $this
            ->tasks()
            ->whereRaw("DATE('$date') BETWEEN start_on AND due_on")
            ->whereNull('completed_by_id')
            ->whereNull('trashed_on')
            ->whereHas('assigned')
            ->get();
    }

    public function getClassForTimeBooked()
    {
        if (Carbon::now()->isWeekend() || $this->hasAchievedMinimalRequirement()) {
            return 'normal';
        }

        /** @var Carbon $time */
        $time = $this->getTimeSinceLastSubmitting()->updated_on ?? false;
        if (!$time) {
            return 'normal';
        }
        $diff = $time->diffInMinutes();
        switch ($diff) {
            case $diff <= 60:
                return 'success';
            case $diff <= 120:
                return 'info';
            case $diff <= 180:
                return 'warning';
            case $diff > 180:
                return 'danger';
        }
        return 'normal';
    }

    /**
     * @return bool
     */
    private function hasAchievedMinimalRequirement(): bool
    {
        return $this->timeBookedToday() >= $this->expected_work_time;
    }

    public function timeBookedToday()
    {
        return (float)$this->timeBooked()->where('record_date', Carbon::today())->sum('value');
    }

    public function timeBooked()
    {
        return $this
            ->hasMany(Time::class, 'user_id', 'id')
            ->where('is_trashed', false)
            ->where('parent_type', 'Task');
    }

    public function getTimeSinceLastSubmitting()
    {
        return $this->timeBooked()->orderBy('id', 'desc')->first();
    }

    public function getBookedTimes($date)
    {
        return $this
            ->timeBooked()
            ->whereHas('task', function (Builder $builder) {
                return $builder->whereNotIn('project_id', [15, 70]);
            })
            ->where('record_date', '=', $date)
            ->get();
    }

    public function fuel()
    {
        return $this->hasMany(Fuel::class, 'user_id', 'id');
    }

    public function rates()
    {
        return $this
            ->hasMany(EmployeeRateHistory::class, 'collab_user', 'id')
            ->orderBy('employee_rate_history.from', 'desc');
    }

    public function getFuelTotal()
    {
        return $this->fuel->sum('amount');
    }

    public function isOoo()
    {
        return $this->oooDays()
            ->where('from', '<=', Carbon::now())
            ->where('to', '>', Carbon::now())
            ->exists();
    }

    public function oooDays()
    {
        return $this->hasMany(OutOfOffice::class, 'collab_user', 'id')->orderBy('id', 'desc');
    }

    public function getOooReason()
    {
        $oooDay = $this->oooDays()
            ->where('from', '<=', Carbon::now())
            ->where('to', '>', Carbon::now())
            ->first();
        return OutOfOffice::getDateType($oooDay->reason);
    }

    public function updateRate($rate, $cost): void
    {
        $lastRecord = $this->getLastRecord();
        if (is_null($lastRecord) || ($lastRecord->rate != $rate) || ($lastRecord->salary_bruto != $cost)) {
            EmployeeRateHistory::create([
                'collab_user' => $this->id,
                'rate' => $rate,
                'salary_bruto' => $cost,
                'from' => Carbon::now()
            ]);
        }
    }

    public function getLastRecord()
    {
        return EmployeeRateHistory::where('collab_user', $this->id)->orderBy('from', 'desc')->first();
    }

    public function getCostDebugTable()
    {
        $result = [];
        $now = Carbon::now();
        for ($x = 12; $x >= 0; $x--) {
            $oldDate = $now->copy()->subMonths($x);
            $month = $oldDate->format('m');
            $year = $oldDate->format('Y');
            $workdays = Time::getWorkdays($year, $month);
            $oldRate = $this->getOldRate($oldDate);
            $rate = $oldRate ? $oldRate->rate : 0;
            $cost = $this->getHourRateCost($month, $year);
            $result[] = [
                'month' => $month,
                'year' => $year,
                'pmSalary' => CollabUsers::getEmployeesCost($month, (int)$year, false, true),
                'pmQty' => CollabUsers::getEmployees($month, (int)$year, false, true)->count(),
                'devSalary' => CollabUsers::getEmployeesCost($month, (int)$year, false, false),
                'devQty' => CollabUsers::getEmployees($month, (int)$year, false, false)->count(),
                'salary' => $this->getSalary($year, $month),
                'salaryPart' => round($this->getSalaryPart($month, $year) * 100, 2),
                'runningCost' => Expense::getRunningCostForMonth($month, $year),
                'workDays' => $workdays,
                'workHours' => $this->expected_work_time * $workdays,
                'oooDays' => 'TBD',
                'hourCost' => $cost,
                'hourRate' => $rate,
                'margin' => Income::getProfitMargin($rate, $cost),
            ];
        }
        return $result;
    }
}
