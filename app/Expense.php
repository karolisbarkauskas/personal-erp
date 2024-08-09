<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Expense extends Model implements HasMedia
{
    use SoftDeletes;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'size',
        'category',
        'vat_size',
        'expense_date',
        'issue_date',
        'dept',
        'description',
        'sale_id',
        'collab_user',
        'recurring_expense',
    ];

    public static function getRunningExpenses()
    {
        return RecurringExpense::where('installment', 0)->get()->sum('size') + Employee::active()->sum('salary_to_cover');
    }

    public static function getExpenseAverageForMonth(int $month, bool $all = true, $year = false)
    {

        if (!$year) {
            $year = Carbon::now()->format('Y');
        }
        $now = Carbon::createFromFormat('Y m', $year . ' ' . $month);

        $query = Expense::whereBetween('issue_date', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()]);
        if (!$all) {
            $query = $query
                ->join('expenses_categories', 'expenses_categories.id', 'expenses.category')
                ->where('expenses_categories.investment', false);
        }
        return $query->sum('size');
    }

    public static function getYearlyExpense(int $year, bool $all = true)
    {
        $now = Carbon::createFromFormat('Y', $year);

        $query = Expense::whereBetween('issue_date', [$now->copy()->startOfYear(), $now->copy()->endOfYear()]);
        if (!$all) {
            $query = $query
                ->join('expenses_categories', 'expenses_categories.id', 'expenses.category')
                ->where('expenses_categories.investment', false);
        }
        return $query->sum('size');
    }

    /**
     * Calculating all costs whose categories are assigned to running costs.
     * Costs are selected for specific month. Not day!
     * And finally we are adding non-profitable employees salaries to these costs
     */
    public static function getRunningCostForMonth(int $month, $year = false): float
    {
        if (!$year) {
            $year = Carbon::now()->format('Y');
        }
        $monthObj = Carbon::createFromFormat('Y m', $year . ' ' . $month);
        $query = Expense::whereBetween('issue_date', [$monthObj->copy()->startOfMonth(), $monthObj->copy()->endOfMonth()])
            ->join('expenses_categories', 'expenses_categories.id', 'expenses.category')
            ->where('expenses_categories.running_cost', true);
        $nonProfitEmployeesSalaries = CollabUsers::getEmployeesCost($month, (int)$year, false, true);
        return $query->sum('size') + $nonProfitEmployeesSalaries;
    }

    public function scopeDepts(Builder $builder)
    {
        return $builder->where('dept', true);
    }

    public function expenseCategory()
    {
        return $this->hasOne(ExpensesCategory::class, 'id', 'category');
    }

    public function user()
    {
        return $this->hasOne(CollabUsers::class, 'id', 'collab_user');
    }
}
