<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientContacts extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';

    public $timestamps = false;

    protected $fillable = [
        'full_name',
        'email',
        'client_id',
        'phone',
    ];
}
