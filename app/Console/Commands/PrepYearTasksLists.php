<?php

namespace App\Console\Commands;

use App\TaskList;
use Illuminate\Console\Command;

class PrepYearTasksLists extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'year:prep';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set tasks lists for the current year';

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
        for ($i = 1; $i <= now()->weeksInYear; $i++) {
            TaskList::create([
                'year' => now()->year,
                'week' => $i,
                'employee_hours_available' => 0,
                'employee_hours_used' => 0,
                'employee_hours_booked' => 0,
            ]);
        }

        return 0;
    }
}
