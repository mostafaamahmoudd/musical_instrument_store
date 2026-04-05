<?php

namespace App\Models;

use App\Models\Concerns\HasAuditLogs;
use Illuminate\Database\Eloquent\Model;

class Builder extends Model
{
    use HasAuditLogs;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'country',
        'is_active',
    ];

    public function instrumentSpecs()
    {
        return $this->hasMany(InstrumentSpec::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
