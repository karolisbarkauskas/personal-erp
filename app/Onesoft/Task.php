<?php

namespace App\Onesoft;

use App\Traits\ActiveTrait;
use App\Traits\TasksByProject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Task extends Model
{
    use ActiveTrait;
    use TasksByProject;

    protected $connection = 'onesoft_collab';

    protected $table = 'tasks';

    public function project()
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }
}
