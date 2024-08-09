<?php

namespace App;

class Activity extends \Spatie\Activitylog\Models\Activity
{
    public function causerInitials()
    {
        $words = explode(' ', $this->causer->name);
        return strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
    }
}
