<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    public const ASSIGNED = 29;
    public const CRITICAL = 71;
    public const HIGH_PRIORITY = 65;
    public const NORMAL_PRIORITY = 66;
    public const LOW_PRIORITY = 52;
    const COMPLETED = 68;
    const NO_PRIORITY = 999999999;
    const WAITING = 74;
    const QA = 63;
    const TESTED = 80;
    const DEPLOYED_LIVE = 99;

    public $timestamps = false;

    protected $fillable = [
        'parent_type',
        'parent_id',
        'label_id'
    ];
    protected $table = 'parents_labels';

    public static function formatPrice($price, $withoutCurrency = null, $decPoint = '.', $decimals = 2)
    {
        return number_format($price, $decimals, $decPoint, ' ')
            . (($withoutCurrency) ? '' : '€');
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param array $models
     *
     * @return \App\LabelCollection
     */
    public function newCollection(array $models = [])
    {
        return new LabelCollection($models);
    }

    public function globalLabel()
    {
        return $this
            ->hasOne(GlobalLabel::class, 'id', 'label_id');
    }

    public function showPriority()
    {
        return in_array($this->label_id, [
            self::CRITICAL,
            self::HIGH_PRIORITY,
            self::NORMAL_PRIORITY,
            self::LOW_PRIORITY
        ]);
    }
}
