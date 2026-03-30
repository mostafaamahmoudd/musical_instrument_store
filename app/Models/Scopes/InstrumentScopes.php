<?php

namespace App\Models\Scopes;

use App\Models\Instrument;
use Illuminate\Database\Eloquent\Builder;

trait InstrumentScopes
{
    public function scopeOfVisible(Builder $query): Builder
    {
        return $query
            ->where('stock_status', '!=', Instrument::HIDDEN)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeOfFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeOfFamily(Builder $query, ?int $family): Builder
    {
        return $family ? $query->whereHas('spec', function (Builder $q) use ($family) {
            $q->where('instrument_family_id', $family);
        }) : $query;
    }

    public function scopeOfBuilder(Builder $query, ?int $builder): Builder
    {
        return $builder ? $query->whereHas('spec', function (Builder $q) use ($builder) {
            $q->where('builder_id', $builder);
        }) : $query;
    }

    public function scopeOfCondition(Builder $query, ?string $condition): Builder
    {
        return $condition ? $query->where('condition', $condition) : $query;
    }

    public function scopeOfPrice(Builder $query, $lowPrice, $highPrice): Builder
    {
        if (
            $lowPrice !== null && $lowPrice !== ''
            && $highPrice !== null && $highPrice !== ''
            && $lowPrice > $highPrice
        ) {
            [$lowPrice, $highPrice] = [$highPrice, $lowPrice];
        }

        if ($lowPrice !== null && $lowPrice !== '') {
            $query->where('price', '>=', $lowPrice);
        }

        if ($highPrice !== null && $highPrice !== '') {
            $query->where('price', '<=', $highPrice);
        }

        return $query;
    }
}
