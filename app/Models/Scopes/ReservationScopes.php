<?php

namespace App\Models\Scopes;

trait ReservationScopes
{
    public function scopeOfStatus($query, $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }
}
