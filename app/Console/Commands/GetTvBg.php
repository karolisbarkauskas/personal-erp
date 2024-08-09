<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetTvBg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tv:bg';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get TV background photo';

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
        $photo = Http::get("https://api.unsplash.com/photos/random/?client_id=9kYOrufPE56kNvtX0lPbMsz3AH3L38Ty34Sk_9CoR_Y&count=1&query=city%20skyline%20night&fit=2250x1500&color=black&orientation=landscape")->json();

        if (isset($photo[0])) {
            $url = file_get_contents($photo[0]['urls']['raw']);
            $this->output->info($photo[0]['urls']['raw']);
            file_put_contents(public_path('tv-bg.png'), $url);
        }

        return 0;
    }
}
