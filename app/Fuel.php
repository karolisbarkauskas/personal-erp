<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fuel extends Model
{
    public $timestamps = false;
    protected $table = 'fuel_accountant';
    protected $connection = 'mysql';
    protected $fillable = [
        'user_id',
        'amount',
        'date',
        'type'
    ];

    public function expenseType()
    {
        return $this->hasOne(ExpensesCategory::class, 'id', 'type');
    }
}
