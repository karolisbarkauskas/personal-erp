<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OffDays extends Model
{
    public static function isDayOff($date)
    {
        return OffDays::where('day', $date)->exists();
    }
}
