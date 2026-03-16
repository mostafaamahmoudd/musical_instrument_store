<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
