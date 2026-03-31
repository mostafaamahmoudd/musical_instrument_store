<?php

namespace App\Models;

use App\Models\Relations\WishlistItemRelations;
use Illuminate\Database\Eloquent\Model;

class WishlistItem extends Model
{
    use WishlistItemRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'instrument_id',
    ];
}
