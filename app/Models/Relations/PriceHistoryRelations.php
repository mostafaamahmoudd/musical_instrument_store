<?php

namespace App\Models\Relations;

use App\Models\Instrument;
use App\Models\User;

trait PriceHistoryRelations
{
    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
