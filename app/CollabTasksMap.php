<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollabTasksMap extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'map_id',
        'onesoft_list_id',
        'prestapro_list_id',
        'inner_clist_id',
    ];

    public function onesoftList()
    {
        return $this->hasOne(\App\Onesoft\TaskList::class, 'id', 'onesoft_list_id');
    }

    public function prestaProList()
    {
        return $this->hasOne(\App\Prestapro\TaskList::class, 'id', 'prestapro_list_id');
    }

    public function innerList()
    {
        return $this->hasOne(\App\Inner\TaskList::class, 'id', 'inner_clist_id');
    }
}
