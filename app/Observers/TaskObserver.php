<?php

namespace App\Observers;

use App\WeekTask;

class TaskObserver
{
    public function updated(WeekTask $task)
    {
        $task->list->updateTotals();
        $task->fresh()->updateTaskParent();
    }

    public function deleted(WeekTask $task)
    {
        $task->list->updateTotals();
        $task->fresh()->updateTaskParent();
    }
}
