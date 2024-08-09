<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubSale extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
        'sale_id',
        'price',
        'complete',
        'sold',
        'income_id'
        ];

    public function income()
    {
        return $this->hasOne(Income::class, 'id', 'income_id');
    }
}
