<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $table = 'urls';
    protected $connection = 'speed';


    public function googleSpeeds()
    {
        return $this->hasMany(GoogleSpeed::class, 'url_id', 'id');
    }
    public function siteMaps()
    {
        return $this->hasMany(SiteMap::class, 'url_id', 'id');
    }
}
