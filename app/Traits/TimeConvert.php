<?php

namespace App\Traits;

use App\Time;
use App\TimeCollection;

trait TimeConvert
{
    public function decimalToTime($decimal)
    {
        $h = intval($decimal);
        $m = round((((($decimal - $h) / 100.0) * 60.0) * 100), 0);
        if ($m == 60) {
            $h++;
            $m = 0;
        }
        return sprintf("%02d:%02d", $h, $m);
    }
}
