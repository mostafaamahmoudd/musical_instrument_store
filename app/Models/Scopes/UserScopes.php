<?php

namespace App\Models\Scopes;

trait UserScopes 
{
    public function scopeOfType($query, $type)
    {
        return $type ? $query->where('type', $type) : $query;
    }
}
