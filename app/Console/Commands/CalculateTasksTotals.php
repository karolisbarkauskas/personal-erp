<?php

namespace App\Console\Commands;

use App\WeekTask;
use Illuminate\Console\Command;

class CalculateTasksTotals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:totals';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate all total tasks.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        WeekTask::all()->each(function (WeekTask $task) {
            if (preg_match('/^([^-\s]+-[^-\s]+)/', $task->name, $matches)) {
                $parentTask = \App\Task::firstOrCreate([
                    'code' => $matches[0]
                ]);
                $task->update([
                    'task_id' => $parentTask->id
                ]);
                $task->updateTaskParent();
            }
        });
    }
}
