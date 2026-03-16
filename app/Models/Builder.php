<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InstrumentSpec;

class Builder extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'country',
        'is_active',
    ];

    public function instrumentSpecs()
    {
        return $this->hasMany(InstrumentSpec::class);
    }
}
