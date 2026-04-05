<?php

namespace App\Models\Relations;

use App\Models\InstrumentSpec;

trait WoodRelations
{
    public function backWoodSpecs()
    {
        return $this->hasMany(InstrumentSpec::class, 'back_wood_id');
    }

    public function topWoodSpecs()
    {
        return $this->hasMany(InstrumentSpec::class, 'top_wood_id');
    }
}
