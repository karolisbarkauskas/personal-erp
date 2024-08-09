<?php

namespace App\Providers;

use App\Income;
use App\IncomeReport;
use App\Observers\IncomeObserver;
use App\Observers\ProfitObserver;
use App\Observers\TaskObserver;
use App\WeekTask;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Income::observe(IncomeObserver::class);
        WeekTask::observe(TaskObserver::class);
        IncomeReport::observe(ProfitObserver::class);
    }
}
