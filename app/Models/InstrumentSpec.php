<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Relations\InstrumentSpecRelations;

class InstrumentSpec extends Model
{
    use InstrumentSpecRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'instrument_family_id',
        'builder_id',
        'instrument_type_id',
        'model',
        'num_strings',
        'back_wood_id',
        'top_wood_id',
        'style',
        'finish',
        'description',
    ];
}
