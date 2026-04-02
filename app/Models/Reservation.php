<?php

namespace App\Models;

use App\Models\Relations\ReservationRelations;
use App\Models\Scopes\ReservationScopes;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use ReservationRelations;
    use ReservationScopes;

    /*
     * code for different statuses
     */
    const PENDING = 'pending';

    const APPROVED = 'approved';

    const REJECTED = 'rejected';

    const EXPIRED = 'expired';

    const CANCELLED = 'cancelled';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'instrument_id',
        'status',
        'reserved_until',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'reserved_until' => 'datetime',
        ];
    }

    public static function statuses(): array
    {
        return [
            self::PENDING,
            self::APPROVED,
            self::REJECTED,
            self::EXPIRED,
            self::CANCELLED,
        ];
    }
}
