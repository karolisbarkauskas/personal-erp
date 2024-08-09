<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BankAccount extends Model
{

    protected $fillable = [
        'exp_cat_id', 'iban'
    ];
    public function expenseCategory(): ?belongsTo
    {
        return $this->belongsTo(ExpensesCategory::class, 'exp_cat_id');
    }

}
