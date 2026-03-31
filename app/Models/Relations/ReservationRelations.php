<?php

namespace App\Models\Relations;

use App\Models\Instrument;
use App\Models\User;

trait ReservationRelations
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }
}
