<?php

namespace App\Models\Relations;

use App\Models\Inquiry;
use App\Models\InstrumentSpec;
use App\Models\AuditLog;
use App\Models\PriceHistory;
use App\Models\Reservation;
use App\Models\User;
use App\Models\WishlistItem;

trait InstrumentRelations
{
    public function spec()
    {
        return $this->belongsTo(InstrumentSpec::class, 'instrument_spec_id');
    }

    public function instrumentSpec()
    {
        return $this->spec();
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function createdBy()
    {
        return $this->creator();
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function updatedBy()
    {
        return $this->updater();
    }

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
}
