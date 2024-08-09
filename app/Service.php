<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    const SOFTWARE_DEVELOPMENT = 1;

    protected $fillable = [
        'name',
    ];
}
