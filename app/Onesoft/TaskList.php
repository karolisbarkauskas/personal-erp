<?php

namespace App\Onesoft;

use App\Traits\ActiveTrait;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    use ActiveTrait;

    protected $connection = 'onesoft_collab';

    protected $table = 'task_lists';
}
