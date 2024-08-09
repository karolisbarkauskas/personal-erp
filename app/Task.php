<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'client_time_sold',
        'client_time_used',
    ];

    public function weeklyTasks(): HasMany
    {
        return $this->hasMany(WeekTask::class);
    }

    public function getPrice()
    {
        if (!$this->weeklyTasks->first()->client) {
            return 0;
        }
        return round($this->client_time_sold * $this->weeklyTasks->first()->client->rate);
    }

}
