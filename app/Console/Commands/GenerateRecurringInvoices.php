<?php

namespace App\Console\Commands;

use App\RecurringIncome;
use Illuminate\Console\Command;

class GenerateRecurringInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recurring:invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $invoices = RecurringIncome::where('next_invoice_date', now()->format('Y-m-d'))->get();
        $invoices->each(fn(RecurringIncome $income) => $income->generateInvoice());

        return 0;
    }
}
