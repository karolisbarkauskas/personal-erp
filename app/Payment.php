<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'amount',
        'comment'
    ];

    public static function boot()
    {
        parent::boot();

        self::created(function ($payment) {

        });
    }

    public function income()
    {
        return $this->hasOne(Income::class, 'id', 'income_id');
    }
}
