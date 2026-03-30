<?php

namespace App\Models\Scopes;

use App\Models\Instrument;

trait InstrumentScopes
{
    public function scopeOfVisible($query)
    {
        return $query ? $query->where('stock_status' != Instrument::HIDDEN) : $query;
    }

    public function scopeOfFeatured($query)
    {
        return $query ? $query->where('featured', true) : $query;
    }

    public function scopeOfFamily($query, $family)
    {
        return $query ? $query->whereHas('spec', function ($q) use ($family) {
            $q->where('instrument_family_id', $family);
        }) : $query;
    }

    public function scopeOfBuilder($query, $builder)
    {
        return $query ? $query->whereHas('spec', function ($q) use ($builder) {
            $q->where('builder_id', $builder);
        }) : $query;
    }

    public function scopeOfCondition($query, $condition)
    {
        return $query ? $query->where('condition', $condition) : $query;
    }

    public function scopeOfPrice($query, $lowPrice, $highPrice)
    {
        return $query ? $query->whereBetween('price', $lowPrice, $highPrice) : $query;
    }
}
