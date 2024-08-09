<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * @property Carbon $date
 */
class Import extends Model implements ToModel, WithHeadingRow
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'number',
        'transaction_number',
        'date',
        'payer',
        'sum',
        'purpose',
        'credit_debit'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function model(array $row)
    {
        if (!Import::where('number', $row['israso_nr'])->withTrashed()->first()) {
            return new Import([
                'type' => $row['tipas'],
                'number' => $row['israso_nr'],
                'transaction_number' => $row['pervedimo_nr'],
                'date' => Carbon::parse($row['data_ir_laikas'])->format('Y-m-d'),
                'payer' => $row['gavejas_moketojas'],
                'sum' => (double) str_replace(',', '.', $row['suma_ir_valiuta']),
                'purpose' => $row['paskirtis'],
                'credit_debit' => $row['kreditas_debetas']
            ]);
        }
    }

    public function expenseCategory()
    {
        if ($this->payer && Employee::where('full_name', $this->payer)->first()) {
            return 2;
        }

        return false;
    }

    public function recurringExpenseCategory()
    {
        if ($this->payer && $expense = RecurringExpense::where('expense_payment_name', $this->payer)->first()) {
            return $expense->id;
        }

        return false;
    }

    /**
     * @return false|string
     */
    private function vmi()
    {
        return strstr($this->payer, 'Valstybinio socialinio draudimo fondo valdyba') ||
            strstr($this->payer, 'VMI, Valstybinė mokesčių inspekcija');
    }

    private function employeePayment()
    {
        return false;
    }

    private function office()
    {
        return strstr($this->payer, 'ŽVC') || strstr($this->payer, 'Eden Springs');
    }

    private function fuel()
    {
        return strstr($this->payer, 'EMSI');
    }

    private function addons()
    {
        return strstr($this->payer, 'PRESTASHOPADDON');
    }

    private function software()
    {
        return $this->isJetBrains() ||
            strstr($this->payer, 'SIZZY') ||
            strstr($this->payer, 'SKETCH');
    }

    /**
     * @return false|string
     */
    private function isJetBrains()
    {
        return strstr($this->payer, 'JetBrains');
    }

    private function server()
    {
        return strstr($this->payer, 'Proservis');
    }

    private function food()
    {
        return strstr($this->payer, 'Wolt') || strstr($this->payer, 'dodopizza');
    }

    private function commissions()
    {
        return is_null($this->type);
    }

    public function ignored()
    {
        return $this->isInnerPayment();
    }

    public function getPayerAttribute($value)
    {
        return strpos($value, '|') !== false ? substr($value, 0, strpos($value, '|')) : $value;
    }

    /**
     * @return false|string
     */
    private function isInnerPayment()
    {
        return strstr($this->payer, 'INVOYER TECHNOLOGIES') || strstr($this->payer, 'ECHO EDGE');
    }

    public function getVat(): int
    {
        if ($this->date->isBefore('2024-04-23')) {
            return 0;
        }

        return $this->commissions() ||
        $this->isWorhan() ||
        $this->fuel() ||
        $this->vmi() ||
        $this->employeePayment() ||
        $this->isJetBrains() ||
        $this->addons() ? 0 : 21;
    }

    private function isWorhan()
    {
        return strstr($this->payer, 'WORHAN');
    }

    public function process($sale, float $vat, $expenseCategory, $income, $expenseDeptId, $recurringExpense = null)
    {
        if (!$this->isIncome() && $expenseCategory) {
            return $this->processExpense($expenseDeptId, $vat, $expenseCategory, $sale, $recurringExpense);
        }
        if ($this->isIncome() && $income) {
            return $this->processIncome(Income::find($income));
        }
        return false;
    }

    public function isIncome()
    {
        return $this->sum > 0;
    }

    /**
     * @param $expenseDeptId
     * @param float $vat
     * @param $expenseCategory
     * @param $sale
     * @return bool
     */
    public function processExpense($expenseDeptId, float $vat, $expenseCategory, $sale, $recurringExpense): bool
    {
        $amount = abs($this->sum / (1 + ($vat / 100)));
        $expense = Expense::create([
            'name' => $this->payer ?? $this->purpose,
            'size' => $amount,
            'category' => (int)$expenseCategory,
            'vat_size' => $vat,
            'expense_date' => $this->date,
            'recurring_expense' => (int)$recurringExpense,
            'description' => $this->purpose ?? $this->payer
        ]);
        if ($sale) {
            $expense->update([
                'sale_id' => $sale
            ]);
        }
        return true;
    }

    /**
     * @param Income $income
     * @return bool
     */
    public function processIncome(Income $income): bool
    {
        $income->payment()->create([
            'amount' => $this->sum,
            'comment' => $this->payer . ' ' . $this->purpose
        ]);
        if ($income->isPaid()) {
            $income->markAsPaid();
        }
        return true;
    }
}
