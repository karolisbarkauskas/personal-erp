<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{

    const CLOSED = 2;
    const CONFIRMED = 1;
    const DRAFT = 0;

    protected $fillable = [
        'client_id',
        'status',
        'name',
        'description',
    ];

    public function resources(): HasMany
    {
        return $this->hasMany(ProjectResource::class);
    }

    public function hasTask(int $taskId): bool
    {
        return $this->tasks()->where('id', $taskId)->exists();
    }

    public function scopeConfirmed($builder)
    {
        return $builder->where('status', self::CONFIRMED);
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function getTotalBudget(): float
    {
        return $this->resources->sum('client_time_sold') * $this->client->rate;
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }

}
