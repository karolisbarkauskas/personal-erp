<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property Employee $employee
 */
class ProjectResource extends Model
{
    protected $fillable = [
        'employee_id'
    ];

    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class, 'id', 'employee_id');
    }

    public function project(): HasOne
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function getTotalHoursUsed()
    {
        return WeekTask::whereIn('task_id', $this->project->tasks->pluck('id'))
            ->where('employee_id', $this->employee_id)
            ->get()
            ->sum('employee_hours');
    }
}
