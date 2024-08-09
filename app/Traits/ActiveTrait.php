<?php

namespace App\Traits;

trait ActiveTrait
{
    public function scopeActive($builder)
    {
        return $builder->whereNull('employment_end');
    }
}
