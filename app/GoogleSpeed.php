<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GoogleSpeed extends Model
{
    protected $table = 'results_google';
    protected $connection = 'speed';
}
