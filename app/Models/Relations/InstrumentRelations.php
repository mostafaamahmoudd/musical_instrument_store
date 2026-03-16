<?php

namespace App\Models\Relations;

use App\Models\InstrumentSpec;
use App\Models\User;

trait InstrumentRelations
{
    public function instrumentSpec()
    {
        return $this->belongsTo(InstrumentSpec::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
