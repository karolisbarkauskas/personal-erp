<?php

namespace App;

use App\Traits\ActiveTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property double $sellable_hours_per_day
 * @property double $salary_with_vat
 * @property mixed $hourly_rate_sellable
 * @property mixed $hourly_rate_with_markup
 * @property mixed $hourly_rate_without_markup
 */
class Employee extends Model
{
    use HasFactory, ActiveTrait;

    protected $fillable = [
        'full_name',
        'salary_with_vat',
        'salary_to_hands',
        'return',
        'sellable_hours_per_day',
        'hourly_rate_sellable',
        'employment_start',
        'employment_end',
        'hourly_rate_without_markup',
        'hourly_rate_with_markup',
        'salary_to_cover',
        'markup',
    ];

    public function isProfitable(): bool
    {
        return $this->hourly_rate_sellable > $this->hourly_rate_without_markup;
    }

    public function calculateDelay($rate, $hours = 1): float
    {
        return round(((float)$rate / $this->hourly_rate_with_markup) * (float)$hours, 2);
    }

    public function calculateClientUsedTime($clientRate, $usedTime): float
    {
        return round(($this->hourly_rate_with_markup * $usedTime) / $clientRate, 2);
    }

    public function calculateClientUsedTimePrime($clientRate, $usedTime): float
    {
        return round(($this->hourly_rate_without_markup * $usedTime) / $clientRate, 2);
    }

}
