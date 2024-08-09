<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncomeCost extends Model
{
    use SoftDeletes;

    public const EMPLOYEE_COST = 1;

    public const EXPENSES_COST = 2;

    protected $fillable = [
        'id_income_categories',
        'expense_type',
        'percentage',
        'user_id',
        'category',
        'from',
        'to'
    ];


    public function employee(): ?belongsTo
    {
        return $this->belongsTo(CollabUsers::class, 'user_id');
    }

    public function expenseCategory(): ?belongsTo
    {
        return $this->belongsTo(ExpensesCategory::class, 'category');
    }
}
