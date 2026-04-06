<?php

namespace App\Models;

use App\Models\Relations\PriceHistoryRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceHistory extends Model
{
    use HasFactory;
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
