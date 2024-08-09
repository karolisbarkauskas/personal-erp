<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConfigOptions extends Model
{
    protected $table = 'config_option_values';
    protected $connection = 'collab';
}
