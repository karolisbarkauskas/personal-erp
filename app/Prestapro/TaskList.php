<?php

namespace App\Prestapro;

use App\Traits\ActiveTrait;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    use ActiveTrait;

    protected $connection = 'prestapro_collab';

    protected $table = 'task_lists';
}
