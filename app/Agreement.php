<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_group_id',
        'name',
        'description',
        'task_id',
        'resource_id', // TODO Implement Resources
        'budget',
        'estimate',
        'cost',
        'from',
        'to'
    ];

    public $progress;

    public function group(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AgreementGroup::class, 'id', 'agreement_group_id');
    }

    public function task(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Task::class, 'id', 'task_id');
    }

    public function leftDays(): int
    {
        $taskDeadline = Carbon::createFromFormat('Y-m-d',$this->to);
        $now = Carbon::now();
        return $now->diffInDays($taskDeadline, false);
    }

    public function getProgress()
    {
        if ($this->task->completed_on) {
            $this->progress = 100;
            return $this->progress;
        }
        $timeBooked = $this->task->timeBooked->sum('value');
        if (!$timeBooked) {
            $this->progress = 0;
            return $this->progress;
        }
        $this->progress = round(($this->task->estimate / $this->task->timeBooked->sum('value')) * 100, 2);
        return $this->progress;
    }

    public function getProgressColor()
    {
        if (is_null($this->progress)){
            $this->getProgress();
        }
        $days = $this->leftDays();
        if ($days <= 0) {
            return 'red';
        }

        if ($days > 0 && $days < 5) {
            return 'yellow';
        }
        if ($days >= 5) {
            return 'green';
        }
        return 'blue';
    }
}
