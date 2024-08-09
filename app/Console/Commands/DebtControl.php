<?php

namespace App\Console\Commands;

use App\Income;
use Illuminate\Console\Command;

class DebtControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'debt:calc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for paid invoices';

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
        $incomes = Income::where('status', Income::SENT)->where('invoice_type', Income::INVOICE)->get();
        /** @var Income $income */
        foreach ($incomes as $income) {
            if ($income->getPaymentDeadline()) {
                $this->output->text($income->invoice_no . ' is debtor');
                $income->update(['status' => Income::DEBT]);
            }
        }
    }
}
