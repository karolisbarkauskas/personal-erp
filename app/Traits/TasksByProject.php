<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait TasksByProject
{
    public function scopeGetLastHoursTasks(Builder $builder)
    {
        return $builder->whereBetween(
            'created_on', [
                now()->subHours(4)->startOfHour(),
                now()->subHours(4)->endOfHour()
            ]
        )->whereNull('completed_on');
    }
}
