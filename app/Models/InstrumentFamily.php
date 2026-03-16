<?php

namespace App\Models;

use App\Models\Relations\InstrumentFamilyRelations;
use Illuminate\Database\Eloquent\Model;

class InstrumentFamily extends Model
{
    use InstrumentFamilyRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];
}
