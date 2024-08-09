<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class IncomeCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'logo_link',
        'brand_link',
        'domain',
    ];


    public function incomes(): ?hasMany
    {
        return $this->hasMany(Income::class, 'category', 'id');
    }

    public function getTotalIncomeByMonth($month, $year)
    {
        $now = Carbon::now();
        $monthDate = $now->setMonth((int)$month)->setYear((int)$year);
        $incomes = $this->incomes()
            ->select(DB::raw('sum(incomes.amount) as total'))
            ->where('status', '!=', Income::PLANNED)
            ->whereBetween('income_date', [$monthDate->copy()->startOfMonth(), $monthDate->endOfMonth()])
            ->first()
            ->toArray();
        return $incomes['total'];
    }

    public function getAverageIncome($year)
    {
        $now = Carbon::createFromFormat('Y', $year);
        $currentMonth = now()->month;
        if ($currentMonth != 1) {
            $currentMonth--;
        }

        $total = Income::whereBetween('income_date', [$now->copy()->startOfYear(), $now->copy()->endOfYear()])
            ->where('category', $this->id)
            ->sum('amount');

        return round($total / $currentMonth);
    }


    public function getExpenseForMonth(int $month, $year = false)
    {
        if (!$year) {
            $year = Carbon::now()->format('Y');
        }
        $now = Carbon::createFromFormat('Y m', $year . ' ' . $month);

        $costs = IncomeCost::where('from', '<', $now->copy()->startOfMonth())->where(
            function (Builder $builder) use ($now) {
                $builder->whereNull('to')->orWhere('to', '<=', $now->copy()->endOfMonth());
            }
        )->where('id_income_categories', $this->id)
            ->with(['employee', 'expenseCategory'])
            ->get();
        $costSum = 0;
        /** @var IncomeCost $cost */
        foreach ($costs as $cost) {
            if ($cost->employee) {
                $costSum += $cost->employee->cost * round($cost->percentage / 100, 4);
            }
            if ($cost->expenseCategory) {
                $costSum += $cost->expenseCategory->getTotalByMonth($month, $year) * round($cost->percentage / 100, 4);
            }
        }
        return $costSum;
    }

    public function getTotalByMonth(int $month, $year = false)
    {
        $cost = $this->getExpenseForMonth($month, $year);
        $income = $this->getTotalIncomeByMonth($month, $year);
        return $income - $cost;
    }

    public function getPlannedIncome($date)
    {
        return $this->hasMany(
            PlannedIncome::class,
            'id_income_category',
            'id'
        )
            ->where('income_period', 'LIKE', "%$date%")
            ->get();
    }

}
