<?php

namespace App\Models\Relations;

use App\Models\InstrumentType;

trait InstrumentFamilyRelations
{
    public function instrumentTypes()
    {
        return $this->hasMany(InstrumentType::class);
    }
}
