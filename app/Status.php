<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    const PLANNED = 1;
    const SENT = 2;
    const PAID = 3;
    const DEPT = 4;
    const CREDITED = 5;

    public $timestamps = false;

    protected $table = 'income_statuses';
}
