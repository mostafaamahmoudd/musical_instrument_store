<?php

namespace App\Models\Relations;

use App\Models\InstrumentSpec;
use App\Models\User;

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
}
