<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientStat extends Model
{
    public $timestamps = false;
    protected $table = 'client_stats';
    protected $connection = 'mysql';
    protected $fillable = [
        'year',
        'income_percentage'
    ];

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    /**
     * @throws \Exception
     */
    public function getRandomColor()
    {
        $rgbColor = array();

        foreach (array('r', 'g', 'b') as $color) {
            $rgbColor[$color] = random_int(0, 255);
        }

        return implode(',', $rgbColor);
    }
}
