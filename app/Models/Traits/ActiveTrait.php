<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ActiveTrait
{

    public function scopeActive(Builder $query): void
    {
        $query->where('status', 1);
    }
}
