<?php

namespace App\Console\Commands;

use App\CashFlow;
use App\Employee;
use App\RecurringExpense;
use App\Settings;
use Illuminate\Console\Command;

class CalculateCashFlow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cash:flow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate incoming cash flow';

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
        CashFlow::query()->where('paid', 1)->delete();

        $currentMonth = now()->year . '-' . now()->month . '-01';
        $recurringExpenses = RecurringExpense::all();

        $this->output->info("Recurring expenses: {$recurringExpenses->count()}");
        $vat = 0;

        /** @var RecurringExpense $expens */
        foreach ($recurringExpenses as $expens) {
            $vat -= $expens->getVatSize();
            CashFlow::create([
                'income_date' => $currentMonth,
                'type' => CashFlow::EXPENSE,
                'flow_name' => $expens->name,
                'initial' => $expens->getExpenseWithVat(),
                'real' => $expens->getExpenseWithVat(),
                'paid' => false,
                'priority' => $expens->priority
            ]);
        }

        $employees = Employee::all();

        $this->output->info("EMPLOYEES: {$employees->count()}");
        $employeeTaxesToPay = 0;

        foreach ($employees as $employee) {
            $taxesToPay = $employee->salary_with_vat - $employee->salary_to_hands;
            $employeeTaxesToPay += $taxesToPay;
            $this->output->info("{$employee->full_name}: Payout {$employee->salary_to_hands}, Taxes {$taxesToPay}");

            CashFlow::create([
                'income_date' => $currentMonth,
                'type' => CashFlow::EXPENSE,
                'flow_name' => "ALGA: $employee->full_name",
                'initial' => $employee->salary_to_hands,
                'real' => $employee->salary_to_hands,
                'paid' => false,
            ]);
        }

        CashFlow::create([
            'income_date' => $currentMonth,
            'type' => CashFlow::EXPENSE,
            'flow_name' => "Darbuotojų mokesčiai",
            'initial' => $employeeTaxesToPay,
            'real' => $employeeTaxesToPay,
            'paid' => false,
        ]);

        CashFlow::create([
            'income_date' => $currentMonth,
            'type' => CashFlow::VAT,
            'flow_name' => "Mokėtinas šio mėn PVM.",
            'initial' => $vat,
            'real' => $vat,
            'paid' => false,
        ]);

        return 0;
    }
}
