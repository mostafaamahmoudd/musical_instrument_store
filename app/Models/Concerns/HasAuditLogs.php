<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;

trait HasAuditLogs
{
    public function auditLogs()
    {
        return $this->morphMany(AuditLog::class, 'auditable');
    }
}
