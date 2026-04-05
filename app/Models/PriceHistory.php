<?php

namespace App\Models;

use App\Models\Relations\PriceHistoryRelations;
use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    use PriceHistoryRelations;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'instrument_id',
        'changed_by',
        'old_price',
        'new_price',
    ];
}
