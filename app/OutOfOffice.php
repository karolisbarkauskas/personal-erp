<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OutOfOffice extends Model
{
    protected $table = 'out_of_office';
    protected $connection = 'mysql';
    protected $fillable = [
        'collab_user',
        'from',
        'to',
        'reason',
    ];

    public const PARENTING_DAY = 1;
    public const VACATION_DAY = 2;
    public const FREE_DAY = 3;
    public const SICK_DAY = 4;

    public static function getDateType($const): string
    {
        $type = '';
        switch ($const) {
            case self::PARENTING_DAY:
                $type = 'Tėvadienis';
                break;
            case self::VACATION_DAY:
                $type = 'Atostogos';
                break;
            case self::FREE_DAY:
                $type = 'Neapmokamos';
                break;
            case self::SICK_DAY:
                $type = 'Ligadienis';
                break;
        }
        return $type;
    }

    public static function getTypeList()
    {
        return Collect([
            self::PARENTING_DAY,
            self::VACATION_DAY,
            self::FREE_DAY,
            self::SICK_DAY
        ]);
    }
}
