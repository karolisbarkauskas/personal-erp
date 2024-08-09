<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomeServiceValues extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'service_id',
        'amount',
    ];

    public function service()
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }
}
