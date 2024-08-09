<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class InvoiceScope implements Scope
{

    public function apply(Builder $builder, Model $model)
    {
        if (auth()->user()) {
            $builder->where('company_id', auth()->user()->current_company);
        }
    }
}
