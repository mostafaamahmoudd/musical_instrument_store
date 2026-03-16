<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\InstrumentSpec;

class Wood extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wood';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    public function backWoodSpecs()
    {
        return $this->hasMany(InstrumentSpec::class, 'back_wood_id');
    }

    public function topWoodSpecs()
    {
        return $this->hasMany(InstrumentSpec::class, 'top_wood_id');
    }
}
