<?php

namespace App\Models;

use App\Models\Concerns\HasAuditLogs;
use App\Models\Relations\InstrumentRelations;
use App\Models\Scopes\InstrumentScopes;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Enums\Fit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Instrument extends Model implements HasMedia
{
    use HasAuditLogs;
    use HasFactory;
    use InstrumentRelations;
    use InstrumentScopes;
    use InteractsWithMedia;

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

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Fit::Crop, 300, 300)
            ->performOnCollections('gallery')
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->fit(Fit::Contain, 1000, 1000)
            ->performOnCollections('gallery')
            ->nonQueued();
    }
}
