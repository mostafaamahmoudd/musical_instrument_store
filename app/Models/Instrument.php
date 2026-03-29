<?php

namespace App\Models;

use App\Models\Relations\InstrumentRelations;
use Illuminate\Database\Eloquent\Model;

class Instrument extends Model
{
    use InstrumentRelations;

    /*
     * code for different condition types
     */
    const NEW_CONDITION = 'new';
    const USED_CONDITION = 'used';
    const VINTAGE_CONDITION = 'vintage';

    /*
     * code for different stock statuses
     */
    const AVAILABLE = 'available';
    const RESERVED = 'reserved';
    const SOLD = 'sold';
    const HIDDEN = 'hidden';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'serial_number',
        'sku',
        'instrument_spec_id',
        'price',
        'condition',
        'stock_status',
        'year_made',
        'quantity',
        'featured',
        'published_at',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'featured' => 'boolean',
            'published_at' => 'datetime',
            'year_made' => 'date',
        ];
    }

    public static function stockStatus(): array
    {
        return [
            self::AVAILABLE,
            self::RESERVED,
            self::SOLD,
            self::HIDDEN,
        ];
    }

    public static function conditionTypes(): array
    {
        return [
            self::NEW_CONDITION,
            self::USED_CONDITION,
            self::VINTAGE_CONDITION,
        ];
    }
}
