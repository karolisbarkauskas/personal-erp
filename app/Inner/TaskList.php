<?php

namespace App\Inner;

use App\Traits\ActiveTrait;
use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    use ActiveTrait;

    protected $connection = 'collab';

    protected $table = 'task_lists';
}
