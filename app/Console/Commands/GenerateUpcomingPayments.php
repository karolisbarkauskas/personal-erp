<?php

namespace App\Console\Commands;

use App\Employee;
use App\Payable;
use App\RecurringExpense;
use Illuminate\Console\Command;

class GenerateUpcomingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate upcoming payments';

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
        $recurringExpenses = RecurringExpense::where('installment', 0)->get();

        foreach ($recurringExpenses as $expense) {
            $deadline = $expense->payment_date - 2;
            Payable::create([
                'name' => $expense->name,
                'amount' => $expense->getExpenseWithVat(),
                'amount_paid' => 0,
                'deadline' => now()->addDays($deadline)
            ]);
        }

        $taxes = 0;

        foreach (Employee::active()->get() as $employee) {
            Payable::create([
                'name' => 'Salary for ' . $employee->full_name,
                'amount' => $employee->salary_to_hands,
                'amount_paid' => 0,
                'deadline' => now()->addDays(13)
            ]);
            $taxes += (($employee->salary_with_vat + $employee->salary_to_cover) - $employee->salary_to_hands) + $employee->refund;
        }

        Payable::create([
            'name' => 'GPM',
            'amount' => $taxes,
            'amount_paid' => 0,
            'deadline' => now()->addDays(27)
        ]);

        Payable::create([
            'name' => 'PSD',
            'amount' => $taxes,
            'amount_paid' => 0,
            'deadline' => now()->addDays(13)
        ]);

        return 0;
    }
}
