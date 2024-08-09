<?php

namespace App\Console\Commands;

use App\Client;
use Illuminate\Console\Command;

class ResetHourlyDiffsForClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:hours:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset positive clients hours for quarter';

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
        Client::where('hourly_diff', '>', 0)->each(function ($client) {
            $client->increment('hourly_diff_reset', $client->hourly_diff);
            $client->update(['hourly_diff' => 0]);
        });

        return 0;
    }
}
