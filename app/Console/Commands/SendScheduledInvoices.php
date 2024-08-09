<?php

namespace App\Console\Commands;

use App\IncomeEmails;
use Illuminate\Console\Command;

class SendScheduledInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduledinvoices:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send scheduled invoices';

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
        IncomeEmails::where('send_at', now()->format('Y-m-d H:i:00'))
            ->get()
            ->each(fn(IncomeEmails $email) => $email->income->sendViaEmail($email, $email->locale));

        return 0;
    }
}
