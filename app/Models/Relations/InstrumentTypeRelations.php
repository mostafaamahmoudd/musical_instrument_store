<?php

namespace App\Models\Relations;

use App\Models\InstrumentFamily;

trait InstrumentTypeRelations
{
    public function instrumentFamily()
    {
        return $this->belongsTo(InstrumentFamily::class);
    }
}
