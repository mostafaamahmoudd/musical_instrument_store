<?php

namespace App\Models\Relations;

use App\Models\Inquiry;
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
}
