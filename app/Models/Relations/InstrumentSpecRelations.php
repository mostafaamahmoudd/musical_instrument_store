<?php

namespace App\Models\Relations;

use App\Models\Builder;
use App\Models\Instrument;
use App\Models\InstrumentFamily;
use App\Models\InstrumentType;
use App\Models\Wood;

trait InstrumentSpecRelations
{
    public function instrumentFamily()
    {
        return $this->belongsTo(InstrumentFamily::class);
    }

    public function builder()
    {
        return $this->belongsTo(Builder::class);
    }

    public function instrumentType()
    {
        return $this->belongsTo(InstrumentType::class);
    }

    public function backWood()
    {
        return $this->belongsTo(Wood::class, 'back_wood_id');
    }

    public function topWood()
    {
        return $this->belongsTo(Wood::class, 'top_wood_id');
    }

    public function instruments()
    {
        return $this->hasMany(Instrument::class);
    }
}
