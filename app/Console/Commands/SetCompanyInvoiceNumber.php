<?php

namespace App\Console\Commands;

use App\Company;
use Illuminate\Console\Command;

class SetCompanyInvoiceNumber extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:set_invoice_number';

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
        $year = date('Y');
        $month = now()->format('m');

        Company::where('id', 2)->get()
            ->each(function ($company) use ($year, $month) {
                $company->update([
                    'proforma' => "EP-{$year}{$month}-",
                    'credit' => "EC-{$year}{$month}-",
                    'invoice' => "EE-{$year}{$month}-",
                ]);
            });

        return 0;
    }
}
