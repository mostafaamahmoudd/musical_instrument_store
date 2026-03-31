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

    public function scopeOfType(Builder $query, ?int $type): Builder
    {
        return $type ? $query->whereHas('spec', function (Builder $q) use ($type) {
            $q->where('instrument_type_id', $type);
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

    public function scopeOfTopWood(Builder $query, ?int $wood): Builder
    {
        return $wood ? $query->whereHas('spec', function (Builder $q) use ($wood) {
            $q->where('top_wood_id', $wood);
        }) : $query;
    }

    public function scopeOfBackWood(Builder $query, ?int $wood): Builder
    {
        return $wood ? $query->whereHas('spec', function (Builder $q) use ($wood) {
            $q->where('back_wood_id', $wood);
        }) : $query;
    }

    public function scopeOfStock(Builder $query, ?string $stock): Builder
    {
        return $stock ? $query->where('stock_status', $stock) : $query;
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

    public function scopeOfSort(Builder $query, $sort): Builder
    {
        return $sort ? match ($sort) {
            'price_low_high' => $query->orderBy('price'),
            'price_high_low' => $query->orderByDesc('price'),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        } : $query;
    }

    public function scopeOfSearch(Builder $query, $search): Builder
    {
        return $search ? $query->whereHas('spec', function (Builder $specQuery) use ($search) {
            $specQuery->where('model', 'like', "%{$search}%")
                ->orWhere('style', 'like', "%{$search}%")
                ->orWhere('finish', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }) : $query;
    }
}
