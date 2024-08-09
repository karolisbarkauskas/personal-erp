<?php

namespace App;

use App\Traits\TimeConvert;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property \App\CollabUsers employee
 */
class Time extends Model
{
    use TimeConvert;

    const SUMMER_WINTER_CORRECTION = 2;
    public $timestamps = false;

    protected $connection = 'collab';
    protected $table = 'time_records';

    protected $casts = [
        'created_on' => 'datetime',
        'updated_on' => 'datetime'
    ];

    protected $fillable = [
        'billable_status'
    ];

    public function getUpdatedOnAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->addHours(self::SUMMER_WINTER_CORRECTION);
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param array $models
     *
     * @return \App\TimeCollection
     */
    public function newCollection(array $models = [])
    {
        return new TimeCollection($models);
    }

    public function task()
    {
        return $this->hasOne(Task::class, 'id', 'parent_id');
    }

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'parent_id');
    }

    public function employee()
    {
        return $this->hasOne(CollabUsers::class, 'id', 'user_id');
    }

    public function isTimeBookedInOffDay()
    {
        return Carbon::parse($this->record_date)->isWeekend() || OffDays::where('day', $this->record_date)->exists();
    }

    public function getExpenseSize()
    {
        if ($this->employee) {
            return round($this->employee->getHourlyRate($this->created_on) * $this->value, 2);
        }
        return 0;
    }

    public function getNotBillableTime()
    {
        return $this->where('billable_status', false)->sum('value');
    }

    public function getBookedTime()
    {
        return $this->sum('value');
    }

    public function getTimeDifference()
    {
        return abs(($this->getExpectedWorkTime()) - $this->getBillableTime());
    }

    public function getExpectedWorkTime()
    {
        return $this->first()->employee->expected_work_time;
    }

    public function getBillableTime()
    {
        return $this->where('billable_status', true)->sum('value');
    }

    public function getExpectedWorkTimeWithDaysOff($days, $daysOff)
    {
        return $this->first()->employee->expected_work_time * $days - ($daysOff * $this->first()->employee->expected_work_time);
    }

    public static function getBookedTimeForMonth(int $month, $billable = true, $year = false)
    {
        if (!$year){
            $year = Carbon::now()->format('Y');
        }

        $now = Carbon::createFromFormat('Y m', $year.' '.$month);
        $querry = Time::whereBetween('record_date', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->join('tasks', function ($join){
                $join->on('time_records.parent_id','=', 'tasks.id');
                $join->on('time_records.parent_type','=',  DB::raw("'Task'"));
            })
            ->join('projects', function ($join){
                $join->on('tasks.project_id','=', 'projects.id');
                $join->on('projects.ignore','=',   DB::raw("'0'"));
            });
        if ($billable) {
            $querry = $querry->where('billable_status', $billable);
        }
        return $querry->sum('value');
    }

    public static function getAverageBookedTime($year, $billable = true)
    {
        $now = Carbon::createFromFormat('Y', $year);
        $currentMonth = now()->month;
        if ($currentMonth != 1) {
            $currentMonth--;
        }

        $querry = Time::whereBetween('record_date', [$now->copy()->startOfYear(), $now->copy()->endOfYear()])
        ->join('tasks', function ($join){
            $join->on('time_records.parent_id','=', 'tasks.id');
            $join->on('time_records.parent_type','=',  DB::raw("'Task'"));
        })
        ->join('projects', function ($join){
            $join->on('tasks.project_id','=', 'projects.id');
            $join->on('projects.ignore','=',   DB::raw("'0'"));
        });
        if ($billable) {
            $querry = $querry->where('billable_status', $billable);
        }

        return round($querry->sum('value') / $currentMonth);
    }

    public static function getYearlyTime($year, $billable = true)
    {
        $now = Carbon::createFromFormat('Y', $year);
        $querry= Time::whereBetween('record_date', [$now->copy()->startOfYear(), $now->copy()->endOfYear()])
            ->join('tasks', function ($join){
                $join->on('time_records.parent_id','=', 'tasks.id');
                $join->on('time_records.parent_type','=',  DB::raw("'Task'"));
            })
            ->join('projects', function ($join){
                $join->on('tasks.project_id','=', 'projects.id');
                $join->on('projects.ignore','=',   DB::raw("'0'"));
            });
        if ($billable) {
            $querry = $querry->where('billable_status', $billable);
        }

        return $querry->sum('value');
    }

    public static function getWorkdays(int $year, int $month): int
    {
        $workDaysArray = [
            2020 => [22,20,21,21,20,21,22,21,22,22,20,21],
            2021 => [20,19,22,21,21,21,21,22,22,21,20,22],
            2022 => [21,19,22,20,22,21,20,22,22,21,20,21]
        ];
        if (!isset($workDaysArray[$year])){
            return 21;
        }

        return $workDaysArray[$year][$month - 1];
    }
}
