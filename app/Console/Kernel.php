<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('debt:calc')->daily();
        $schedule->command('inform:debtors --dry-run')->weeklyOn(1, '9:00');
        $schedule->command('inform:debtors')->weeklyOn(2, '15:00');
        $schedule->command('inform:debtors')->weeklyOn(4, '15:00');
        $schedule->command('tv:bg')->hourly();
        $schedule->command('payments:generate')->monthlyOn(1, '09:00');
        $schedule->command('clients:hours:reset')->quarterly();
        $schedule->command('company:set_invoice_number')->monthly();
        $schedule->command('recurring:invoices')->dailyAt('09:00');
        $schedule->command('scheduledinvoices:send')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
