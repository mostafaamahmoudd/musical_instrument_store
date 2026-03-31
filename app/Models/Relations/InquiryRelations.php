<?php

namespace App\Models\Relations;

use App\Models\Instrument;
use App\Models\User;

trait InquiryRelations
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function instrument()
    {
        return $this->belongsTo(Instrument::class);
    }

    public function assignedAdmin()
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }
}
