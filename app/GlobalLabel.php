<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalLabel extends Model
{
    protected $connection = 'collab';
    protected $table = 'labels';

    public const BRANDS = [
        self::PRESTAPRO,
        self::ONESOFT,
    ];

    public const ONESOFT = 96;
    public const PRESTAPRO = 93;

    public function scopeTeamLabels($builder)
    {
        return $builder->whereIn('id', self::BRANDS);
    }
}
