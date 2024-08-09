<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeTracker extends Model
{
    protected $table = 'flow_trackers';
    public $timestamps = false;
    protected $fillable = [
        'collab_user',
        'tracking_user',
        'severity_level'
    ];

    public function user()
    {
        return $this->hasOne(CollabUsers::class, 'id', 'collab_user');
    }
}
