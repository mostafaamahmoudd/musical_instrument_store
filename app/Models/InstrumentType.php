<?php

namespace App\Models;

use App\Models\Concerns\HasAuditLogs;
use App\Models\Relations\InstrumentTypeRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstrumentType extends Model
{
    use HasFactory;
    use HasAuditLogs;
    use InstrumentTypeRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'instrument_family_id',
        'name',
        'slug',
    ];
}
