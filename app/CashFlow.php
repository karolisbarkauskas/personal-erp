<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CashFlow extends Model
{
    const INCOME = 1;
    const EXPENSE = 2;

    const VAT = 3;

    protected $fillable = [
        'income_date',
        'type',
        'flow_name',
        'initial',
        'real',
        'paid',
        'priority',
    ];

    public function incomeConnection()
    {
        return $this->hasOne(Income::class, 'id', 'income_id');
    }

    public function scopeUnpaidIncome(Builder $builder)
    {
        return $builder->where('type', self::INCOME)->where('paid', false);
    }

    public function scopeIncome(Builder $builder)
    {
        return $builder->where('type', self::INCOME);
    }

    public function scopeCurrent(Builder $builder)
    {
        return $builder->where('income_date', now()->format('Y-m-01'));
    }

    public function scopeExpenses(Builder $builder)
    {
        return $builder->whereIn('type', [
            self::EXPENSE,
            self::VAT
        ]);
    }

}
