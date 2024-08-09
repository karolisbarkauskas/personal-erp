<?php

namespace App\Inner;

use App\Traits\ActiveTrait;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use ActiveTrait;
    protected $connection = 'collab';

    public function taskLists()
    {
        return $this->hasMany(TaskList::class)->whereNull('completed_on')->whereNull('trashed_on');
    }
}
