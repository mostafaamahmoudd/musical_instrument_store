<?php

namespace App\Models;

use App\Models\Concerns\HasAuditLogs;
use App\Models\Relations\WoodRelations;
use Illuminate\Database\Eloquent\Model;

class Wood extends Model
{
    use HasAuditLogs;
    use WoodRelations;

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
