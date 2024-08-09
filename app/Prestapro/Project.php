<?php

namespace App\Prestapro;

use App\Traits\ActiveTrait;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use ActiveTrait;

    protected $connection = 'prestapro_collab';

    public function taskLists()
    {
        return $this->hasMany(TaskList::class)->whereNull('completed_on')->whereNull('trashed_on');
    }
}
