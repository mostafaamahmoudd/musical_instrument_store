<?php

namespace App\Models\Relations;

use App\Models\InstrumentSpec;
use App\Models\InstrumentType;

trait InstrumentFamilyRelations
{
    public function instrumentTypes()
    {
        return $this->hasMany(InstrumentType::class);
    }

    public function instrumentSpecs()
    {
        return $this->hasMany(InstrumentSpec::class);
    }
}
