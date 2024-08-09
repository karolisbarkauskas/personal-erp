<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property Carbon $active_from
 * @property Carbon $active_to
 * @property bool $active
 */
class TvNew extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $table = 'contents';

    protected $fillable = [
        'title',
        'active_from',
        'active_to',
        'active',
        'heading',
        'content',
        'icon_id',
        'side',
        'created_by',
    ];

    protected $casts = [
        'active_from' => 'datetime',
        'active_to' => 'datetime',
    ];

    public function getActive()
    {
        return $this->active_from->format('Y-m-d H:i') . ' -> ' . $this->active_to->format('Y-m-d H:i');
    }

    public function isActive()
    {
        return $this->active_from->lte(now()) && $this->active_to->gte(now()) && $this->active;
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
}
