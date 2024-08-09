<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollabMaps extends Model
{
    public $timestamps = false;
    protected $connection = 'mysql';

    protected $fillable = [
        'onesoft_collab_id',
        'prestapro_collab_id',
        'inner_collab_id',
    ];

    public function onesoftCollab()
    {
        return $this->hasOne(\App\Onesoft\Project::class, 'id', 'onesoft_collab_id');
    }

    public function prestaProCollab()
    {
        return $this->hasOne(\App\Prestapro\Project::class, 'id', 'prestapro_collab_id');
    }

    public function innerCollab()
    {
        return $this->hasOne(\App\Inner\Project::class, 'id', 'inner_collab_id');
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function taskListsMap()
    {
        return $this->hasMany(CollabTasksMap::class, 'map_id', 'id');
    }
}
