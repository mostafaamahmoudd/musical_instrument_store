<?php

namespace App\Models;

use App\Models\Concerns\HasAuditLogs;
use App\Models\Relations\InquiryRelations;
use App\Models\Scopes\InquiryScopes;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasAuditLogs;
    use InquiryRelations;
    use InquiryScopes;

    /*
     * code for different statuses
     */
    const NEW = 'new';

    const IN_PROGRESS = 'in_progress';

    const CLOSED = 'closed';

    const SPAM = 'spam';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'assigned_admin_id',
        'instrument_id',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
    ];

    public static function statuses(): array
    {
        return [
            self::NEW,
            self::IN_PROGRESS,
            self::CLOSED,
            self::SPAM,
        ];
    }
}
