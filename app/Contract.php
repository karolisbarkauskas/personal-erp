<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';

    public $timestamps = false;

    protected $fillable = [
        'date',
        'number',
        'client_id'
    ];
}
