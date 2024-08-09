<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Icon extends Model implements HasMedia
{
    use InteractsWithMedia;
    public $timestamps = false;

    protected $fillable = [
        'name'
    ];
}
