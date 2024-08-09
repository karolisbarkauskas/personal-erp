<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalNotes extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'notes'
    ];
}
