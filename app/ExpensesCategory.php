<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpensesCategory extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'name',
        'parent_id',
        'running_cost',
        'investment'
    ];

    public function accounts(): ?hasMany
    {
        return $this->hasMany(BankAccount::class, 'exp_cat_id', 'id');
    }

    public function expenses(): ?hasMany
    {
        return $this->hasMany(Expense::class, 'category', 'id');
    }

    public function subcategories(): ?hasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function getTotalByMonth($month, $year)
    {
        $now = Carbon::now();
        $monthDate = $now->setMonth((int)$month)->setYear((int)$year);
        $subcategories = $this->subcategories()->select('id')->pluck('id')->push($this->id);

        return Expense::whereIn('category', $subcategories)
            ->whereBetween('issue_date', [$monthDate->copy()->startOfMonth(), $monthDate->endOfMonth()])
            ->sum('size');
    }

    public function getAverageExpenses($year)
    {
        $now = Carbon::createFromFormat('Y', $year);
        $subcategories = $this->subcategories()->select('id')->pluck('id')->push($this->id);
        $currentMonth = now()->month;
        if ($currentMonth != 1) {
            $currentMonth--;
        }

        $total = Expense::whereIn('category', $subcategories)
            ->whereBetween('issue_date', [$now->copy()->startOfYear(), $now->copy()->endOfYear()])
            ->sum('size');

        return round($total / $currentMonth);
    }

    public static function getExpensesAverageForMounth($month, $year = false)
    {
        if (!$year){
            $year = Carbon::now()->format('Y');
        }
        $now = Carbon::createFromFormat('Y m', $year.' '.$month);


        $total = Expense::whereBetween('issue_date', [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()])
            ->sum('size');

        return round($total);
    }

}
