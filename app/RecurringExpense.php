<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property Collection $expenses
 */
class RecurringExpense extends Model
{
    protected $fillable = [
        'name',
        'size',
        'category',
        'vatable',
        'contract_expires',
        'payment_date',
        'installment',
        'priority',
        'expense_payment_name',
        'expense_id',
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'recurring_expense');
    }

    public function getRecurringExpensesList()
    {
        return $this
            ->expenses
            ->sortBy('expense_date')
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item['expense_date'])->format('Y-m');
            })
            ->map(function ($group) {
                return [
                    'size' => $group->sum('size'),
                    'expense_date' => \Carbon\Carbon::parse($group->first()['expense_date'])->format('Y-m'),
                ];
            })
            ->values()
            ->toArray();
    }

    public function getSizeChanges()
    {
        if ($this->size == 0) {
            return;
        }

        $data = $this
            ->expenses
            ->sortBy('expense_date')
            ->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item['expense_date'])->format('Y-m');
            })
            ->map(function ($group) {
                return [
                    'size' => $group->sum('size'),
                    'expense_date' => \Carbon\Carbon::parse($group->first()['expense_date'])->format('Y-m'),
                ];
            });

        $sizes = $data->pluck('size');
        if (!$sizes->last()) {
            return;
        }

        $percentageChange = round((($this->size - $sizes->last()) / $this->size) * 100);

        if($percentageChange == 0){
            return '<span class="text-info">↔️0%</span>';
        }

        if ($percentageChange > 0) {
            return '<span class="text-success">⬇️ -' . $percentageChange . '%</span>';
        }

        return '<span class="text-danger">⬆️ +' . abs($percentageChange) . '%</span>';
    }

    public static function getCurrentExpenses()
    {
        return self::active()->get()->sum('size');
    }

    public function scopeActive($builder)
    {
        return $builder->where('contract_expires', '>', now());
    }

    public function isVatable()
    {
        return $this->vatable == 1;
    }

    public function expenseCategory()
    {
        return $this->hasOne(ExpensesCategory::class, 'id', 'category');
    }

    public function getExpenseWithVat()
    {
        if (!$this->isVatable()) {
            return $this->size;
        }

        return round(
            $this->size * 1.21,
            2
        );
    }

    public function getVatSize()
    {
        if (!$this->isVatable()) {
            return 0;
        }

        return $this->getExpenseWithVat() - $this->size;
    }

    public function getCoverage()
    {
        return $this->getCoveredIncome()->implode('average', '+');
    }

    /**
     * @return mixed
     */
    public function getCoveredIncome()
    {
        return RecurringIncome::where('expense_id', $this->id)->get();
    }

}
