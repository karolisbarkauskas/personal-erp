<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EmployeeRateHistory extends Model
{
    public $timestamps = false;
    protected $table = 'employee_rate_history';
    protected $connection = 'mysql';
    protected $fillable = [
        'collab_user',
        'rate',
        'salary_bruto',
        'from',
    ];

    public function employee(): hasOne
    {
        return $this->hasOne(CollabUsers::class, 'id', 'collab_user');
    }
}
