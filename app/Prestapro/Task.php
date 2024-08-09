<?php

namespace App\Prestapro;

use App\Traits\ActiveTrait;
use App\Traits\TasksByProject;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use ActiveTrait;
    use TasksByProject;

    protected $connection = 'prestapro_collab';

    protected $table = 'tasks';

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }
}
