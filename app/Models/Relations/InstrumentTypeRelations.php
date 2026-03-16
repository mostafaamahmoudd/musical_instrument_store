<?php

namespace App\Models\Relations;

use App\Models\InstrumentFamily;
use App\Models\InstrumentSpec;

trait InstrumentTypeRelations
{
    public function instrumentFamily()
    {
        return $this->belongsTo(InstrumentFamily::class);
    }

    public function instrumentSpecs()
    {
        return $this->hasMany(InstrumentSpec::class);
    }
}
