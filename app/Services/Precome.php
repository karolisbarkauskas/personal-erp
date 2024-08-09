<?php

namespace App\Services;

use App\CollabUsers;
use App\PrecomeTask;
use App\Time;

class Precome
{
    /** @var \App\Precome */
    private $precome;

    public function __construct($client, $project)
    {
        $this->precome = \App\Precome::firstOrCreate([
            'client_id' => $client,
            'project_id' => $project,
        ]);
    }

    public function createRecords($entries): void
    {
        /** @var Time $entry */
        foreach ($entries as $entry) {
            /** @var PrecomeTask $task */
            $task = $this->precome->tasks()->firstOrCreate([
                'project_id' => ($entry->parent_type === 'Task' ? $entry->task->project->id : $entry->parent_id),
                'task_id' => ($entry->parent_type === 'Project' ? null : $entry->parent_id),
            ]);

            $task->entries()->updateOrCreate([
                'time_id' => $entry->id
            ], [
                'user_id' => $entry->user_id,
                'time' => (float)$entry->value,
                'comment' => $entry->summary,
                'entry_time' => $entry->record_date,
                'billable' => $entry->billable_status,
                'time_id' => $entry->id
            ]);
        }

        $this->precome->tasks->each(function (PrecomeTask $precomeTask) {
            $precomeTask->update([
                'snoozed' => false
            ]);
        });
    }

    public function updateTotals()
    {
        $total = 0;
        $totalHours = 0;
        /** @var PrecomeTask $entry */
        foreach ($this->precome->tasks()->get() as $entry) {
            $taskPrice = 0;
            foreach ($entry->entries()->where('billable', true)->get() as $entr) {
                /** @var CollabUsers $user */
                $user = CollabUsers::find($entr->user_id);
                $rate = $user->getLastRecord(); // TODO Need to use getOldRate()
                if ($rate){
                    $total += $entr->time * $rate->rate;
                    $taskPrice += $entr->time * $rate->rate;
                }
                $totalHours += $entr->time;
            }

            $entry->update([
                'total_eur' => $taskPrice,
                'total_hours' => $entry->entries->sum('time')
            ]);
        }
        $this->precome->update([
            'total_hours' => $totalHours,
            'total_eur' => $total
        ]);
    }

    public function recalculatePrecomeTotal()
    {
        $total = $this->precome
            ->tasks()
            ->where('snoozed', false)
            ->get()
            ->fresh()
            ->sum('total_eur');

        $this->precome->update([
            'total_eur' => $total
        ]);
    }
}
