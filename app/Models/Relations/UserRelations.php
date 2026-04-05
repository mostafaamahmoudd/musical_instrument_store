<?php

namespace App\Models\Relations;

use App\Models\AuditLog;
use App\Models\Inquiry;
use App\Models\PriceHistory;
use App\Models\Reservation;
use App\Models\WishlistItem;

trait UserRelations
{
    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class);
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function priceHistories()
    {
        return $this->hasMany(PriceHistory::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
