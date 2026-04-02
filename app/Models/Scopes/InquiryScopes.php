<?php

namespace App\Models\Scopes;

trait InquiryScopes 
{
    public function scopeOfStatus($query, $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }
}
