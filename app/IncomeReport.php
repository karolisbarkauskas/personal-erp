<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeReport extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'task_id',
        'task_link',
        'include',
        'hourly_rate',
        'hours',
        'total',
        'done',
        'profit',
    ];


    public function income()
    {
        return $this->hasOne(Income::class, 'id', 'income_id');
    }

    public function getPercentage()
    {
        if ($this->total <= 0) {
            return 0;
        }

        return round(
            ($this->profit * 100) / $this->total
        );
    }

}
