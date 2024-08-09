<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property Carbon $deadline
 */
class Payable extends Model
{
    use SoftDeletes;

    protected $casts = [
        'deadline' => 'date'
    ];

    protected $fillable = [
        'name',
        'deadline',
        'amount',
        'amount_paid',
    ];

    public function getPriorityBadge()
    {
        switch ($this->priority) {
            case 1:
                return "<span class='badge badge-danger text-white'>Critical (PAY NOW)</span>";
            case 2:
                return "<span class='badge badge-warning text-black-50'>Normal (PAY until {$this->deadline})</span>";
            case 3:
                return "<span class='badge badge-info'>LOW (PAY)</span>";
        }
    }

    public function isOverDue(): bool
    {
        return $this->deadline->isPast();
    }

}
